<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'category', 'unit', 'pack_size',
        'purchase_rate_paisas', 'sale_rate_paisas',
        'min_stock_quantity', 'max_stock', 'current_stock',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'min_stock_quantity' => 'integer',
            'max_stock'          => 'integer',
            'current_stock'      => 'float',
        ];
    }

    public function batches()       { return $this->hasMany(ProductBatch::class); }
    public function stockMovements(){ return $this->hasMany(StockMovement::class); }
    public function stockAlerts()   { return $this->hasMany(StockAlert::class); }

    /**
     * Live stock recomputed from posted movements. For reconciliation/audit only.
     * Normal reads should just use the denormalized `current_stock` column.
     * Kept as a method (not removed) so any caller doing `$product->currentStock()` still works.
     */
    public function currentStock(): float
    {
        return (float) ($this->current_stock ?? $this->stockMovements()->sum('quantity_change'));
    }
}
