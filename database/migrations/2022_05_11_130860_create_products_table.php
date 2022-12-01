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

            // $table->string('price')->nullable();
            // $table->string('currency')->nullable();
            $table->integer('discount')->nullable();
            
            // $table->string('material')->nullable();
            // $table->string('size')->nullable();
            // $table->string('weight')->nullable();

            $table->integer('mead_in_georgia')->nullable();
            $table->string('sale_type')->nullable();

            // $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('product_categories')->onDelete('cascade');

            // $table->integer('us_product_id')->nullable();
            // $table->integer('ru_product_id')->nullable();
            // $table->integer('ka_product_id')->nullable();

            // $table->foreignId('locale_product_id')->constrained()->onDelete('cascade');
            // $table->foreignId('locale_product_id')->constrained()->onDelete('cascade');
            // $table->foreignId('locale_product_id')->constrained()->onDelete('cascade');

            $table->unsignedBigInteger('us_product_id');
            $table->foreign('us_product_id')->references('id')->on('locale_products')->onDelete('cascade');

            $table->unsignedBigInteger('ka_product_id');
            $table->foreign('ka_product_id')->references('id')->on('locale_products')->onDelete('cascade');

            $table->unsignedBigInteger('ru_product_id');
            $table->foreign('ru_product_id')->references('id')->on('locale_products')->onDelete('cascade');

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
