<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SalesReturnResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'return_number'   => $this->return_number,
            'date'            => $this->date?->toDateString(),
            'fiscal_year'     => $this->fiscal_year,
            'sale_invoice_id' => $this->sale_invoice_id,
            'customer_id'     => $this->customer_id,
            'settlement_type' => $this->settlement_type,
            'total_paisas'    => $this->total_paisas,
            'reason'          => $this->reason,
            'party_name'      => $this->customer?->party?->name,
            'lines'           => $this->whenLoaded('lines', fn() => $this->lines->map(fn($l) => [
                'id'            => $l->id,
                'product_id'    => $l->product_id,
                'product_name'  => $l->product?->name,
                'quantity'      => $l->quantity,
                'unit'          => $l->unit,
                'rate_paisas'   => $l->rate_paisas,
                'total_paisas'  => $l->total_paisas,
            ])),
        ];
    }
}
