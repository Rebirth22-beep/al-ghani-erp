<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GeneralLedgerLineResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                  => $this->id,
            'date'                => $this->entry?->date?->toDateString(),
            'chart_of_account_id' => $this->chart_of_account_id,
            'account_code'        => $this->chartOfAccount?->code,
            'account_name'        => $this->chartOfAccount?->name,
            'description'         => $this->description ?? $this->entry?->description,
            'debit_paisas'        => $this->debit_paisas,
            'credit_paisas'       => $this->credit_paisas,
        ];
    }
}
