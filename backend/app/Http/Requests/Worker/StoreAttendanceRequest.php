<?php

namespace App\Http\Requests\Worker;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttendanceRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'worker_id' => ['required', 'exists:workers,id'],
            'date'      => ['required', 'date'],
            'present'   => ['required', 'boolean'],
            'notes'     => ['nullable', 'string', 'max:500'],
        ];
    }
}
