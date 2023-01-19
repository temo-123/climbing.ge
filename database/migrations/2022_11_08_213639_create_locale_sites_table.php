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
        Schema::create('locale_sites', function (Blueprint $table) {
            $table->id();
            
            $table->string('locale')->nullable();

            // films portsl descriptions
            $table->string('films_title')->nullable();
            $table->string('films_description')->nullable();
            $table->string('films_short_description')->nullable();

            // forum description
            $table->string('forum_title')->nullable();
            $table->string('forum_description')->nullable();
            $table->string('forum_short_description')->nullable();

            // shop descriptions
            $table->string('shop_title')->nullable();
            $table->string('shop_description')->nullable();
            $table->string('shop_short_description')->nullable();

            $table->string('products_description')->nullable();
            $table->string('services_description')->nullable();

            // guidbook descriptions
            $table->string('guid_title')->nullable();
            $table->string('guid_description')->nullable();
            $table->string('guid_short_description')->nullable();

            $table->string('other_activity_description')->nullable();
            $table->string('mount_description')->nullable();
            $table->string('event_description')->nullable();
            $table->string('tech_tips_description')->nullable();
            $table->string('news_description')->nullable();
            $table->string('index_gallery_description')->nullable();
            $table->string('outdoor_description')->nullable();
            $table->string('indoor_description')->nullable();
            $table->string('ice_description')->nullable();
            $table->string('topo_description')->nullable();
            $table->string('what_we_do_description')->nullable();
            $table->string('partner_description')->nullable();
            $table->string('message_description')->nullable();
            $table->string('other_description')->nullable();

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
        Schema::dropIfExists('locale_sites');
    }
};
