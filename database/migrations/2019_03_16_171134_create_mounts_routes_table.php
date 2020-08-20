<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMountsRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mounts_routes', function (Blueprint $table) {
            $table->increments('id');
            
            $table->string('name')->nullable();
            $table->string('title')->nullable();
            $table->string('mounts')->nullable();
            $table->text('route_text')->nullable();
            $table->string('description_short')->nullable();
            $table->text('image')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_keyword')->nullable();
            $table->integer('published');
            
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
        Schema::dropIfExists('mounts_routes');
    }
}
