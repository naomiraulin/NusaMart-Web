<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('withdrawals', function (Blueprint $table) {
            $table->string('idWithdrawal')->primary();
            
            // Foreign Key ke tabel store_wallets
            $table->string('idWallet');
            
            $table->double('nominal');
            $table->double('serviceCost')->default(0.0);
            
            // Enum untuk status penarikan
            $table->enum('status', ['PENDING', 'PROCESSING', 'DONE', 'FAILED'])->default('PENDING');
            
            // Diubah ke String agar bisa menampung URL foto bukti transfer
            $table->string('transferPic')->nullable(); 

            // Relasi dengan cascade delete
            $table->foreign('idWallet')->references('idWallet')->on('store_wallets')->onDelete('cascade');

            // Custom Timestamps
            $table->dateTime('createAt')->nullable();
            $table->dateTime('updateAt')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('withdrawals');
    }
};