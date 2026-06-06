<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\CourierOption;
use App\Models\Shipping;
use Illuminate\Database\Seeder;

class ShippingSeeder extends Seeder
{
    public function run(): void
    {
        $orders = Order::all();
        $couriers = CourierOption::all();

        if ($orders->count() > 0 && $couriers->count() > 0) {
            foreach ($orders as $order) {
                $randomCourier = $couriers->random();

                $simulatedStatus = match($order->orderStatus) {
                    'PENDING', 'PROCESSED' => 'WAITING',
                    'SHIPPED' => 'IN_TRANSIT',
                    'DELIVERED' => 'DELIVERED',
                    'CANCELLED' => 'FAILED',
                    default => 'WAITING'
                };

                Shipping::factory()->create([
                    'idOrder' => $order->idOrder,
                    'idCourier' => $randomCourier->idCourier,
                    'shippingPrice' => $order->shippingCost, 
                    'shippingStatus' => $simulatedStatus,
                ]);
            }
        }
    }
}