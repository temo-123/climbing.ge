<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_option_combination_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('combination_id')->constrained('product_option_combinations')->onDelete('cascade');
            $table->foreignId('product_option_id')->constrained('product_options')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_option_combination_items');
    }
};
