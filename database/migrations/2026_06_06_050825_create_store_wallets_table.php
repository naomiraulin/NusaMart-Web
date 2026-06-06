<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('store_wallets', function (Blueprint $table) {
            $table->string('idWallet')->primary();
            
            // Foreign Key ke tabel stores (dibuat unique untuk relasi 1-to-1)
            $table->string('idStore')->unique();
            
            // Tipe data double untuk saldo
            $table->double('activeBalance')->default(0.0);
            $table->double('outstandingBalance')->default(0.0);

            // Relasi dengan cascade delete
            $table->foreign('idStore')->references('idStore')->on('stores')->onDelete('cascade');

            // Custom Timestamps
            $table->dateTime('createAt')->nullable();
            $table->dateTime('updateAt')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('store_wallets');
    }
};