<?php

namespace App\Http\Requests\Supplier;

use Illuminate\Foundation\Http\FormRequest;

class StoreSupplierRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'                   => ['required', 'string', 'max:255'],
            'phone'                  => ['nullable', 'string', 'max:20'],
            'address'                => ['nullable', 'string'],
            'city'                   => ['nullable', 'string', 'max:100'],
            'opening_balance_paisas' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
