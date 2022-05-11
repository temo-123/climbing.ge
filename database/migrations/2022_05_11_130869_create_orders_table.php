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
            $table->id();

            // $table->integer('user_id')->nullable();
            // $table->integer('adres_id')->nullable();
            
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // $table->foreignId('adres_id')->constrained();

            $table->unsignedBigInteger('adres_id');
            $table->foreign('adres_id')->references('id')->on('user_adreses')->onDelete('cascade'); // froom

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
