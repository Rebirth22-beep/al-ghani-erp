<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Compact purchase-entry row for the supplier ledger view.
 */
class SupplierLedgerEntryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'reference_number' => $this->reference_number,
            'date'             => $this->date?->toDateString(),
            'total_paisas'     => (int) $this->total_paisas,
            'status'           => $this->status?->value,
        ];
    }
}
