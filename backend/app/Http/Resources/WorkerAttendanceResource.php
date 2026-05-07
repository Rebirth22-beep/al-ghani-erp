<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkerAttendanceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'worker_id'   => $this->worker_id,
            'worker_name' => $this->worker?->name,
            'date'        => $this->date?->toDateString(),
            'present'     => $this->present,
            'notes'       => $this->notes,
        ];
    }
}
