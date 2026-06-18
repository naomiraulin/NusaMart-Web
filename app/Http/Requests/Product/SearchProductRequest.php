<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class SearchProductRequest extends FormRequest
{
    /**
     * Tentukan apakah user diizinkan membuat request ini.
     * Karena fitur pencarian bersifat publik (Guest & Buyer), atur menjadi true.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi untuk request pencarian.
     */
    public function rules(): array
    {
        return [
            // Nullable karena user bisa saja menekan tombol cari tanpa mengetik apa-apa
            // Max 255 untuk mencegah user iseng memasukkan teks ribuan karakter yang memberatkan server
            'search' => ['nullable', 'string', 'max:255'],
            
            // Nantinya kamu bisa menambahkan filter lain di sini jika diperlukan, contoh:
            // 'category_id' => ['nullable', 'string', 'exists:categories,idCategory'],
            // 'min_price'   => ['nullable', 'numeric', 'min:0'],
            // 'max_price'   => ['nullable', 'numeric', 'gte:min_price'],
        ];
    }

    /**
     * Pesan error kustom (opsional).
     */
    public function messages(): array
    {
        return [
            'search.string' => 'Format pencarian tidak valid.',
            'search.max'    => 'Kata kunci pencarian maksimal 255 karakter.',
        ];
    }
}