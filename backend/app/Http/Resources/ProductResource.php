<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                    => $this->id,
            'name'                  => $this->name,
            'category'              => $this->category,
            'unit'                  => $this->unit,
            'pack_size'             => $this->pack_size,
            'purchase_rate_paisas'  => $this->purchase_rate_paisas,
            'sale_rate_paisas'      => $this->sale_rate_paisas,
            'min_stock_quantity'    => $this->min_stock_quantity,
            'description'           => $this->description,
            'current_stock'         => $this->preloaded_stock ?? $this->currentStock(),
        ];
    }
}
