<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('review_images', function (Blueprint $table) {
            $table->string('idRevImage')->primary();
            
            // Foreign Key ke tabel reviews
            $table->string('idReview');
            
            $table->string('urlImage');

            // Relasi dengan cascade delete
            $table->foreign('idReview')->references('idReview')->on('reviews')->onDelete('cascade');

            // Custom Timestamps
            $table->dateTime('createAt')->nullable();
            $table->dateTime('updateAt')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('review_images');
    }
};