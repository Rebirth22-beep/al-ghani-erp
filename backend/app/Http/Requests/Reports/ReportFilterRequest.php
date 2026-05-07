<?php

namespace App\Http\Requests\Reports;

use App\Enums\Season;
use Illuminate\Validation\Rule;

/**
 * Shared validation for the report listing endpoints.
 *
 * The same lightweight filter shape is accepted by sales, purchase, stock,
 * party, and worker reports. Individual services simply ignore filters that do
 * not apply to that report type.
 */
class ReportFilterRequest extends ReportDateRangeRequest
{
    public function rules(): array
    {
        return [
            ...parent::rules(),
            'season'     => ['nullable', Rule::in($this->seasonValues())],
            'product_id' => ['nullable', 'integer', 'exists:products,id'],
            'limit'      => ['nullable', 'integer', 'min:1', 'max:5000'],
        ];
    }

    private function seasonValues(): array
    {
        return array_map(
            fn (Season $season): string => $season->value,
            Season::cases()
        );
    }
}
