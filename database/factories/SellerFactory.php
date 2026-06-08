<?php

namespace Database\Factories;

use App\Models\Seller;
use Illuminate\Database\Eloquent\Factories\Factory;

class SellerFactory extends Factory
{
    public function definition(): array
    {
        return [
            // idSeller diisi via Seeder karena sama dengan idUser
            'nik'           => fake()->numerify('################'),
            'bankName'      => fake()->randomElement(['BCA', 'Mandiri', 'BNI', 'BRI', 'BSI']),
            'accountNumber' => fake()->bankAccountNumber(),
            'ktpPhoto'      => null,
            'createAt'      => now(),
            'updateAt'      => now(),
        ];
    }
}