<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockAlert extends Model
{
    protected $fillable = ['product_id', 'current_stock', 'min_stock', 'alerted_at'];

    protected function casts(): array
    {
        return ['alerted_at' => 'datetime'];
    }

    public function product() { return $this->belongsTo(Product::class); }
}
