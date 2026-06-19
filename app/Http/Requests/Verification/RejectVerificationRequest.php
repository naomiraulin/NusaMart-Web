<?php

namespace App\Http\Requests\Verification;

use Illuminate\Foundation\Http\FormRequest;

class RejectVerificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Asumsi auth middleware admin sudah berjalan di rute
    }

    public function rules(): array
    {
        return [
            'notes' => ['required', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'notes.required' => 'Alasan penolakan wajib diisi.',
            'notes.max'      => 'Alasan penolakan maksimal 500 karakter.',
        ];
    }
}