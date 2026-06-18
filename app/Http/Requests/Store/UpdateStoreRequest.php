<?php

namespace App\Http\Requests\Store;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'         => ['sometimes', 'string', 'max:150'],
            'description'  => ['sometimes', 'nullable', 'string'],
            'location'     => ['sometimes', 'string', 'max:255'],
            'url_location' => ['sometimes', 'nullable', 'url'],
            'logo'         => ['sometimes', 'image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.max'        => 'Nama toko maksimal 150 karakter.',
            'url_location.url'=> 'Format URL lokasi tidak valid.',
            'logo.image'      => 'Logo harus berupa gambar.',
            'logo.max'        => 'Ukuran logo maksimal 8MB.',
        ];
    }
}