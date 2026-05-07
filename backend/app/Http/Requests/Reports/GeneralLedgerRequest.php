<?php

namespace App\Http\Requests\Reports;

/**
 * Validation for General Ledger filters.
 *
 * General Ledger supports the shared report date range plus optional account
 * and pagination controls.
 */
class GeneralLedgerRequest extends ReportDateRangeRequest
{
    public function rules(): array
    {
        return [
            ...parent::rules(),
            'account_id' => ['nullable', 'integer', 'exists:chart_of_accounts,id'],
            'per_page'   => ['nullable', 'integer', 'min:1', 'max:200'],
        ];
    }
}
