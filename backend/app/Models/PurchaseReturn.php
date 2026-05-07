<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseReturn extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'return_number', 'date', 'fiscal_year',
        'purchase_entry_id', 'supplier_id',
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

    public function lines()    { return $this->hasMany(PurchaseReturnLine::class); }
    public function supplier() { return $this->belongsTo(Supplier::class); }
    public function purchase() { return $this->belongsTo(PurchaseEntry::class, 'purchase_entry_id'); }
}
