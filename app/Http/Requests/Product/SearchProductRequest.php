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
            'search'    => ['nullable', 'string', 'max:255'],
            
            'sort'      => ['nullable', 'string', 'in:semua,termurah,termahal'],
            
            'min_price' => ['nullable', 'numeric', 'min:0'],
            'max_price' => ['nullable', 'numeric', 'gte:min_price'],
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