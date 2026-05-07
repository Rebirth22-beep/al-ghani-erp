<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BalanceSheetResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'as_of'       => $this['as_of'],
            'assets'      => $this['assets'],
            'liabilities' => $this['liabilities'],
            'equity'      => $this['equity'],
            'balanced'    => $this['balanced'],
        ];
    }
}
