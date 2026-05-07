<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TrialBalanceLineResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'account_id'   => $this['account_id'],
            'account_code' => $this['account_code'],
            'account_name' => $this['account_name'],
            'account_type' => $this['account_type'],
            'total_debit'  => $this['total_debit'],
            'total_credit' => $this['total_credit'],
        ];
    }
}
