<?php

namespace App\Services;

use App\Enums\InvoiceStatus;
use App\Enums\StockMovementType;
use App\Exceptions\InsufficientStockException;
use App\Exceptions\InvoiceAlreadyPostedException;
use App\Models\SaleInvoice;
use App\Models\SaleInvoiceLineBatch;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class SaleInvoiceService
{
    public function __construct(
        private StockLedgerService $stockLedger,
        private JournalService $journal,
        private AuditLogService $auditLog,
        private DocumentSequenceService $sequence,
        private StockService $stock,
    ) {}

    public function create(array $data): SaleInvoice
    {
        return DB::transaction(function () use ($data) {
            $invoice = SaleInvoice::create([
                'bill_number'     => $this->sequence->next('INV'),
                'date'            => $data['date'],
                'customer_id'     => $data['customer_id'] ?? null,
                'payment_type'    => $data['payment_type'],
                'season'          => $data['season'],
                'bill_book_number'=> $data['bill_book_number'] ?? null,
                'total_paisas'    => $data['total_paisas'],
                'status'          => InvoiceStatus::Draft,
                'created_by'      => Auth::id(),
            ]);

            foreach ($data['lines'] as $line) {
                $invoice->lines()->create($line);
            }

            $this->auditLog->log('create', SaleInvoice::class, $invoice->id, [], $invoice->toArray());

            return $invoice->load('lines.product', 'customer.party');
        });
    }

    /**
     * Post a draft sale invoice: deduct stock via FIFO, persist batch allocations,
     * record audit movement, mark posted. All inside a single transaction.
     *
     * @throws InvoiceAlreadyPostedException if already posted
     * @throws InsufficientStockException    if any line lacks stock (FIFO threw RuntimeException)
     */
    public function post(SaleInvoice $invoice): SaleInvoice
    {
        if ($invoice->status === InvoiceStatus::Posted) {
            throw new InvoiceAlreadyPostedException("Invoice #{$invoice->bill_number} is already posted.");
        }

        return DB::transaction(function () use ($invoice) {
            foreach ($invoice->lines as $line) {
                try {
                    // FIFO deduction — returns the actual batches that supplied this qty.
                    $allocations = $this->stock->deductStock(
                        productId: $line->product_id,
                        qty:       (float) $line->quantity,
                        batchId:   $line->batch_id, // null = pure FIFO; non-null = forced batch
                    );
                } catch (RuntimeException $e) {
                    // Translate to the typed exception the controller catches.
                    throw new InsufficientStockException($e->getMessage(), 0, $e);
                }

                // Persist allocations to the pivot so sale returns can restore the same batches
                // and profit reports use the snapshot cost.
                foreach ($allocations as $alloc) {
                    SaleInvoiceLineBatch::create([
                        'sale_invoice_line_id' => $line->id,
                        'batch_id'             => $alloc['batch_id'],
                        'quantity'             => $alloc['qty'],
                        'cost_price_paisas'    => $alloc['cost_price_paisas'],
                    ]);
                }

                // Audit-trail movement — used by Stock Movement Report and StockAlert checks.
                $this->stockLedger->recordMovement(
                    $line->product_id,
                    StockMovementType::SaleOut,
                    (float) $line->quantity,
                    SaleInvoice::class,
                    $invoice->id
                );
            }

            $invoice->update(['status' => InvoiceStatus::Posted, 'posted_at' => now()]);

            $this->auditLog->log('post', SaleInvoice::class, $invoice->id,
                ['status' => InvoiceStatus::Draft->value],
                ['status' => InvoiceStatus::Posted->value]
            );

            return $invoice->fresh();
        });
    }

    public function updateLines(SaleInvoice $invoice, array $lines): void
    {
        $invoice->lines()->delete();
        foreach ($lines as $line) {
            $invoice->lines()->create($line);
        }
    }

}
