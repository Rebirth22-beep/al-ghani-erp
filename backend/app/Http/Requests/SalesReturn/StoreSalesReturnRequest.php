<?php

namespace App\Http\Requests\SalesReturn;

use Illuminate\Foundation\Http\FormRequest;

class StoreSalesReturnRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'date'             => ['required', 'date'],
            'sale_invoice_id'  => ['nullable', 'exists:sale_invoices,id'],
            'customer_id'      => ['nullable', 'exists:customers,id'],
            'settlement_type'  => ['required', 'in:cash,credit'],
            'total_paisas'     => ['required', 'integer', 'min:0'],
            'reason'           => ['nullable', 'string', 'max:1000'],
            'lines'            => ['required', 'array', 'min:1'],
            'lines.*.product_id'        => ['required', 'exists:products,id'],
            'lines.*.original_line_id'  => ['nullable', 'exists:sale_invoice_lines,id'],
            'lines.*.quantity'          => ['required', 'numeric', 'min:0.001'],
            'lines.*.unit'              => ['required', 'string', 'max:50'],
            'lines.*.rate_paisas'       => ['required', 'integer', 'min:0'],
            'lines.*.total_paisas'      => ['required', 'integer', 'min:0'],
        ];
    }
}
