<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;

    protected $fillable = ['party_id', 'credit_limit_paisas'];

    public function party()        { return $this->belongsTo(Party::class); }
    public function saleInvoices() { return $this->hasMany(SaleInvoice::class); }
}
