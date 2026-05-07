<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkerSalary extends Model
{
    protected $fillable = ['worker_id', 'month', 'amount_paisas', 'paid_at', 'notes'];

    protected function casts(): array
    {
        return ['paid_at' => 'datetime', 'amount_paisas' => 'integer'];
    }

    public function worker() { return $this->belongsTo(Worker::class); }
}
