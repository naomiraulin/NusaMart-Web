<?php

namespace App\Http\Requests\Review;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'order_item_id' => ['required', 'string', 'exists:order_items,idOrderItem'],
            'rating'        => ['required', 'numeric', 'min:1', 'max:5'],
            'comment'       => ['nullable', 'string', 'max:1000'],
            'images'        => ['nullable', 'array', 'max:5'],
            'images.*'      => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'order_item_id.required' => 'Item order wajib diisi.',
            'order_item_id.exists'   => 'Item order tidak ditemukan.',
            'rating.required'        => 'Rating wajib diisi.',
            'rating.min'             => 'Rating minimal 1.',
            'rating.max'             => 'Rating maksimal 5.',
            'comment.max'            => 'Komentar maksimal 1000 karakter.',
            'images.max'             => 'Maksimal 5 foto ulasan.',
            'images.*.image'         => 'File harus berupa gambar.',
            'images.*.max'           => 'Ukuran foto maksimal 2MB.',
        ];
    }
}