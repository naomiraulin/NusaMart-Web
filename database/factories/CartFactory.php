<?php

namespace Database\Factories;

use App\Models\Cart;
use App\Services\IdGeneratorService;
use Illuminate\Database\Eloquent\Factories\Factory;

class CartFactory extends Factory
{
    public function definition(): array
    {
        return [
            'idCart' => app(IdGeneratorService::class)->generate('CRT', Cart::class, 'idCart'),
        ];
    }
}