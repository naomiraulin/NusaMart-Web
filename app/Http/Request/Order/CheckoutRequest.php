<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_store'      => ['required', 'string', 'exists:stores,idStore'],
            'id_address'    => ['required', 'string', 'exists:user_addresses,idAddress'],
            'id_courier'    => ['required', 'string', 'exists:courier_options,idCourier'],
            'id_method'     => ['required', 'string', 'exists:payment_methods,idMethod'],
            'shipping_cost' => ['required', 'numeric', 'min:0'],
            'service_price' => ['nullable', 'numeric', 'min:0'],
            'buyer_note'    => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'id_store.required'      => 'Toko tidak valid.',
            'id_store.exists'        => 'Toko tidak ditemukan.',
            'id_address.required'    => 'Pilih alamat pengiriman.',
            'id_address.exists'      => 'Alamat tidak ditemukan.',
            'id_courier.required'    => 'Pilih kurir pengiriman.',
            'id_courier.exists'      => 'Kurir tidak ditemukan.',
            'id_method.required'     => 'Pilih metode pembayaran.',
            'id_method.exists'       => 'Metode pembayaran tidak ditemukan.',
            'shipping_cost.required' => 'Ongkos kirim wajib diisi.',
            'buyer_note.max'         => 'Catatan maksimal 500 karakter.',
        ];
    }
}