<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->string('idOrder')->primary();
            
            // Foreign Keys
            $table->string('idUser');
            $table->string('idStore');
            $table->string('idAddress');
            
            // Rincian Harga
            $table->double('productTotalPrice');
            $table->double('shippingCost');
            $table->double('servicePrice');
            $table->double('grandTotal');
            
            // Status dan Info
            $table->enum('orderStatus', ['PENDING', 'PROCESSED', 'SHIPPED', 'DELIVERED', 'CANCELLED'])->default('PENDING');
            $table->string('invoiceNumber')->unique();
            
            // Tanggal
            $table->dateTime('orderDate');
            $table->dateTime('arrivedDate')->nullable();
            
            // Catatan dan Pembayaran
            $table->string('buyerNote')->nullable();
            // Pastikan kolom paymentId ada
            $table->string('paymentId')->nullable();

            // Tambahkan foreign key ini di bagian bawah definisi tabel
            $table->foreign('paymentId')->references('idPayment')->on('payments')->onDelete('set null');

            // Definisi Relasi
            $table->foreign('idUser')->references('idUser')->on('users')->onDelete('cascade');
            $table->foreign('idStore')->references('idStore')->on('stores')->onDelete('cascade');
            $table->foreign('idAddress')->references('idAddress')->on('user_addresses')->onDelete('cascade');

            // Custom Timestamps
            $table->dateTime('createAt')->nullable();
            $table->dateTime('updateAt')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};