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
        Schema::create('services', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->string('url_title')->nullable();

            $table->integer('published')->nullable();

            // $table->integer('us_service_id')->nullable();
            // $table->integer('ru_service_id')->nullable();
            // $table->integer('ka_service_id')->nullable();
            
            // $table->foreignId('locale_service_id')->constrained()->onDelete('cascade');
            // $table->foreignId('locale_service_id')->constrained()->onDelete('cascade');
            // $table->foreignId('locale_service_id')->constrained()->onDelete('cascade');

            $table->unsignedBigInteger('us_service_id');
            $table->foreign('us_service_id')->references('id')->on('locale_services')->onDelete('cascade');

            $table->unsignedBigInteger('ka_service_id');
            $table->foreign('ka_service_id')->references('id')->on('locale_services')->onDelete('cascade');

            $table->unsignedBigInteger('ru_service_id');
            $table->foreign('ru_service_id')->references('id')->on('locale_services')->onDelete('cascade');

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
        Schema::dropIfExists('services');
    }
};
