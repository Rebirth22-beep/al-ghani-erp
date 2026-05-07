<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockAdjustment extends Model
{
    use SoftDeletes;

    protected $fillable = ['product_id', 'date', 'adjustment_type', 'quantity', 'reason', 'created_by'];

    protected function casts(): array
    {
        return ['date' => 'date', 'quantity' => 'float'];
    }

    public function product()   { return $this->belongsTo(Product::class); }
    public function createdBy() { return $this->belongsTo(User::class, 'created_by'); }
}
