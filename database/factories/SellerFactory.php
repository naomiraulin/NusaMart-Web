<?php

namespace Database\Factories;

use App\Models\Seller;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Seller>
 */
class SellerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // idSeller sengaja dikosongkan karena nanti akan diisi otomatis lewat Seeder
            'nik' => fake()->numerify('################'), // 16 digit NIK acak
            'bankName' => fake()->randomElement(['BCA', 'Mandiri', 'BNI', 'BRI', 'BSI']),
            'accountNumber' => fake()->bankAccountNumber(),
        ];
    }
}