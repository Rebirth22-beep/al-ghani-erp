<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Compact invoice row for the customer ledger view.
 * Trimmed: only the fields the ledger page renders.
 */
class CustomerLedgerInvoiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'bill_number'  => $this->bill_number,
            'date'         => $this->date?->toDateString(),
            'total_paisas' => (int) $this->total_paisas,
            'status'       => $this->status?->value,
        ];
    }
}
