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
        Schema::create('locale_films', function (Blueprint $table) {
            $table->id();

            $table->string('locale')->nullable();
            $table->string('name')->nullable();
            $table->string('short_description')->nullable();

            $table->text('text')->nullable();

            $table->string('coutry')->nullable();
            $table->string('issue_year')->nullable();

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
        Schema::dropIfExists('locale_films');
    }
};
