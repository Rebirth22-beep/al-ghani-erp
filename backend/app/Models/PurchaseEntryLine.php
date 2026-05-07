<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseEntryLine extends Model
{
    protected $fillable = ['purchase_entry_id', 'product_id', 'batch_id', 'quantity', 'unit', 'rate_paisas', 'total_paisas'];

    protected function casts(): array
    {
        return ['quantity' => 'float', 'rate_paisas' => 'integer', 'total_paisas' => 'integer'];
    }

    public function entry()   { return $this->belongsTo(PurchaseEntry::class, 'purchase_entry_id'); }
    public function product() { return $this->belongsTo(Product::class); }
}
