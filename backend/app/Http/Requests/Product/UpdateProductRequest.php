<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'                 => ['sometimes', 'string', 'max:255', Rule::unique('products', 'name')->ignore($this->product)],
            'category'             => ['nullable', 'string', 'max:100'],
            'unit'                 => ['sometimes', 'string', 'max:50'],
            'pack_size'            => ['nullable', 'numeric', 'min:0'],
            'purchase_rate_paisas' => ['nullable', 'integer', 'min:0'],
            'sale_rate_paisas'     => ['nullable', 'integer', 'min:0'],
            'min_stock_quantity'   => ['nullable', 'integer', 'min:0'],
            'description'          => ['nullable', 'string'],
        ];
    }
}
