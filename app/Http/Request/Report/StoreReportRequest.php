<?php

namespace App\Http\Requests\Report;

use Illuminate\Foundation\Http\FormRequest;

class StoreReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type'         => ['required', 'in:user,product,review,others'],
            'reference_id' => ['nullable', 'string'],
            'reason'       => ['required', 'string', 'min:20', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'type.required'    => 'Tipe laporan wajib diisi.',
            'type.in'          => 'Tipe laporan tidak valid.',
            'reason.required'  => 'Alasan laporan wajib diisi.',
            'reason.min'       => 'Alasan laporan minimal 20 karakter.',
            'reason.max'       => 'Alasan laporan maksimal 1000 karakter.',
        ];
    }
}