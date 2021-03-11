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
            $table->id();
            
            $table->string('title')->nullable();
            $table->string('title_ru')->nullable();
            $table->string('title_ka')->nullable();
            
            $table->text('text')->nullable();
            $table->text('text_ru')->nullable();
            $table->text('text_ka')->nullable();
            
            $table->string('short_description')->nullable();
            $table->string('short_description_ru')->nullable();
            $table->string('short_description_ka')->nullable();
            
            $table->text('other_activity_description')->nullable();
            $table->text('other_activity_description_ru')->nullable();
            $table->text('other_activity_description_ka')->nullable();
            
            $table->text('mount_description')->nullable();
            $table->text('mount_description_ru')->nullable();
            $table->text('mount_description_ka')->nullable();
            
            $table->text('outdoor_description')->nullable();
            $table->text('outdoor_description_ru')->nullable();
            $table->text('outdoor_description_ka')->nullable();
            
            $table->text('indoor_description')->nullable();
            $table->text('indoor_description_ru')->nullable();
            $table->text('indoor_description_ka')->nullable();
            
            $table->text('ice_description')->nullable();
            $table->text('ice_description_ru')->nullable();
            $table->text('ice_description_ka')->nullable();
            
            $table->text('topo_description')->nullable();
            $table->text('topo_description_ru')->nullable();
            $table->text('topo_description_ka')->nullable();
            
            $table->text('what_we_do_description')->nullable();
            $table->text('what_we_do_description_ru')->nullable();
            $table->text('what_we_do_description_ka')->nullable();
            
            $table->text('shop_description')->nullable();
            $table->text('shop_description_ru')->nullable();
            $table->text('shop_description_ka')->nullable();

            $table->string('fb_link')->nullable();
            $table->string('inst_link')->nullable();
            $table->string('google_link')->nullable();
            $table->string('twit_link')->nullable();

            $table->string('email')->nullable();
            $table->string('number')->nullable();
            $table->text('map')->nullable();

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
