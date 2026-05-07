<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JvVoucherResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'voucher_number' => $this->voucher_number,
            'date'           => $this->date?->toDateString(),
            'fiscal_year'    => $this->fiscal_year,
            'narration'      => $this->narration,
            'total_paisas'   => $this->total_paisas,
            'lines'          => $this->whenLoaded('lines', fn() => $this->lines->map(fn($line) => [
                'id'                  => $line->id,
                'chart_of_account_id' => $line->chart_of_account_id,
                'account_code'        => $line->account?->code,
                'account_name'        => $line->account?->name,
                'debit_paisas'        => $line->debit_paisas,
                'credit_paisas'       => $line->credit_paisas,
                'description'         => $line->description,
            ])),
        ];
    }
}
