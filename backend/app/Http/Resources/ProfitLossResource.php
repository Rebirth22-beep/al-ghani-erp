<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfitLossResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'revenue'            => $this['revenue'],
            'expenses'           => $this['expenses'],
            'net_profit_paisas'  => $this['net_profit_paisas'],
        ];
    }
}
