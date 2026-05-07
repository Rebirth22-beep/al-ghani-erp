<?php

namespace App\Http\Requests\SaleInvoice;

use App\Enums\PaymentType;
use App\Enums\Season;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSaleInvoiceRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'date'             => ['required', 'date'],
            'customer_id'      => ['nullable', 'exists:customers,id'],
            'party_id'         => ['nullable', 'integer'],
            'payment_type'     => ['required', Rule::enum(PaymentType::class)],
            'season'           => ['required', Rule::enum(Season::class)],
            'bill_book_number' => ['nullable', 'string', 'max:50'],
            'total_paisas'     => ['required', 'integer', 'min:0'],
            'lines'            => ['required', 'array', 'min:1'],
            'lines.*.product_id'   => ['required', 'exists:products,id'],
            'lines.*.quantity'     => ['required', 'numeric', 'min:0.001'],
            'lines.*.unit'         => ['required', 'string'],
            'lines.*.rate_paisas'  => ['required', 'integer', 'min:0'],
            'lines.*.total_paisas' => ['required', 'integer', 'min:0'],
            'lines.*.batch_id'     => ['nullable', 'exists:product_batches,id'],
        ];
    }
}
