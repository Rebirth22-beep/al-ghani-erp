<?php

namespace App\Models;

use App\Enums\WorkerPayType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Worker extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'phone', 'pay_type', 'rate_paisas', 'is_active'];

    protected function casts(): array
    {
        return [
            'pay_type'    => WorkerPayType::class,
            'rate_paisas' => 'integer',
            'is_active'   => 'boolean',
        ];
    }

    public function attendance() { return $this->hasMany(WorkerAttendance::class); }
    public function salaries()   { return $this->hasMany(WorkerSalary::class); }
}
