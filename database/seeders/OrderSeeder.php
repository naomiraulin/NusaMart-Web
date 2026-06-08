<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Store;
use App\Models\UserAddress;
use App\Models\Order;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $buyers = User::where('role', 'BUYER')->get();
        $stores = Store::all();

        if ($buyers->count() > 0 && $stores->count() > 0) {
            foreach ($buyers as $buyer) {
                $address = UserAddress::where('idUser', $buyer->idUser)
                    ->where('isDefault', true)
                    ->first();

                if ($address) {
                    $maxStores = min(3, $stores->count());
                    $randomStores = $stores->random(rand(1, $maxStores));

                    foreach ($randomStores as $store) {
                        Order::factory()->create([
                            'idUser'     => $buyer->idUser,
                            'idStore'    => $store->idStore,
                            'idAddress'  => $address->idAddress,
                        ]);
                    }
                }
            }
        }
    }
}