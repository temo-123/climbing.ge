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
        Schema::create('shiped_countries', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->string('country')->nullable();
            $table->string('shiping_price')->nullable();
            $table->string('free_shiping_price_after')->nullable();

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
        Schema::dropIfExists('shiped_countries');
    }
};
