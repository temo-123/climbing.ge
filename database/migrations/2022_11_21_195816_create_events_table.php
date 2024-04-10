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
        Schema::create('events', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('url_title')->nullable();

            $table->integer('published')->nullable();
            
            $table->string('category')->nullable();
            $table->text('map')->nullable();

            $table->timestamp('start_data')->nullable();
            $table->timestamp('end_data')->nullable();

            $table->string('image')->nullable();

            $table->unsignedBigInteger('us_event_id');
            $table->foreign('us_event_id')->references('id')->on('locale_events')->onDelete('cascade');

            $table->unsignedBigInteger('ka_event_id');
            $table->foreign('ka_event_id')->references('id')->on('locale_events')->onDelete('cascade');

            // $table->unsignedBigInteger('ru_event_id');
            // $table->foreign('ru_event_id')->references('id')->on('locale_events')->onDelete('cascade');

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
        Schema::dropIfExists('events');
    }
};
