<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StockAlertResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'product_id'    => $this->product_id,
            'product_name'  => $this->product?->name,
            'current_stock' => $this->current_stock,
            'min_stock'     => $this->min_stock,
            'alerted_at'    => $this->alerted_at?->toDateTimeString(),
        ];
    }
}
