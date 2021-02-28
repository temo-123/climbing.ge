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
            
            $table->string('title');
            $table->string('title_ru');
            $table->string('title_ka');
            
            $table->text('text');
            $table->text('text_ru');
            $table->text('text_ka');
            
            $table->string('short_description');
            $table->string('short_description_ru');
            $table->string('short_description_ka');
            
            $table->text('other_activity_description');
            $table->text('other_activity_description_ru');
            $table->text('other_activity_description_ka');
            
            $table->text('mount_description');
            $table->text('mount_description_ru');
            $table->text('mount_description_ka');
            
            $table->text('outdoor_description');
            $table->text('outdoor_description_ru');
            $table->text('outdoor_description_ka');
            
            $table->text('indoor_description');
            $table->text('indoor_description_ru');
            $table->text('indoor_description_ka');
            
            $table->text('topo_description');
            $table->text('topo_description_ru');
            $table->text('topo_description_ka');
            
            $table->text('what_we_do_description');
            $table->text('what_we_do_description_ru');
            $table->text('what_we_do_description_ka');
            
            $table->text('shop_description');
            $table->text('shop_description_ru');
            $table->text('shop_description_ka');
            
            $table->text('meta_description');
            $table->text('meta_description_ru');
            $table->text('meta_description_ka');

            $table->string('fb_link');
            $table->string('inst_link');
            $table->string('google_link');
            $table->string('twit_link');

            $table->text('email');
            $table->text('map');

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
