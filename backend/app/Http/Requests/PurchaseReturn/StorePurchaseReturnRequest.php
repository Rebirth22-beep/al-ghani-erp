<?php

namespace App\Http\Requests\PurchaseReturn;

use Illuminate\Foundation\Http\FormRequest;

class StorePurchaseReturnRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'date'              => ['required', 'date'],
            'purchase_entry_id' => ['nullable', 'exists:purchase_entries,id'],
            'supplier_id'       => ['nullable', 'exists:suppliers,id'],
            'settlement_type'   => ['required', 'in:cash,credit'],
            'total_paisas'      => ['required', 'integer', 'min:0'],
            'reason'            => ['nullable', 'string', 'max:1000'],
            'lines'             => ['required', 'array', 'min:1'],
            'lines.*.product_id'        => ['required', 'exists:products,id'],
            'lines.*.original_line_id'  => ['nullable', 'exists:purchase_entry_lines,id'],
            'lines.*.batch_id'          => ['nullable', 'exists:product_batches,id'],
            'lines.*.quantity'          => ['required', 'numeric', 'min:0.001'],
            'lines.*.unit'              => ['required', 'string', 'max:50'],
            'lines.*.rate_paisas'       => ['required', 'integer', 'min:0'],
            'lines.*.total_paisas'      => ['required', 'integer', 'min:0'],
        ];
    }
}
