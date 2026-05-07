<?php

namespace App\Models;

use App\Enums\InvoiceStatus;
use App\Enums\PaymentType;
use App\Enums\Season;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaleInvoice extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'bill_number', 'date', 'customer_id', 'payment_type', 'season',
        'bill_book_number', 'total_paisas', 'status', 'posted_at', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'date'        => 'date',
            'posted_at'   => 'datetime',
            'status'      => InvoiceStatus::class,
            'payment_type'=> PaymentType::class,
            'season'      => Season::class,
            'total_paisas'=> 'integer',
        ];
    }

    public function customer() { return $this->belongsTo(Customer::class); }
    public function lines()    { return $this->hasMany(SaleInvoiceLine::class); }
    public function createdBy(){ return $this->belongsTo(User::class, 'created_by'); }

    public function getPartyNameAttribute(): string
    {
        return $this->customer?->party?->name ?? '';
    }
}
