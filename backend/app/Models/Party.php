<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Party extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'phone', 'address', 'city', 'opening_balance_paisas', 'party_type'];

    public function customer() { return $this->hasOne(Customer::class); }
    public function supplier() { return $this->hasOne(Supplier::class); }
    public function partner()  { return $this->hasOne(Partner::class); }
}
