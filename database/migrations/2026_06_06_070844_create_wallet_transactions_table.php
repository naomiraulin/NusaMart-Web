<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->string('idTransaction')->primary();
            
            // Foreign Key ke tabel store_wallets
            $table->string('idWallet');
            
            // Tipe Mutasi dan Rincian
            $table->enum('mutationType', ['IN', 'OUT']);
            $table->double('nominal');
            $table->string('description')->nullable();
            
            // ID Referensi (Polymorphic: Tidak pakai constraint foreign key di database)
            $table->string('referenceId');

            // Relasi ke store_wallets
            $table->foreign('idWallet')->references('idWallet')->on('store_wallets')->onDelete('cascade');

            // Custom Timestamps
            $table->dateTime('createAt')->nullable();
            $table->dateTime('updateAt')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};