<?php

namespace App\Services;

use App\Enums\InvoiceStatus;
use App\Enums\StockMovementType;
use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\PurchaseEntry;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseEntryService
{
    public function __construct(
        private StockLedgerService $stockLedger,
        private AuditLogService $auditLog,
        private DocumentSequenceService $sequence,
    ) {}

    public function create(array $data): PurchaseEntry
    {
        return DB::transaction(function () use ($data) {
            $entry = PurchaseEntry::create([
                'reference_number' => $data['reference_number'] ?? $this->sequence->next('PUR'),
                'date'             => $data['date'],
                'supplier_id'      => $data['supplier_id'],
                'total_paisas'     => $data['total_paisas'],
                'status'           => InvoiceStatus::Draft,
                'created_by'       => Auth::id(),
            ]);

            foreach ($data['lines'] as $line) {
                $entry->lines()->create($line);
            }

            $this->auditLog->log('create', PurchaseEntry::class, $entry->id, [], $entry->toArray());

            return $entry->load('lines.product', 'supplier.party');
        });
    }

    /**
     * Post a draft purchase: create a `product_batches` row per line, increment stock,
     * record audit movement, mark posted. All inside a single transaction.
     *
     * Each posted line creates ONE batch with:
     *   - qty_received  = qty_remaining = $line->quantity
     *   - cost_price_paisas = $line->rate_paisas
     *   - received_date = entry date
     *   - status = 'active'
     */
    public function post(PurchaseEntry $entry): PurchaseEntry
    {
        return DB::transaction(function () use ($entry) {
            foreach ($entry->lines as $line) {
                // 1. Create a batch row from this purchase line.
                ProductBatch::create([
                    'product_id'        => $line->product_id,
                    'batch_number'      => $line->batch_id ? "B{$line->batch_id}" : "PUR-{$entry->id}-{$line->id}",
                    'qty_received'      => $line->quantity,
                    'qty_remaining'     => $line->quantity,
                    'cost_price_paisas' => $line->rate_paisas,
                    'received_date'     => $entry->date,
                    'status'            => 'active',
                    'quantity'          => $line->quantity, // legacy aggregate column
                ]);

                // 2. Bump the denormalized current_stock cache on Product.
                Product::where('id', $line->product_id)->increment('current_stock', $line->quantity);

                // 3. Audit-trail movement (used by Stock Movement Report and StockAlert checks).
                $this->stockLedger->recordMovement(
                    $line->product_id,
                    StockMovementType::PurchaseIn,
                    (float) $line->quantity,
                    PurchaseEntry::class,
                    $entry->id
                );
            }

            $entry->update(['status' => InvoiceStatus::Posted]);
            $this->auditLog->log('post', PurchaseEntry::class, $entry->id,
                ['status' => InvoiceStatus::Draft->value],
                ['status' => InvoiceStatus::Posted->value]
            );

            return $entry->fresh();
        });
    }

}
