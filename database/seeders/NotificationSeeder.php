<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Order;
use App\Models\Notification;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $counter = 1;

        // 1. Notifikasi Sistem (Welcome Message) untuk semua user
        $users = User::all();
        foreach ($users as $user) {
            Notification::factory()->create([
                'idNotif'       => 'NTF-' . str_pad($counter++, 6, '0', STR_PAD_LEFT),
                'idUser'        => $user->idUser,
                'title'         => 'Selamat Datang!',
                'body'          => 'Terima kasih telah bergabung di platform UMKM kami.',
                'type'          => 'SISTEM',
                'referenceId'   => null,
                'referenceType' => 'SYSTEM',
            ]);
        }

        // 2. Notifikasi Order untuk pembeli
        $orders = Order::all();
        foreach ($orders as $order) {
            $statusText = match($order->orderStatus) {
                'PENDING'    => 'Pesanan Anda sedang menunggu pembayaran.',
                'PROCESSED'  => 'Pesanan Anda sedang diproses oleh penjual.',
                'SHIPPED'    => 'Pesanan Anda sedang dalam perjalanan.',
                'DELIVERED'  => 'Pesanan Anda telah sampai tujuan.',
                'CANCELLED'  => 'Pesanan Anda telah dibatalkan.',
                default      => 'Ada pembaruan pada pesanan Anda.'
            };

            Notification::factory()->create([
                'idNotif'       => 'NTF-' . str_pad($counter++, 6, '0', STR_PAD_LEFT),
                'idUser'        => $order->idUser,
                'title'         => 'Update Pesanan ' . $order->invoiceNumber,
                'body'          => $statusText,
                'type'          => 'ORDER',
                'referenceId'   => $order->idOrder,
                'referenceType' => 'ORDER',
                'isRead'        => false,
            ]);
        }
    }
}