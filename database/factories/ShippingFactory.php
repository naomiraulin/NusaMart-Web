<?php

namespace Database\Factories;

use App\Models\Shipping;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ShippingFactory extends Factory
{
    public function definition(): array
    {
        $status = fake()->randomElement(['WAITING', 'PICKED_UP', 'IN_TRANSIT', 'DELIVERED', 'FAILED']);
        $shippingDate = ($status !== 'WAITING') ? fake()->dateTimeBetween('-2 weeks', 'now') : null;
        $deliveredDate = ($status === 'DELIVERED') ? Carbon::instance($shippingDate)->addDays(rand(1, 4)) : null;
        $resi = ($status !== 'WAITING') ? strtoupper(fake()->bothify('AWB#########ID')) : null;

        return [
            'idShipping' => Str::uuid()->toString(),
            'resi' => $resi,
            'shippingPrice' => fake()->randomFloat(2, 10000, 50000),
            'shippingStatus' => $status,
            'shippingDate' => $shippingDate,
            'deliveredDate' => $deliveredDate,
        ];
    }
}