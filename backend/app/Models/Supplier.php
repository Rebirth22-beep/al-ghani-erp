<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use SoftDeletes;

    protected $fillable = ['party_id'];

    public function party()           { return $this->belongsTo(Party::class); }
    public function purchaseEntries() { return $this->hasMany(PurchaseEntry::class); }
}
