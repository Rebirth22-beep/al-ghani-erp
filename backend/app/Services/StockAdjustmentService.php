<?php

namespace App\Services;

use App\Enums\StockMovementType;
use App\Models\StockAdjustment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * StockAdjustmentService — manual stock corrections.
 *
 * Creates the adjustment row AND the corresponding stock movement in one transaction.
 * Audit log handled by AuditLogService injection.
 */
class StockAdjustmentService
{
    public function __construct(
        private StockLedgerService $stockLedger,
        private AuditLogService $auditLog,
    ) {}

    public function create(array $data): StockAdjustment
    {
        return DB::transaction(function () use ($data) {
            $adjustment = StockAdjustment::create([...$data, 'created_by' => Auth::id()]);

            $movementType = $data['adjustment_type'] === 'increase'
                ? StockMovementType::AdjustmentIn
                : StockMovementType::AdjustmentOut;

            $this->stockLedger->recordMovement(
                $data['product_id'],
                $movementType,
                (float) $data['quantity'],
                StockAdjustment::class,
                $adjustment->id,
                $data['reason'] ?? null,
            );

            $this->auditLog->log('create', StockAdjustment::class, $adjustment->id, [], $adjustment->toArray());

            return $adjustment->load('product');
        });
    }

    public function delete(StockAdjustment $adjustment): void
    {
        // Adjustments are soft-deleted (audit trail). Stock reversal is intentionally
        // NOT done here — that requires a NEW adjustment in the opposite direction.
        $adjustment->delete();
        $this->auditLog->log('delete', StockAdjustment::class, $adjustment->id, [], []);
    }
}
