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
        $table->bigIncrements('id');

        $table->unsignedBigInteger('route_id');
        $table->foreign('route_id')->references('id')->on('routes');

        $table->unsignedBigInteger('reiting_id');
        $table->foreign('reiting_id')->references('id')->on('sport_routes_reitings')->onDelete('cascade');
        
        $table->timestamps();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
