<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesReturnLine extends Model
{
    protected $fillable = [
        'sales_return_id',
        'product_id',
        'original_line_id',
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

    public function return()       { return $this->belongsTo(SalesReturn::class, 'sales_return_id'); }
    public function product()      { return $this->belongsTo(Product::class); }
    public function originalLine() { return $this->belongsTo(SaleInvoiceLine::class, 'original_line_id'); }
}
