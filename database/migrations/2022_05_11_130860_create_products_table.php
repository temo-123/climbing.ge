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
            $table->bigIncrements('id');
            
            $table->string('url_title')->nullable();

            $table->integer('published')->nullable();

            $table->integer('discount')->nullable();
            
            $table->string('material')->nullable();
            
            $table->integer('mead_in_georgia')->nullable();
            $table->string('sale_type')->nullable();

            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('product_categories');

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
