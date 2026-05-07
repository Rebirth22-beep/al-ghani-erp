<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JvVoucherLine extends Model
{
    protected $fillable = [
        'jv_voucher_id',
        'chart_of_account_id',
        'debit_paisas',
        'credit_paisas',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'debit_paisas'  => 'integer',
            'credit_paisas' => 'integer',
        ];
    }

    public function voucher() { return $this->belongsTo(JvVoucher::class, 'jv_voucher_id'); }
    public function account() { return $this->belongsTo(ChartOfAccount::class, 'chart_of_account_id'); }
}
