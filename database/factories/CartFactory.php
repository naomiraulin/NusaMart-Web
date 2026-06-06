<?php

namespace Database\Factories;

use App\Models\Cart;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CartFactory extends Factory
{
    public function definition(): array
    {
        return [
            'idCart' => Str::uuid()->toString(),
            // idUser akan diisi melalui Seeder
        ];
    }
}