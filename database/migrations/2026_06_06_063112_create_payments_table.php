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
        $table->string('idUser');
        $table->string('idMethod');
        $table->decimal('totalAmount', 15, 2);
        $table->string('transactionIdGateway')->nullable();
        $table->string('snapToken')->nullable();
        $table->enum('paymentStatus', ['PENDING', 'APPROVED', 'CANCELED'])->default('PENDING');
        $table->dateTime('paymentTime')->nullable();
        $table->string('imageURL')->nullable();
        $table->dateTime('createAt')->nullable();
        $table->dateTime('updateAt')->nullable();

        $table->foreign('idUser')->references('idUser')->on('users')->onDelete('cascade');
        $table->foreign('idMethod')->references('idMethod')->on('payment_methods')->onDelete('cascade');
    });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};