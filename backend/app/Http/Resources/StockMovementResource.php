<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Stock movement row used by the Stock Movement Report.
 */
class StockMovementResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'date'            => $this->created_at?->toDateTimeString(),
            'product_id'      => $this->product_id,
            'product_name'    => $this->product?->name,
            'movement_type'   => $this->movement_type?->value,
            'quantity_change' => (float) $this->quantity_change,
            'reference_type'  => $this->reference_type,
            'reference_id'    => $this->reference_id,
            'notes'           => $this->notes,
        ];
    }
}
