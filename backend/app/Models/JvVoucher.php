<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JvVoucher extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'voucher_number', 'date', 'fiscal_year',
        'narration', 'total_paisas', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'date'         => 'date',
            'total_paisas' => 'integer',
        ];
    }

    public function lines() { return $this->hasMany(JvVoucherLine::class); }
}
