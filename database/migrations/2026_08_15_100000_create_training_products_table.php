<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_products', function (Blueprint $table) {
            $table->unsignedBigInteger('training_id');
            $table->unsignedBigInteger('product_id');
            $table->primary(['training_id', 'product_id']);

            $table->foreign('training_id')->references('id')->on('trainings')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_products');
    }
};
