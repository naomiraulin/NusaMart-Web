<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->string('idCartItem')->primary();
            
            // Foreign Keys
            $table->string('idCart');
            $table->string('idItem');
            
            $table->integer('quantity')->default(1);

            // Mendefinisikan relasi
            $table->foreign('idCart')->references('idCart')->on('carts')->onDelete('cascade');
            $table->foreign('idItem')->references('idItem')->on('product_items')->onDelete('cascade');

            // Custom Timestamps
            $table->dateTime('createAt')->nullable();
            $table->dateTime('updateAt')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};