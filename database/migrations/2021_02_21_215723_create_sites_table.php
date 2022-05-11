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
            
            $table->string('guid_title', 70, 20)->nullable();
            $table->string('guid_title_ru', 70, 20)->nullable();
            $table->string('guid_title_ka', 70, 20)->nullable();
            
            $table->text('guid_description')->nullable();
            $table->text('guid_description_ru')->nullable();
            $table->text('guid_description_ka')->nullable();
            
            $table->string('guid_short_description')->nullable();
            $table->string('guid_short_description_ru')->nullable();
            $table->string('guid_short_description_ka')->nullable();


            
            $table->string('films_title', 70, 20)->nullable();
            $table->string('films_title_ru', 70, 20)->nullable();
            $table->string('films_title_ka', 70, 20)->nullable();

            $table->text('films_description')->nullable();
            $table->text('films_description_ru')->nullable();
            $table->text('films_description_ka')->nullable();

            $table->string('films_short_description')->nullable();
            $table->string('films_short_description_ru')->nullable();
            $table->string('films_short_description_ka')->nullable();



            $table->string('forum_title', 70, 20)->nullable();
            $table->string('forum_title_ru', 70, 20)->nullable();
            $table->string('forum_title_ka', 70, 20)->nullable();

            $table->text('forum_description')->nullable();
            $table->text('forum_description_ru')->nullable();
            $table->text('forum_description_ka')->nullable();

            $table->string('forum_short_description')->nullable();
            $table->string('forum_short_description_ru')->nullable();
            $table->string('forum_short_description_ka')->nullable();

            
            
            $table->string('shop_title', 70, 20)->nullable();
            $table->string('shop_title_ru', 70, 20)->nullable();
            $table->string('shop_title_ka', 70, 20)->nullable();

            $table->text('shop_description')->nullable();
            $table->text('shop_description_ru')->nullable();
            $table->text('shop_description_ka')->nullable();

            $table->string('shop_short_description')->nullable();
            $table->string('shop_short_description_ru')->nullable();
            $table->string('shop_short_description_ka')->nullable();



            
            $table->text('other_activity_description')->nullable();
            $table->text('other_activity_description_ru')->nullable();
            $table->text('other_activity_description_ka')->nullable();
            
            $table->text('mount_description')->nullable();
            $table->text('mount_description_ru')->nullable();
            $table->text('mount_description_ka')->nullable();
            
            $table->text('event_description')->nullable();
            $table->text('event_description_ru')->nullable();
            $table->text('event_description_ka')->nullable();
            
            $table->text('tech_tips_description')->nullable();
            $table->text('tech_tips_description_ru')->nullable();
            $table->text('tech_tips_description_ka')->nullable();
            
            $table->text('news_description')->nullable();
            $table->text('news_description_ru')->nullable();
            $table->text('news_description_ka')->nullable();
            
            $table->text('index_gallery_description')->nullable();
            $table->text('index_gallery_description_ru')->nullable();
            $table->text('index_gallery_description_ka')->nullable();
            
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

            $table->text('products_description')->nullable();
            $table->text('products_description_ru')->nullable();
            $table->text('products_description_ka')->nullable();

            $table->text('services_description')->nullable();
            $table->text('services_description_ru')->nullable();
            $table->text('services_description_ka')->nullable();

            $table->text('article_ad_block')->nullable();
            $table->text('footer_ad_block')->nullable();
            $table->text('pages_ad_block')->nullable();
            $table->text('forum_ad_block')->nullable();
            $table->text('ad_published')->nullable();

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
