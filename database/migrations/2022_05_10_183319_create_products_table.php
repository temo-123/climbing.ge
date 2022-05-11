<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            
            $table->string('url_title')->nullable();

            $table->integer('published')->nullable();

            $table->string('price')->nullable();
            $table->string('currency')->nullable();
            $table->string('discount')->nullable();
            
            $table->string('quantity')->nullable();
            $table->string('material')->nullable();
            $table->string('size')->nullable();
            $table->string('weight')->nullable();

            $table->integer('category_id')->nullable();
            $table->integer('user_id')->nullable();

            // $table->integer('us_product_id')->nullable();
            // $table->integer('ru_product_id')->nullable();
            // $table->integer('ka_product_id')->nullable();

            $table->foreignId('us_product_id')->constrained()->onDelete('cascade');
            $table->foreignId('ru_product_id')->constrained()->onDelete('cascade');
            $table->foreignId('ka_product_id')->constrained()->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
