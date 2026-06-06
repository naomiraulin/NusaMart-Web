<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->string('idOrderItem')->primary();
            
            // Foreign Keys
            $table->string('idOrder');
            $table->string('idItem');
            
            // Snapshot data saat transaksi terjadi
            $table->string('nameSnapshot');
            $table->double('priceSnapshot');
            
            $table->integer('quantity')->default(1);

            // Relasi dengan cascade delete
            $table->foreign('idOrder')->references('idOrder')->on('orders')->onDelete('cascade');
            $table->foreign('idItem')->references('idItem')->on('product_items')->onDelete('cascade');

            // Custom Timestamps
            $table->dateTime('createAt')->nullable();
            $table->dateTime('updateAt')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};