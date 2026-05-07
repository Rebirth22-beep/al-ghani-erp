<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductBatchResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                 => $this->id,
            'product_id'         => $this->product_id,
            'product_name'       => $this->product?->name,
            'batch_number'       => $this->batch_number,
            'quantity'           => $this->quantity,
            'qty_received'       => $this->qty_received,
            'qty_remaining'      => $this->qty_remaining,
            'cost_price_paisas'  => $this->cost_price_paisas,
            'received_date'      => $this->received_date?->toDateString(),
            'manufacture_date'   => $this->manufacture_date?->toDateString(),
            'expiry_date'        => $this->expiry_date?->toDateString(),
            'status'             => $this->status,
        ];
    }
}
