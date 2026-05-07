<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Partner extends Model
{
    use SoftDeletes;

    protected $fillable = ['party_id', 'share_percentage'];

    public function party() { return $this->belongsTo(Party::class); }
}
