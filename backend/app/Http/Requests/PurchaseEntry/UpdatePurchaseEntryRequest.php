<?php

namespace App\Http\Requests\PurchaseEntry;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePurchaseEntryRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'date'         => ['sometimes', 'date'],
            'total_paisas' => ['sometimes', 'integer', 'min:0'],
        ];
    }
}
