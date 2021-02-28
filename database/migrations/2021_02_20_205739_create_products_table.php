<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
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
            $table->string('url_title');

            $table->integer('published');

            $table->string('price');
            $table->astring('currency');
            $table->string('discount');
            $table->string('color');
            $table->string('quantity');
            $table->string('material');

            $table->integer('category_id');
            $table->integer('user_id');

            $table->integer('us_product_id');
            $table->integer('ru_product_id');
            $table->integer('ka_product_id');

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
}
