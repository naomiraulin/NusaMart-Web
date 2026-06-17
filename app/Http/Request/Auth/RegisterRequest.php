<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $base = [
            'username' => ['required', 'string', 'max:100'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'phone'    => ['required', 'string', 'max:20'],
            'role'     => ['required', 'in:BUYER,SELLER'],
        ];

        // Tambahan validasi kalau daftar sebagai SELLER
        if ($this->input('role') === 'SELLER') {
            $base = array_merge($base, [
                'nik'            => ['required', 'string', 'size:16'],
                'bank_name'      => ['required', 'string', 'max:100'],
                'account_number' => ['required', 'string', 'max:50'],
                'store_name'     => ['required', 'string', 'max:150'],
                'location'       => ['required', 'string', 'max:255'],
            ]);
        }

        return $base;
    }

    public function messages(): array
    {
        return [
            'username.required'      => 'Username wajib diisi.',
            'email.required'         => 'Email wajib diisi.',
            'email.unique'           => 'Email sudah digunakan.',
            'password.required'      => 'Password wajib diisi.',
            'password.confirmed'     => 'Konfirmasi password tidak cocok.',
            'phone.required'         => 'Nomor HP wajib diisi.',
            'role.in'                => 'Role tidak valid.',
            'nik.required'           => 'NIK wajib diisi.',
            'nik.size'               => 'NIK harus 16 digit.',
            'bank_name.required'     => 'Nama bank wajib diisi.',
            'account_number.required'=> 'Nomor rekening wajib diisi.',
            'store_name.required'    => 'Nama toko wajib diisi.',
            'location.required'      => 'Lokasi toko wajib diisi.',
        ];
    }
}