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
            // Untuk payment manual — upload bukti transfer
            'proof_image'          => ['sometimes', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],

            // Untuk Midtrans — transaction ID dari gateway
            'transaction_id'       => ['sometimes', 'string'],
            'snap_token'           => ['sometimes', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'proof_image.image' => 'Bukti transfer harus berupa gambar.',
            'proof_image.max'   => 'Ukuran bukti transfer maksimal 2MB.',
        ];
    }
}