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
        Schema::create('user_complaints', function (Blueprint $table) {
            $table->id();

            $table->text('comment')->nullable();
            $table->string('category')->nullable();
            $table->string('decision')->nullable();

            // $table->integer('user_id')->nullable();

            /*
            * $table->foreignId('complainted_user_id')->constrained()->onDelete('cascade'); 
            * Left code dose not workind! Bicous migration serching 'complainted_users' table
            * Use only right code
            */
            $table->unsignedBigInteger('complainted_user_id');
            $table->foreign('complainted_user_id')->references('id')->on('users')->onDelete('cascade'); // froom

            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // to
            
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
        Schema::dropIfExists('user_complaints');
    }
};
