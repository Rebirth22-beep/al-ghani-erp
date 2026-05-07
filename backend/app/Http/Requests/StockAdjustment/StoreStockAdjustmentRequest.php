<?php

namespace App\Http\Requests\StockAdjustment;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreStockAdjustmentRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'product_id'      => ['required', 'exists:products,id'],
            'date'            => ['required', 'date'],
            'adjustment_type' => ['required', Rule::in(['increase', 'decrease'])],
            'quantity'        => ['required', 'numeric', 'min:0.001'],
            'reason'          => ['required', 'string', 'max:500'],
        ];
    }
}
