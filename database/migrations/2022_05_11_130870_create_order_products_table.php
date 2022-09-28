<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_products', function (Blueprint $table) {
            $table->id();

            // $table->integer('product_id')->nullable();
            $table->integer('quantity')->nullable();
            // $table->integer('order_id')->nullable();
            // $table->integer('color_id')->nullable();

            $table->foreignId('product_id')->constrained();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_option_id')->constrained();

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
        Schema::dropIfExists('order_products');
    }
}
