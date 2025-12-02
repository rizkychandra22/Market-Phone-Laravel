<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('brand_id')->constrained()->onDelete('cascade');
            $table->string('name'); 
            $table->text('description')->nullable();

            // Spesifikasi umum (tidak berubah per varian)
            $table->string('chipset');
            $table->string('software');
            $table->string('display');
            $table->string('dimensi');
            $table->string('camera');
            $table->string('baterai');
            $table->string('network'); 
            $table->string('konektivitas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
