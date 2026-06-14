<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('mtp_reviews', function (Blueprint $table) {
            $table->id();
            $table->string('stars')->nullable();
            $table->text('text')->nullable();
            $table->unsignedBigInteger('mtp_id');
            $table->foreign('mtp_id')->references('id')->on('mtps')->onDelete('cascade');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mtp_reviews');
    }
};
