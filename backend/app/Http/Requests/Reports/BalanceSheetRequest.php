<?php

namespace App\Http\Requests\Reports;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validation for the Balance Sheet endpoint.
 * Balance Sheet shows assets/liabilities/equity AT a single point in time
 * (not a date range), so we only accept ?as_of=.
 */
class BalanceSheetRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'as_of' => ['nullable', 'date'],
        ];
    }
}
