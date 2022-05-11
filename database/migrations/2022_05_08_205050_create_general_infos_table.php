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
        Schema::create('general_infos', function (Blueprint $table) {
            $table->id();

            $table->string('title_us')->nullable();
            $table->string('title_ka')->nullable();
            $table->string('title_ru')->nullable();

            $table->text('text_ru')->nullable();
            $table->text('text_ka')->nullable();
            $table->text('text_us')->nullable();

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
        Schema::dropIfExists('general_infos');
    }
};
