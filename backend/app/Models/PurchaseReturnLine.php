<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseReturnLine extends Model
{
    protected $fillable = [
        'purchase_return_id',
        'product_id',
        'original_line_id',
        'batch_id',
        'quantity',
        'unit',
        'rate_paisas',
        'total_paisas',
    ];

    protected function casts(): array
    {
        return [
            'quantity'     => 'float',
            'rate_paisas'  => 'integer',
            'total_paisas' => 'integer',
        ];
    }

    public function return()       { return $this->belongsTo(PurchaseReturn::class, 'purchase_return_id'); }
    public function product()      { return $this->belongsTo(Product::class); }
    public function originalLine() { return $this->belongsTo(PurchaseEntryLine::class, 'original_line_id'); }
    public function batch()        { return $this->belongsTo(ProductBatch::class, 'batch_id'); }
}
