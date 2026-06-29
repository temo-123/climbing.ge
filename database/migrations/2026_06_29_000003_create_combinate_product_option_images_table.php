<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('combinate_product_option_images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('combination_id')->constrained('product_option_combinations')->onDelete('cascade');
            $table->string('image')->nullable();
            $table->string('title')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('combinate_product_option_images');
    }
};
