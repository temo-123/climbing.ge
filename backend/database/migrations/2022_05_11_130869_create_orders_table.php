<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('confirm')->nullable();
            $table->string('payment')->nullable();
            $table->string('discount')->nullable();
            $table->integer('shiping')->nullable();
            
            $table->foreignId('user_id')->constrained();

            $table->unsignedBigInteger('adres_id');
            $table->foreign('adres_id')->references('id')->on('user_adreses');

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
        Schema::dropIfExists('orders');
    }
}
