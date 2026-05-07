<?php

namespace App\Models;

use App\Enums\InvoiceStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseEntry extends Model
{
    use SoftDeletes;

    protected $fillable = ['reference_number', 'date', 'supplier_id', 'total_paisas', 'status', 'created_by'];

    protected function casts(): array
    {
        return [
            'date'        => 'date',
            'total_paisas'=> 'integer',
            'status'      => InvoiceStatus::class,
        ];
    }

    public function supplier()  { return $this->belongsTo(Supplier::class); }
    public function lines()     { return $this->hasMany(PurchaseEntryLine::class); }
    public function createdBy() { return $this->belongsTo(User::class, 'created_by'); }
}
