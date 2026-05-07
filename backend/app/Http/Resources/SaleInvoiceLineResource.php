<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleInvoiceLineResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'product_id'   => $this->product_id,
            'batch_id'     => $this->batch_id,
            'quantity'     => $this->quantity,
            'unit'         => $this->unit,
            'rate_paisas'  => $this->rate_paisas,
            'total_paisas' => $this->total_paisas,
            'product'      => $this->whenLoaded('product', fn() => [
                'id'   => $this->product->id,
                'name' => $this->product->name,
                'unit' => $this->product->unit,
            ]),
        ];
    }
}
