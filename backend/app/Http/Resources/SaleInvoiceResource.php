<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleInvoiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'bill_number'      => $this->bill_number,
            'date'             => $this->date?->toDateString(),
            'payment_type'     => $this->payment_type?->value,
            'season'           => $this->season?->value,
            'bill_book_number' => $this->bill_book_number,
            'total_paisas'     => $this->total_paisas,
            'status'           => $this->status?->value,
            'posted_at'        => $this->posted_at?->toDateTimeString(),
            'party_name'       => $this->customer?->party?->name,
            'customer'         => $this->whenLoaded('customer', fn() => [
                'id'   => $this->customer->id,
                'name' => $this->customer->party?->name,
            ]),
            'lines' => SaleInvoiceLineResource::collection($this->whenLoaded('lines')),
        ];
    }
}
