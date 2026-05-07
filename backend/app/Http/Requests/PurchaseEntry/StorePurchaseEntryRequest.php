<?php

namespace App\Http\Requests\PurchaseEntry;

use Illuminate\Foundation\Http\FormRequest;

class StorePurchaseEntryRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'date'               => ['required', 'date'],
            'supplier_id'        => ['required', 'exists:suppliers,id'],
            'total_paisas'       => ['required', 'integer', 'min:0'],
            'lines'              => ['required', 'array', 'min:1'],
            'lines.*.product_id' => ['required', 'exists:products,id'],
            'lines.*.quantity'   => ['required', 'numeric', 'min:0.001'],
            'lines.*.unit'       => ['required', 'string'],
            'lines.*.rate_paisas'=> ['required', 'integer', 'min:0'],
            'lines.*.total_paisas'=> ['required', 'integer', 'min:0'],
        ];
    }
}
