<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseReturnResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'return_number'     => $this->return_number,
            'date'              => $this->date?->toDateString(),
            'fiscal_year'       => $this->fiscal_year,
            'purchase_entry_id' => $this->purchase_entry_id,
            'supplier_id'       => $this->supplier_id,
            'settlement_type'   => $this->settlement_type,
            'total_paisas'      => $this->total_paisas,
            'reason'            => $this->reason,
            'party_name'        => $this->supplier?->party?->name,
            'lines'             => $this->whenLoaded('lines', fn() => $this->lines->map(fn($l) => [
                'id'            => $l->id,
                'product_id'    => $l->product_id,
                'product_name'  => $l->product?->name,
                'batch_id'      => $l->batch_id,
                'quantity'      => $l->quantity,
                'unit'          => $l->unit,
                'rate_paisas'   => $l->rate_paisas,
                'total_paisas'  => $l->total_paisas,
            ])),
        ];
    }
}
