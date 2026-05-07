<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                     => $this->id,
            'party_id'               => $this->party_id,
            'name'                   => $this->party?->name,
            'phone'                  => $this->party?->phone,
            'address'                => $this->party?->address,
            'city'                   => $this->party?->city,
            'opening_balance_paisas' => $this->party?->opening_balance_paisas,
            'credit_limit_paisas'    => $this->credit_limit_paisas,
        ];
    }
}
