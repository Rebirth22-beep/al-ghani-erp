<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductBatch extends Model
{
    protected $fillable = [
        'product_id',
        'batch_number',
        'quantity',           // legacy aggregate column — kept for backwards compatibility
        'qty_received',       // FIFO: how much came in originally
        'qty_remaining',      // FIFO: how much is still in stock
        'cost_price_paisas',  // per-unit cost when batch was received
        'received_date',      // FIFO ordering key (oldest first)
        'status',             // 'active' | 'consumed' | 'expired'
        'manufacture_date',
        'expiry_date',
    ];

    protected function casts(): array
    {
        return [
            'manufacture_date'  => 'date',
            'expiry_date'       => 'date',
            'received_date'     => 'date',
            'cost_price_paisas' => 'integer',
            'qty_received'      => 'float',
            'qty_remaining'     => 'float',
        ];
    }

    public function product() { return $this->belongsTo(Product::class); }
}
