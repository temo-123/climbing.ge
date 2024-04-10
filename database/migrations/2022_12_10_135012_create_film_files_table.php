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
        Schema::create('film_files', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('us_name')->nullable();
            $table->string('ka_name')->nullable();
            // $table->string('ru_name')->nullable();

            $table->text('us_description')->nullable();
            $table->text('ka_description')->nullable();
            // $table->text('ru_description')->nullable();

            $table->string('file')->nullable();

            $table->foreignId('film_id')->constrained()->onDelete('cascade');

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
        Schema::dropIfExists('film_files');
    }
};
