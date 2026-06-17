<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_name'       => ['required', 'string', 'max:255'],
            'description'        => ['nullable', 'string'],
            'weight_gram'        => ['required', 'integer', 'min:1'],
            'product_status'     => ['nullable', 'in:ACTIVE,INACTIVE'],
            'sub_category_ids'   => ['required', 'array', 'min:1'],
            'sub_category_ids.*' => ['required', 'string', 'exists:sub_categories,idSubCategory'],
            'images'             => ['required', 'array', 'min:1', 'max:10'],
            'images.*'           => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'product_name.required'     => 'Nama produk wajib diisi.',
            'weight_gram.required'      => 'Berat produk wajib diisi.',
            'weight_gram.min'           => 'Berat produk minimal 1 gram.',
            'sub_category_ids.required' => 'Pilih minimal satu subkategori.',
            'sub_category_ids.*.exists' => 'Subkategori tidak valid.',
            'images.required'           => 'Upload minimal 1 foto produk.',
            'images.max'                => 'Maksimal 10 foto produk.',
            'images.*.image'            => 'File harus berupa gambar.',
            'images.*.max'              => 'Ukuran foto maksimal 2MB.',
        ];
    }
}