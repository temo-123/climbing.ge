<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserAdresesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_adreses', function (Blueprint $table) {
            $table->id();

            $table->string('home_category')->nullable();

            $table->string('country')->nullable();
            $table->string('city')->nullable();
            
            $table->string('strit')->nullable();
            $table->string('number')->nullable();
            $table->string('floor')->nullable();
            $table->string('flat')->nullable();
            $table->string('entrance')->nullable();

            $table->string('zip_code')->nullable();

            // $table->integer('user_id')->nullable();
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
        Schema::dropIfExists('user_adreses');
    }
}
