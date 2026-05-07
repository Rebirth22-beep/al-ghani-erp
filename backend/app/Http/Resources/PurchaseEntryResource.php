<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseEntryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'reference_number' => $this->reference_number,
            'date'             => $this->date?->toDateString(),
            'total_paisas'     => $this->total_paisas,
            'status'           => $this->status?->value,
            'supplier_name'    => $this->supplier?->party?->name,
            'lines'            => $this->whenLoaded('lines', fn() => $this->lines->map(fn($l) => [
                'id'           => $l->id,
                'product_id'   => $l->product_id,
                'product_name' => $l->product?->name,
                'quantity'     => $l->quantity,
                'unit'         => $l->unit,
                'rate_paisas'  => $l->rate_paisas,
                'total_paisas' => $l->total_paisas,
            ])),
        ];
    }
}
