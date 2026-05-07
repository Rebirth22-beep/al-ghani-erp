<?php

namespace App\Models;

use App\Enums\StockMovementType;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    protected $fillable = [
        'product_id', 'movement_type', 'quantity_change',
        'reference_type', 'reference_id', 'notes', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'movement_type'   => StockMovementType::class,
            'quantity_change' => 'float',
        ];
    }

    public function product() { return $this->belongsTo(Product::class); }
}
