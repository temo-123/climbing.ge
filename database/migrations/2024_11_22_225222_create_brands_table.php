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
        Schema::create('brands', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('us_brand_id');
            $table->foreign('us_brand_id')->references('id')->on('locale_brands')->constrained()->onDelete('cascade');

            $table->unsignedBigInteger('ka_brand_id');
            $table->foreign('ka_brand_id')->references('id')->on('locale_brands')->constrained()->onDelete('cascade');

            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brands');
    }
};
