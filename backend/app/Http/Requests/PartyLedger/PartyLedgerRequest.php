<?php

namespace App\Http\Requests\PartyLedger;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validation for the Party Ledger statement endpoint.
 * Requires a party_id (the customer/supplier/partner being viewed) and
 * an optional date range.
 */
class PartyLedgerRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'party_id' => ['required', 'integer', 'exists:parties,id'],
            'from'     => ['nullable', 'date'],
            'to'       => ['nullable', 'date', 'after_or_equal:from'],
        ];
    }
}
