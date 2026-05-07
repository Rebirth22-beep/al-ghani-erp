<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\PurchaseEntryLine;
use App\Models\SaleInvoiceLine;
use Illuminate\Support\Facades\DB;
use RuntimeException;

/**
 * ============================================================
 *  StockService — FIFO stock operations
 * ============================================================
 *
 *  Why is this separate from StockLedgerService?
 *  ---------------------------------------------
 *  - StockLedgerService records *movements* (audit trail) and triggers stock alerts.
 *  - StockService performs the actual *FIFO arithmetic* on batches.
 *  Each service has one job. Calls to StockService should also call
 *  StockLedgerService->recordMovement() so the audit trail stays complete.
 *
 *  FIFO = "First In, First Out".
 *  Oldest batch (smallest received_date) is sold first.
 *
 *  Beginner rules:
 *  - Quantity is a decimal (e.g. 1.5 kg). Money is integer paisas.
 *  - Always pass an explicit $batchId when you can (lets the user pick the batch).
 *  - Stock locks happen automatically inside DB::transaction — never call deduct()
 *    or restore() outside a transaction context unless you wrap it yourself.
 * ============================================================
 */
class StockService
{
    /**
     * Deduct stock for a sale (or purchase return).
     *
     * @param  int       $productId  Which product to deduct from
     * @param  float     $qty        How much to deduct (positive)
     * @param  int|null  $batchId    If provided, deduct only from this batch
     * @return array<int,array{batch_id:int, qty:float, cost_price_paisas:int}>
     *                              Actual allocations — caller persists these to a pivot.
     *
     * @throws RuntimeException on insufficient stock or invalid batch.
     */
    public function deductStock(int $productId, float $qty, ?int $batchId = null): array
    {
        if ($qty <= 0) {
            throw new RuntimeException("Deduct quantity must be positive (got {$qty}).");
        }

        return DB::transaction(function () use ($productId, $qty, $batchId) {
            $product = Product::lockForUpdate()->findOrFail($productId);

            $batches = $this->batchesToConsume($productId, $batchId);
            $remaining = $qty;
            $allocations = [];

            foreach ($batches as $batch) {
                if ($remaining <= 0) {
                    break;
                }

                $take = min($remaining, (float) $batch->qty_remaining);
                $batch->qty_remaining = (float) $batch->qty_remaining - $take;

                if ($batch->qty_remaining <= 0) {
                    $batch->qty_remaining = 0;
                    $batch->status = 'consumed';
                }
                $batch->save();

                $allocations[] = [
                    'batch_id'          => $batch->id,
                    'qty'               => $take,
                    'cost_price_paisas' => (int) $batch->cost_price_paisas,
                ];

                $remaining -= $take;
            }

            if ($remaining > 0) {
                throw new RuntimeException(
                    "Insufficient stock for product #{$productId}. Short by {$remaining} unit(s)."
                );
            }

            // Keep summary column in sync with batches.
            $product->decrement('current_stock', $qty);

            return $allocations;
        });
    }

    /**
     * Restore stock from a sale return / purchase entry edit.
     *
     * If $batchId is provided, restores into that specific batch (validates it isn't expired).
     * If null, restores into the first active batch found.
     *
     * @throws RuntimeException if the batch is expired (cannot put returned goods into expired stock).
     */
    public function restoreStock(int $productId, float $qty, ?int $batchId = null): void
    {
        if ($qty <= 0) {
            throw new RuntimeException("Restore quantity must be positive (got {$qty}).");
        }

        DB::transaction(function () use ($productId, $qty, $batchId) {
            $product = Product::lockForUpdate()->findOrFail($productId);

            $batch = $batchId
                ? ProductBatch::where('product_id', $productId)->lockForUpdate()->findOrFail($batchId)
                : ProductBatch::where('product_id', $productId)
                    ->where('status', 'active')
                    ->orderBy('received_date')
                    ->lockForUpdate()
                    ->first();

            if (! $batch) {
                throw new RuntimeException("No batch available to restore stock into for product #{$productId}.");
            }

            if ($batch->expiry_date && $batch->expiry_date->isPast()) {
                throw new RuntimeException("Cannot restore into expired batch #{$batch->id}.");
            }

            $batch->qty_remaining = (float) $batch->qty_remaining + $qty;
            if ($batch->status === 'consumed' && $batch->qty_remaining > 0) {
                $batch->status = 'active';
            }
            $batch->save();

            $product->increment('current_stock', $qty);
        });
    }

    /**
     * Recompute current_stock from posted documents. For import/reconciliation only.
     *
     * Formula: posted_purchases − posted_sales + sale_returns − purchase_returns
     */
    public function recalculate(int $productId): float
    {
        // Sale return / purchase return tables don't exist as line tables yet (Phase 3.3/3.4).
        // We compute from the available signals; later phases extend this.
        $purchased = (float) PurchaseEntryLine::query()
            ->where('product_id', $productId)
            ->whereHas('entry', fn($q) => $q->where('status', 'posted'))
            ->sum('quantity');

        $sold = (float) SaleInvoiceLine::query()
            ->where('product_id', $productId)
            ->whereHas('invoice', fn($q) => $q->where('status', 'posted'))
            ->sum('quantity');

        $stock = $purchased - $sold;

        Product::where('id', $productId)->update(['current_stock' => $stock]);
        return $stock;
    }

    /**
     * Weighted average cost in paisas, computed only over active batches with qty_remaining > 0.
     * Returns 0 when total qty is zero.
     */
    public function averageCost(int $productId): int
    {
        $row = DB::table('product_batches')
            ->where('product_id', $productId)
            ->where('status', 'active')
            ->where('qty_remaining', '>', 0)
            ->selectRaw('SUM(qty_remaining * cost_price_paisas) as numerator, SUM(qty_remaining) as denominator')
            ->first();

        $num = (float) ($row->numerator ?? 0);
        $den = (float) ($row->denominator ?? 0);

        return $den > 0 ? (int) round($num / $den) : 0;
    }

    /**
     * Returns the list of batches to consume from, in FIFO order.
     * If $batchId given, returns only that batch (after validating ownership).
     */
    private function batchesToConsume(int $productId, ?int $batchId): \Illuminate\Database\Eloquent\Collection
    {
        if ($batchId !== null) {
            $batch = ProductBatch::where('product_id', $productId)
                ->lockForUpdate()
                ->findOrFail($batchId);
            return ProductBatch::query()->whereKey($batch->id)->lockForUpdate()->get();
        }

        return ProductBatch::query()
            ->where('product_id', $productId)
            ->where('status', 'active')
            ->where('qty_remaining', '>', 0)
            ->orderBy('received_date')   // oldest first = FIFO
            ->orderBy('id')              // tiebreaker
            ->lockForUpdate()
            ->get();
    }
}
