<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkerSalaryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'worker_id'      => $this->worker_id,
            'worker_name'    => $this->worker?->name,
            'month'          => $this->month,
            'amount_paisas'  => $this->amount_paisas,
            'paid_at'        => $this->paid_at?->toDateTimeString(),
            'notes'          => $this->notes,
        ];
    }
}
