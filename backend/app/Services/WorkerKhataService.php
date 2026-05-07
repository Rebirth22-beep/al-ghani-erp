<?php

namespace App\Services;

use App\Models\Worker;
use App\Models\WorkerAttendance;
use App\Models\WorkerSalary;

class WorkerKhataService
{
    public function markAttendance(array $data): WorkerAttendance
    {
        return WorkerAttendance::updateOrCreate(
            ['worker_id' => $data['worker_id'], 'date' => $data['date']],
            ['present' => $data['present'], 'notes' => $data['notes'] ?? null]
        );
    }

    public function paySalary(array $data): WorkerSalary
    {
        return WorkerSalary::create([
            'worker_id'    => $data['worker_id'],
            'month'        => $data['month'],
            'amount_paisas'=> $data['amount_paisas'],
            'paid_at'      => now(),
            'notes'        => $data['notes'] ?? null,
        ]);
    }

    public function monthlyReport(int $workerId, string $month): array
    {
        $worker = Worker::findOrFail($workerId);

        $attendance = WorkerAttendance::where('worker_id', $workerId)
            ->whereYear('date',  substr($month, 0, 4))
            ->whereMonth('date', substr($month, 5, 2))
            ->get();

        $daysPresent = $attendance->where('present', true)->count();
        $earned      = $worker->pay_type->value === 'daily'
            ? $daysPresent * $worker->rate_paisas
            : $worker->rate_paisas;

        return [
            'worker'      => $worker,
            'month'       => $month,
            'days_present'=> $daysPresent,
            'earned_paisas'=> $earned,
            'attendance'  => $attendance,
        ];
    }
}
