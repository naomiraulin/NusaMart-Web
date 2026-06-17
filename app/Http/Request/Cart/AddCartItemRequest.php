<?php

namespace App\Http\Requests\Cart;

use Illuminate\Foundation\Http\FormRequest;

class AddCartItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'item_id'  => ['required', 'string', 'exists:product_items,idItem'],
            'quantity' => ['required', 'integer', 'min:1', 'max:999'],
        ];
    }

    public function messages(): array
    {
        return [
            'item_id.required'  => 'Item produk wajib diisi.',
            'item_id.exists'    => 'Item produk tidak ditemukan.',
            'quantity.required' => 'Jumlah wajib diisi.',
            'quantity.integer'  => 'Jumlah harus berupa angka.',
            'quantity.min'      => 'Jumlah minimal 1.',
            'quantity.max'      => 'Jumlah maksimal 999.',
        ];
    }
}