<?php

namespace App\Http\Requests\SaleInvoice;

use App\Enums\PaymentType;
use App\Enums\Season;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSaleInvoiceRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'date'             => ['sometimes', 'date'],
            'payment_type'     => ['sometimes', Rule::enum(PaymentType::class)],
            'season'           => ['sometimes', Rule::enum(Season::class)],
            'bill_book_number' => ['nullable', 'string', 'max:50'],
            'total_paisas'     => ['sometimes', 'integer', 'min:0'],
            'lines'            => ['sometimes', 'array', 'min:1'],
            'lines.*.product_id'   => ['required_with:lines', 'exists:products,id'],
            'lines.*.quantity'     => ['required_with:lines', 'numeric', 'min:0.001'],
            'lines.*.unit'         => ['required_with:lines', 'string'],
            'lines.*.rate_paisas'  => ['required_with:lines', 'integer', 'min:0'],
            'lines.*.total_paisas' => ['required_with:lines', 'integer', 'min:0'],
        ];
    }
}
