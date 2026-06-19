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
            // Item yang dipilih dari cart
            'cart_item_ids'                => ['required', 'array', 'min:1'],
            'cart_item_ids.*'              => ['required', 'string', 'exists:cart_items,idCartItem'],

            // Alamat & metode pembayaran (global, satu untuk semua order)
            'id_address'                   => ['required', 'string', 'exists:user_addresses,idAddress'],
            'id_method'                    => ['required', 'string', 'exists:payment_methods,idMethod'],
            'service_price'                => ['nullable', 'numeric', 'min:0'],

            // Per-store data (array keyed by idStore)
            'stores'                       => ['required', 'array', 'min:1'],
            'stores.*.id_store'            => ['required', 'string', 'exists:stores,idStore'],
            'stores.*.id_courier'          => ['required', 'string', 'exists:courier_options,idCourier'],
            'stores.*.shipping_cost'       => ['required', 'numeric', 'min:0'],
            'stores.*.buyer_note'          => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'cart_item_ids.required'       => 'Pilih minimal satu produk.',
            'cart_item_ids.*.exists'       => 'Item keranjang tidak valid.',
            'id_address.required'          => 'Pilih alamat pengiriman.',
            'id_address.exists'            => 'Alamat tidak ditemukan.',
            'id_method.required'           => 'Pilih metode pembayaran.',
            'id_method.exists'             => 'Metode pembayaran tidak ditemukan.',
            'stores.required'              => 'Data toko tidak valid.',
            'stores.*.id_store.required'   => 'Data toko tidak valid.',
            'stores.*.id_store.exists'     => 'Toko tidak ditemukan.',
            'stores.*.id_courier.required' => 'Pilih kurir untuk setiap toko.',
            'stores.*.id_courier.exists'   => 'Kurir tidak ditemukan.',
            'stores.*.shipping_cost.required' => 'Ongkos kirim wajib diisi.',
            'stores.*.buyer_note.max'      => 'Catatan maksimal 500 karakter.',
        ];
    }
}