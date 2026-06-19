<?php

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;

class ConfirmPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'proof_image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'proof_image.required' => 'Bukti transfer wajib diupload.',
            'proof_image.image'    => 'Bukti transfer harus berupa gambar.',
            'proof_image.max'      => 'Ukuran bukti transfer maksimal 2MB.',
        ];
    }
}