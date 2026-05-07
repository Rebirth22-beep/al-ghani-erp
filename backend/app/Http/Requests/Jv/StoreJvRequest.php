<?php

namespace App\Http\Requests\Jv;

use Illuminate\Foundation\Http\FormRequest;

class StoreJvRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'date'      => ['required', 'date'],
            'narration' => ['required', 'string', 'max:1000'],
            'lines'     => ['required', 'array', 'min:2'],
            'lines.*.chart_of_account_id' => ['required', 'exists:chart_of_accounts,id'],
            'lines.*.debit_paisas'        => ['required', 'integer', 'min:0'],
            'lines.*.credit_paisas'       => ['required', 'integer', 'min:0'],
            'lines.*.description'         => ['nullable', 'string', 'max:255'],
        ];
    }
}
