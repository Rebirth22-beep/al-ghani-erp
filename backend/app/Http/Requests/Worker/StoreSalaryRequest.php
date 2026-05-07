<?php

namespace App\Http\Requests\Worker;

use Illuminate\Foundation\Http\FormRequest;

class StoreSalaryRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'worker_id'     => ['required', 'exists:workers,id'],
            'month'         => ['required', 'string', 'regex:/^\d{4}-\d{2}$/'],
            'amount_paisas' => ['required', 'integer', 'min:0'],
            'notes'         => ['nullable', 'string', 'max:500'],
        ];
    }
}
