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
        Schema::create('sale_codes', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('code')->nullable();
            
            $table->string('one_time_code')->nullable();

            $table->integer('discount')->nullable();

            $table->timestamp('action_data')->nullable();

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
        Schema::dropIfExists('sale_codes');
    }
};
