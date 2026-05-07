<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'phone'       => $this->phone,
            'pay_type'    => $this->pay_type->value,
            'rate_paisas' => $this->rate_paisas,
            'is_active'   => $this->is_active,
        ];
    }
}
