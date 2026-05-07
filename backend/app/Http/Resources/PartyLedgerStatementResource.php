<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PartyLedgerStatementResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'opening_paisas' => $this['opening_paisas'],
            'lines'          => $this['lines'],
            'closing_paisas' => $this['closing_paisas'],
        ];
    }
}
