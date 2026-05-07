<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesReturn extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'return_number', 'date', 'fiscal_year',
        'sale_invoice_id', 'customer_id',
        'settlement_type', 'total_paisas', 'reason',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'date'         => 'date',
            'total_paisas' => 'integer',
        ];
    }

    public function lines()    { return $this->hasMany(SalesReturnLine::class); }
    public function customer() { return $this->belongsTo(Customer::class); }
    public function invoice()  { return $this->belongsTo(SaleInvoice::class, 'sale_invoice_id'); }
}
