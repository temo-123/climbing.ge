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
        Schema::dropIfExists('ice_sector_images');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('ice_sector_images', function (Blueprint $table) {
            $table->id();

            $table->string('title')->nullable();
            $table->string('image')->nullable();
            
            $table->integer('num')->nullable();
            
            $table->foreignId('ice_sector_id')->constrained();

            $table->timestamps();
        });
    }
};
