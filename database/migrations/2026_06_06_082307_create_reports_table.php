<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->string('idReport')->primary();
            
            $table->string('reporterId');
            $table->enum('type', ['user', 'product', 'review', 'others']);
            $table->string('referenceId')->nullable();
            
            // Ubah menjadi string
            $table->string('reason');
            
            $table->enum('status', ['OPEN', 'REVIEWED', 'RESOLVED', 'DISMISSED'])->default('OPEN');
            
            // Ubah menjadi string
            $table->string('adminNote')->nullable();

            $table->foreign('reporterId')->references('idUser')->on('users')->onDelete('cascade');

            $table->dateTime('createAt')->nullable();
            $table->dateTime('updateAt')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};