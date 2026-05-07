<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Compact bill row for the dashboard "Recent Bills" widget.
 */
class RecentBillResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'bill_number'  => $this->bill_number,
            'party_name'   => $this->customer?->party?->name,
            'date'         => $this->date?->toDateString(),
            'total_paisas' => (int) $this->total_paisas,
            'status'       => $this->status?->value,
        ];
    }
}
