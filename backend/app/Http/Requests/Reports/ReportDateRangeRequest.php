<?php

namespace App\Http\Requests\Reports;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Shared date-range validation for any report endpoint that takes ?from=&to=.
 * Used by ProfitLoss, CashFlow, GeneralLedger, TrialBalance, etc.
 */
class ReportDateRangeRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'from' => ['nullable', 'date'],
            'to'   => ['nullable', 'date', 'after_or_equal:from'],
        ];
    }
}
