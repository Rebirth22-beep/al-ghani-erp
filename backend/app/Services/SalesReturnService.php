<?php

namespace App\Services;

use App\Enums\InvoiceStatus;
use App\Enums\StockMovementType;
use App\Models\SaleInvoice;
use App\Models\SaleInvoiceLineBatch;
use App\Models\SalesReturn;
use App\Models\SalesReturnLine;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RuntimeException;

/**
 * ============================================================
 *  SalesReturnService — process sale returns
 * ============================================================
 *
 *  What this does:
 *  ---------------
 *  1. Validates caps (return qty <= sold qty - already returned, etc.).
 *  2. Restores stock to the ORIGINAL batch allocations (so FIFO accounting stays correct).
 *  3. Issues a return number from DocumentSequenceService ("RET-00042").
 *  4. (Future) Posts ledger entries: CR party, DR sales account.
 *
 *  Beginner rule:
 *  Sale returns are ALWAYS posted immediately — there is no "draft return".
 * ============================================================
 */
class SalesReturnService
{
    public function __construct(
        private DocumentSequenceService $sequence,
        private StockService $stock,
        private StockLedgerService $stockLedger,
        private AuditLogService $auditLog,
    ) {}

    public function create(array $data): SalesReturn
    {
        return DB::transaction(function () use ($data) {
            $invoice = isset($data['sale_invoice_id'])
                ? SaleInvoice::lockForUpdate()->find($data['sale_invoice_id'])
                : null;

            if ($invoice && $invoice->status !== InvoiceStatus::Posted) {
                throw new RuntimeException("Cannot return against an unposted invoice.");
            }

            $return = SalesReturn::create([
                'return_number'   => $this->sequence->next('RET'),
                'date'            => $data['date'],
                'fiscal_year'     => Setting::currentFiscalYear(\Carbon\Carbon::parse($data['date'])),
                'sale_invoice_id' => $data['sale_invoice_id'] ?? null,
                'customer_id'     => $data['customer_id'] ?? $invoice?->customer_id,
                'settlement_type' => $data['settlement_type'] ?? 'credit',
                'total_paisas'    => $data['total_paisas'],
                'reason'          => $data['reason'] ?? null,
                'created_by'      => Auth::id(),
            ]);

            foreach ($data['lines'] as $lineData) {
                $this->processLine($return, $lineData, $invoice);
            }

            $this->auditLog->log('create', SalesReturn::class, $return->id, [], $return->toArray());

            return $return->load('lines.product', 'customer.party', 'invoice');
        });
    }

    /**
     * Validate caps for one line, persist it, and restore stock to the original batches.
     */
    private function processLine(SalesReturn $return, array $lineData, ?SaleInvoice $invoice): void
    {
        // Cap check: don't allow returning more than was sold (minus already returned).
        if (! empty($lineData['original_line_id']) && $invoice) {
            $this->validateCap($lineData, $invoice);
        }

        $line = SalesReturnLine::create([
            'sales_return_id'  => $return->id,
            'product_id'       => $lineData['product_id'],
            'original_line_id' => $lineData['original_line_id'] ?? null,
            'quantity'         => $lineData['quantity'],
            'unit'             => $lineData['unit'],
            'rate_paisas'      => $lineData['rate_paisas'],
            'total_paisas'     => $lineData['total_paisas'],
        ]);

        // Restore stock — into the original batch(es) when possible (preserves FIFO history).
        $this->restoreStockForLine($line);

        // Audit movement.
        $this->stockLedger->recordMovement(
            $line->product_id,
            StockMovementType::ReturnIn,
            (float) $line->quantity,
            SalesReturn::class,
            $return->id
        );
    }

    /**
     * Enforce the spec rule: return_qty <= original_qty - already_returned_qty
     *                       and return_rate <= original_rate.
     */
    private function validateCap(array $lineData, SaleInvoice $invoice): void
    {
        $originalLine = $invoice->lines()->find($lineData['original_line_id']);
        if (! $originalLine) {
            throw new RuntimeException("Original line not found on invoice #{$invoice->bill_number}.");
        }

        if ($lineData['rate_paisas'] > $originalLine->rate_paisas) {
            throw new RuntimeException(
                "Return rate ({$lineData['rate_paisas']}) cannot exceed original rate ({$originalLine->rate_paisas})."
            );
        }

        $alreadyReturned = (float) SalesReturnLine::where('original_line_id', $originalLine->id)->sum('quantity');
        $remaining       = (float) $originalLine->quantity - $alreadyReturned;

        if ($lineData['quantity'] > $remaining) {
            throw new RuntimeException(
                "Cannot return {$lineData['quantity']} units — only {$remaining} remain (already returned {$alreadyReturned})."
            );
        }
    }

    /**
     * Restore stock back to the same batches the original line consumed.
     * If no allocation history (e.g. legacy data), falls back to first active batch.
     */
    private function restoreStockForLine(SalesReturnLine $line): void
    {
        $originalAllocations = $line->original_line_id
            ? SaleInvoiceLineBatch::where('sale_invoice_line_id', $line->original_line_id)->get()
            : collect();

        if ($originalAllocations->isEmpty()) {
            // Fallback: no record of which batch was sold from. Restore into the first active batch.
            $this->stock->restoreStock($line->product_id, (float) $line->quantity);
            return;
        }

        // Distribute the return qty across the original allocations proportionally.
        $totalAllocated = (float) $originalAllocations->sum('quantity');
        $remaining      = (float) $line->quantity;

        foreach ($originalAllocations as $alloc) {
            if ($remaining <= 0) {
                break;
            }
            $share = min($remaining, ((float) $alloc->quantity / $totalAllocated) * (float) $line->quantity);
            // Last batch gets any rounding leftover.
            if ($alloc === $originalAllocations->last()) {
                $share = $remaining;
            }
            $this->stock->restoreStock($line->product_id, $share, $alloc->batch_id);
            $remaining -= $share;
        }
    }
}
