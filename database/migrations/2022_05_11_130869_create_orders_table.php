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

            $table->integer('treatment')->nullable();
            $table->timestamp('treatment_data')->nullable();
            $table->integer('preparation_for_shipment')->nullable();
            $table->timestamp('preparation_for_shipment_data')->nullable();
            $table->integer('ready_to_ship')->nullable();
            $table->timestamp('ready_to_ship_data')->nullable();
            $table->integer('order_has_been_sent')->nullable();
            $table->timestamp('order_has_been_sent_data')->nullable();
            $table->integer('transferred_to_the_delivery_service')->nullable();
            $table->timestamp('transferred_to_the_delivery_service_data')->nullable();
            $table->integer('delivered')->nullable();
            $table->timestamp('delivered_data')->nullable();

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
