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
        Schema::create('user_notifications', function (Blueprint $table) {
            $table->id();

            $table->integer('favorite_outdoor')->nullable();
            $table->integer('favorite_product')->nullable();
            $table->integer('favorite_film')->nullable();
            $table->integer('interested_event')->nullable();

            $table->integer('add_new_sector')->nullable();
            $table->integer('add_new_outdoor_spot')->nullable();
            $table->integer('add_new_ice_spot')->nullable();
            $table->integer('add_new_techtip')->nullable();
            $table->integer('add_new_gym')->nullable();
            $table->integer('add_new_product')->nullable();
            $table->integer('add_new_service')->nullable();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');

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
        Schema::dropIfExists('user_notifications');
    }
};
