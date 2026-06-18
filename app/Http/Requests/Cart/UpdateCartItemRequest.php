<?php

namespace App\Http\Requests\Cart;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCartItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'quantity' => ['required', 'integer', 'min:0', 'max:999'],
        ];
    }

    public function messages(): array
    {
        return [
            'quantity.required' => 'Jumlah wajib diisi.',
            'quantity.integer'  => 'Jumlah harus berupa angka.',
            'quantity.min'      => 'Jumlah tidak boleh kurang dari 0.',
            'quantity.max'      => 'Jumlah maksimal 999.',
        ];
    }
}