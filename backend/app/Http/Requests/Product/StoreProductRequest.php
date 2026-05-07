<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'                  => ['required', 'string', 'max:255', 'unique:products,name'],
            'category'              => ['nullable', 'string', 'max:100'],
            'unit'                  => ['required', 'string', 'max:50'],
            'pack_size'             => ['nullable', 'numeric', 'min:0'],
            'purchase_rate_paisas'  => ['nullable', 'integer', 'min:0'],
            'sale_rate_paisas'      => ['nullable', 'integer', 'min:0'],
            'min_stock_quantity'    => ['nullable', 'integer', 'min:0'],
            'description'           => ['nullable', 'string'],
        ];
    }
}
