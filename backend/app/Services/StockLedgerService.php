<?php

namespace App\Services;

use App\Enums\StockMovementType;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\StockAlert;
use Illuminate\Support\Facades\Auth;

class StockLedgerService
{
    public function recordMovement(
        int $productId,
        StockMovementType $type,
        float $quantity,
        string $referenceType,
        int $referenceId,
        ?string $notes = null
    ): StockMovement {
        $change = in_array($type, [
            StockMovementType::PurchaseIn,
            StockMovementType::ReturnIn,
            StockMovementType::AdjustmentIn,
        ]) ? abs($quantity) : -abs($quantity);

        $movement = StockMovement::create([
            'product_id'      => $productId,
            'movement_type'   => $type,
            'quantity_change' => $change,
            'reference_type'  => $referenceType,
            'reference_id'    => $referenceId,
            'notes'           => $notes,
            'created_by'      => Auth::id(),
        ]);

        $this->checkAlert($productId);

        return $movement;
    }

    private function checkAlert(int $productId): void
    {
        $product      = Product::find($productId);
        $currentStock = $product->currentStock();

        if ($currentStock <= $product->min_stock_quantity) {
            StockAlert::updateOrCreate(
                ['product_id' => $productId],
                ['current_stock' => $currentStock, 'min_stock' => $product->min_stock_quantity, 'alerted_at' => now()]
            );
        } else {
            StockAlert::where('product_id', $productId)->delete();
        }
    }
}
