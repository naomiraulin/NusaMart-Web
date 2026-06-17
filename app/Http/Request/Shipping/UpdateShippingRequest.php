<?php

namespace App\Http\Requests\Shipping;

use Illuminate\Foundation\Http\FormRequest;

class UpdateShippingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status'      => ['required', 'in:WAITING,PICKED_UP,IN_TRANSIT,DELIVERED,FAILED'],
            'location'    => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'Status pengiriman wajib diisi.',
            'status.in'       => 'Status pengiriman tidak valid. Pilihan: WAITING, PICKED_UP, IN_TRANSIT, DELIVERED, FAILED.',
            'location.max'    => 'Lokasi maksimal 255 karakter.',
            'description.max' => 'Deskripsi maksimal 500 karakter.',
        ];
    }
}