<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_name'       => ['sometimes', 'string', 'max:255'],
            'description'        => ['sometimes', 'nullable', 'string'],
            'weight_gram'        => ['sometimes', 'integer', 'min:1'],
            'product_status'     => ['sometimes', 'in:ACTIVE,INACTIVE'],
            'sub_category_ids'   => ['sometimes', 'array', 'min:1'],
            'sub_category_ids.*' => ['required_with:sub_category_ids', 'string', 'exists:sub_categories,idSubCategory'],
            'images'             => ['sometimes', 'array', 'max:10'],
            'images.*'           => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'weight_gram.min'           => 'Berat produk minimal 1 gram.',
            'product_status.in'         => 'Status produk tidak valid.',
            'sub_category_ids.*.exists' => 'Subkategori tidak valid.',
            'images.max'                => 'Maksimal 10 foto produk.',
            'images.*.image'            => 'File harus berupa gambar.',
            'images.*.max'              => 'Ukuran foto maksimal 2MB.',
        ];
    }
}