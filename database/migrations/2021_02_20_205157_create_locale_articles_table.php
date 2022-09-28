<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocaleArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locale_articles', function (Blueprint $table) {
            $table->id();

            $table->string('locale')->nullable();

            $table->string('title')->nullable();
            $table->string('short_description')->nullable();
            $table->text('text')->nullable();
            $table->text('route')->nullable();
            $table->text('how_get')->nullable();
            $table->text('best_time')->nullable();
            $table->text('what_need')->nullable();
            $table->text('info')->nullable();
            $table->text('price_text')->nullable();
            $table->string('address')->nullable();

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
        Schema::dropIfExists('locale_articles');
    }
}
