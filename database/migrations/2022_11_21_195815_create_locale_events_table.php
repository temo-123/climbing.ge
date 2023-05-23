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
        Schema::create('locale_events', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('locale')->nullable();

            $table->string('title')->nullable();
            $table->string('short_description')->nullable();
            $table->text('text')->nullable();
            $table->text('info')->nullable();

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
        Schema::dropIfExists('locale_events');
    }
};
