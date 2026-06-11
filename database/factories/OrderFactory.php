<?php

namespace Database\Factories;

use App\Models\Order;
use App\Services\IdGeneratorService;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Carbon\Carbon;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        $productTotal = fake()->randomFloat(2, 50000, 1000000);
        $shipping     = fake()->randomFloat(2, 10000, 50000);
        $service      = 2500;
        $grandTotal    = $productTotal + $shipping + $service; 

        $status    = fake()->randomElement(['PENDING', 'PROCESSED', 'SHIPPED', 'DELIVERED', 'CANCELLED']);
        $orderDate = fake()->dateTimeBetween('-1 month', 'now');
        $arrivedDate = ($status === 'DELIVERED') ? Carbon::instance($orderDate)->addDays(rand(2, 5)) : null;

        return [
            'idOrder'           => app(IdGeneratorService::class)->generate('ORD', Order::class, 'idOrder'),
            'productTotalPrice' => $productTotal,
            'shippingCost'      => $shipping,
            'servicePrice'      => $service,
            'grandTotal'        => $grandTotal,
            'orderStatus'       => $status,
            'invoiceNumber'     => 'INV/' . date('Ymd') . '/' . strtoupper(Str::random(6)),
            'orderDate'         => $orderDate,
            'arrivedDate'       => $arrivedDate,
            'buyerNote'         => fake()->optional()->sentence(),
            'idPayment'         => null,
            'createAt'          => now(),
            'updateAt'          => now(),
        ];
    }
}