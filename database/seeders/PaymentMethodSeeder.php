<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        $methods = [
            ['id' => 'MET-000001', 'name' => 'Midtrans',     'provider' => 'MIDTRANS'],
            ['id' => 'MET-000002', 'name' => 'Transfer Bank', 'provider' => 'MANUAL'],
            ['id' => 'MET-000003', 'name' => 'COD',           'provider' => 'COD'],
        ];

        foreach ($methods as $method) {
            PaymentMethod::create([
                'idMethod'    => $method['id'],
                'methodName'  => $method['name'],
                'provider'    => $method['provider'],
                'isActive'    => true,
            ]);
        }
    }
}