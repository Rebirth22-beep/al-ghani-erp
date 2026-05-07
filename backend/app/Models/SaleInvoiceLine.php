<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleInvoiceLine extends Model
{
    protected $fillable = ['sale_invoice_id', 'product_id', 'batch_id', 'quantity', 'unit', 'rate_paisas', 'total_paisas'];

    protected function casts(): array
    {
        return [
            'quantity'     => 'float',
            'rate_paisas'  => 'integer',
            'total_paisas' => 'integer',
        ];
    }

    public function invoice()    { return $this->belongsTo(SaleInvoice::class, 'sale_invoice_id'); }
    public function product()    { return $this->belongsTo(Product::class); }
    public function batch()      { return $this->belongsTo(ProductBatch::class); }
    public function allocations(){ return $this->hasMany(SaleInvoiceLineBatch::class, 'sale_invoice_line_id'); }
}
