<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->string('idPayment')->primary();
            $table->string('idMethod');
            
            // Kolom untuk Midtrans (Nullable karena kalau COD/Manual tidak pakai ini)
            $table->string('transactionIdGateway')->nullable();
            $table->string('snapToken')->nullable();
            
            // Status sesuai gambar
            $table->enum('paymentStatus', ['PENDING', 'APPROVED', 'CANCELED'])->default('PENDING');
            
            // Waktu pembayaran
            $table->dateTime('paymentTime')->nullable();
            
            // Tambahan foto bukti transfer untuk manual
            $table->string('imageURL')->nullable(); 

            // Relasi ke metode pembayaran
            $table->foreign('idMethod')->references('idMethod')->on('payment_methods')->onDelete('cascade');

            $table->dateTime('createAt')->nullable();
            $table->dateTime('updateAt')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};