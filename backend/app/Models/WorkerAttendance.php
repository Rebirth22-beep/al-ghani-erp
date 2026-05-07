<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkerAttendance extends Model
{
    protected $fillable = ['worker_id', 'date', 'present', 'notes'];

    protected function casts(): array
    {
        return ['date' => 'date', 'present' => 'boolean'];
    }

    public function worker() { return $this->belongsTo(Worker::class); }
}
