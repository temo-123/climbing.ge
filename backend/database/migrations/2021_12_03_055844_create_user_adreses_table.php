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
            
            $table->bigIncrements('id');

            $table->string('name')->nullable();

            $table->string('city')->nullable();
            
            $table->string('strit')->nullable();
            $table->string('number')->nullable();
            $table->string('floor')->nullable();
            $table->string('flat')->nullable();
            $table->string('entrance')->nullable();

            $table->string('zip_code')->nullable();

            $table->string('country_id')->constrained();
            $table->foreignId('user_id')->constrained();

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
