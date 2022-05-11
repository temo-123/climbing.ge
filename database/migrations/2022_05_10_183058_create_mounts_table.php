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
        Schema::create('mounts', function (Blueprint $table) {
            $table->id();
            
            $table->integer('published')->nullable();

            // $table->integer('us_mount_id')->nullable();
            // $table->integer('ru_mount_id')->nullable();
            // $table->integer('ka_mount_id')->nullable();

            $table->foreignId('us_mount_id')->constrained()->onDelete('cascade');
            $table->foreignId('ru_mount_id')->constrained()->onDelete('cascade');
            $table->foreignId('ka_mount_id')->constrained()->onDelete('cascade');

            $table->text('map')->nullable();
            $table->text('weather')->nullable();
            
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
        Schema::dropIfExists('mounts');
    }
};
