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
            $table->id();
            
            $table->string('url_title')->nullable();

            $table->integer('published')->nullable();

            // $table->integer('us_service_id')->nullable();
            // $table->integer('ru_service_id')->nullable();
            // $table->integer('ka_service_id')->nullable();
            
            $table->foreignId('us_service_id')->constrained()->onDelete('cascade');
            $table->foreignId('ru_service_id')->constrained()->onDelete('cascade');
            $table->foreignId('ka_service_id')->constrained()->onDelete('cascade');

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
