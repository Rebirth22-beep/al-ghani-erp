<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CashFlowLineResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'date'           => $this['date'],
            'account'        => $this['account'],
            'description'    => $this['description'],
            'in_paisas'      => $this['in_paisas'],
            'out_paisas'     => $this['out_paisas'],
            'balance_paisas' => $this['balance_paisas'],
        ];
    }
}
