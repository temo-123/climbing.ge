<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sites', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('email')->nullable();
            $table->string('number')->nullable();

            $table->text('map')->nullable();
            $table->text('ad')->nullable();

            $table->unsignedBigInteger('us_info_id');
            $table->foreign('us_info_id')->references('id')->on('locale_sites')->onDelete('cascade');

            $table->unsignedBigInteger('ka_info_id');
            $table->foreign('ka_info_id')->references('id')->on('locale_sites')->onDelete('cascade');

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
        Schema::dropIfExists('sites');
    }
}
