<?php

namespace App\Services;

use App\Enums\InvoiceStatus;
use App\Enums\StockMovementType;
use App\Models\PurchaseEntry;
use App\Models\PurchaseReturn;
use App\Models\PurchaseReturnLine;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RuntimeException;

/**
 * ============================================================
 *  PurchaseReturnService — process purchase returns
 * ============================================================
 *
 *  Mirror of SalesReturnService, but for goods we send BACK to suppliers.
 *
 *  - DEDUCTS stock (we no longer have those goods).
 *  - Caps return qty against original purchase qty.
 *  - Issues a return number from DocumentSequenceService ("PR-00042").
 *  - Always posted immediately (no draft state).
 * ============================================================
 */
class PurchaseReturnService
{
    public function __construct(
        private DocumentSequenceService $sequence,
        private StockService $stock,
        private StockLedgerService $stockLedger,
        private AuditLogService $auditLog,
    ) {}

    public function create(array $data): PurchaseReturn
    {
        return DB::transaction(function () use ($data) {
            $purchase = isset($data['purchase_entry_id'])
                ? PurchaseEntry::lockForUpdate()->find($data['purchase_entry_id'])
                : null;

            if ($purchase && $purchase->status !== InvoiceStatus::Posted) {
                throw new RuntimeException("Cannot return against an unposted purchase.");
            }

            $return = PurchaseReturn::create([
                'return_number'     => $this->sequence->next('PR'),
                'date'              => $data['date'],
                'fiscal_year'       => Setting::currentFiscalYear(\Carbon\Carbon::parse($data['date'])),
                'purchase_entry_id' => $data['purchase_entry_id'] ?? null,
                'supplier_id'       => $data['supplier_id'] ?? $purchase?->supplier_id,
                'settlement_type'   => $data['settlement_type'] ?? 'credit',
                'total_paisas'      => $data['total_paisas'],
                'reason'            => $data['reason'] ?? null,
                'created_by'        => Auth::id(),
            ]);

            foreach ($data['lines'] as $lineData) {
                $this->processLine($return, $lineData, $purchase);
            }

            $this->auditLog->log('create', PurchaseReturn::class, $return->id, [], $return->toArray());

            return $return->load('lines.product', 'supplier.party', 'purchase');
        });
    }

    private function processLine(PurchaseReturn $return, array $lineData, ?PurchaseEntry $purchase): void
    {
        if (! empty($lineData['original_line_id']) && $purchase) {
            $this->validateCap($lineData, $purchase);
        }

        $line = PurchaseReturnLine::create([
            'purchase_return_id' => $return->id,
            'product_id'         => $lineData['product_id'],
            'original_line_id'   => $lineData['original_line_id'] ?? null,
            'batch_id'           => $lineData['batch_id'] ?? null,
            'quantity'           => $lineData['quantity'],
            'unit'               => $lineData['unit'],
            'rate_paisas'        => $lineData['rate_paisas'],
            'total_paisas'       => $lineData['total_paisas'],
        ]);

        // Deduct stock — from chosen batch if specified, else FIFO.
        try {
            $this->stock->deductStock(
                productId: $line->product_id,
                qty:       (float) $line->quantity,
                batchId:   $line->batch_id,
            );
        } catch (RuntimeException $e) {
            throw new RuntimeException("Cannot return goods we no longer have: {$e->getMessage()}", 0, $e);
        }

        $this->stockLedger->recordMovement(
            $line->product_id,
            StockMovementType::ReturnOut,
            (float) $line->quantity,
            PurchaseReturn::class,
            $return->id
        );
    }

    private function validateCap(array $lineData, PurchaseEntry $purchase): void
    {
        $originalLine = $purchase->lines()->find($lineData['original_line_id']);
        if (! $originalLine) {
            throw new RuntimeException("Original line not found on purchase #{$purchase->reference_number}.");
        }

        $alreadyReturned = (float) PurchaseReturnLine::where('original_line_id', $originalLine->id)->sum('quantity');
        $remaining       = (float) $originalLine->quantity - $alreadyReturned;

        if ($lineData['quantity'] > $remaining) {
            throw new RuntimeException(
                "Cannot return {$lineData['quantity']} units — only {$remaining} remain on this purchase line."
            );
        }
    }
}
