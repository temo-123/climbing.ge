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

            // $table->foreignId('us_mount_id')->constrained()->onDelete('cascade');
            // $table->foreignId('ru_mount_id')->constrained()->onDelete('cascade');
            // $table->foreignId('ka_mount_id')->constrained()->onDelete('cascade');

            $table->unsignedBigInteger('us_mount_id');
            $table->foreign('us_mount_id')->references('id')->on('locale_mounts')->onDelete('cascade');

            $table->unsignedBigInteger('ka_mount_id');
            $table->foreign('ka_mount_id')->references('id')->on('locale_mounts')->onDelete('cascade');

            $table->unsignedBigInteger('ru_mount_id');
            $table->foreign('ru_mount_id')->references('id')->on('locale_mounts')->onDelete('cascade');

            $table->text('map')->nullable();
            $table->text('weather')->nullable();
            $table->string('image')->nullable();
            
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
