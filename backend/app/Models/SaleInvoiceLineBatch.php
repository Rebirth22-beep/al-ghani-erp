<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Pivot row recording which batch supplied which qty for a given sale invoice line.
 * Created at posting time by StockService::deductStock() return values.
 *
 * IMPORTANT: cost_price_paisas is a SNAPSHOT — never re-read from the batch later.
 * Historic profit reports must use this stored cost, not the current batch cost.
 */
class SaleInvoiceLineBatch extends Model
{
    protected $fillable = [
        'sale_invoice_line_id',
        'batch_id',
        'quantity',
        'cost_price_paisas',
    ];

    protected function casts(): array
    {
        return [
            'quantity'          => 'float',
            'cost_price_paisas' => 'integer',
        ];
    }

    public function line()  { return $this->belongsTo(SaleInvoiceLine::class, 'sale_invoice_line_id'); }
    public function batch() { return $this->belongsTo(ProductBatch::class, 'batch_id'); }
}
