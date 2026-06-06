<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shippings', function (Blueprint $table) {
            $table->string('idShipping')->primary();
            
            $table->string('idOrder');
            $table->string('idCourier');
            $table->string('resi')->nullable();
            $table->double('shippingPrice');
            $table->enum('shippingStatus', ['WAITING', 'PICKED_UP', 'IN_TRANSIT', 'DELIVERED', 'FAILED'])->default('WAITING');
            $table->dateTime('shippingDate')->nullable();
            $table->dateTime('deliveredDate')->nullable();

            $table->foreign('idOrder')->references('idOrder')->on('orders')->onDelete('cascade');
            $table->foreign('idCourier')->references('idCourier')->on('courier_options')->onDelete('cascade');

            $table->dateTime('createAt')->nullable();
            $table->dateTime('updateAt')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shippings');
    }
};