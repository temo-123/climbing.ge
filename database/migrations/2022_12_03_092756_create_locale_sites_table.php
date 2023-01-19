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

            // $table->title();
            // $table->text(); 
            // $table->short_description();
            // $table->other_activity_description();
            // $table->ice_description(); 
             
            // $table->mount_description();
            // $table->outdoor_description();
            // $table->indoor_description();
            // $table->topo_description();
            // $table->what_we_do_description();
            // $table->shop_description();
            // $table->shop_short_description();
            // $table->services_description(); 

            $table->string('locale', 70, 20)->nullable();

            $table->string('guid_title', 70, 20)->nullable();
            $table->text('guid_description')->nullable();
            $table->string('guid_short_description')->nullable();

            $table->string('films_title', 70, 20)->nullable();
            $table->text('films_description')->nullable();
            $table->string('films_short_description')->nullable();

            $table->string('forum_title', 70, 20)->nullable();
            $table->text('forum_description')->nullable();
            $table->string('forum_short_description')->nullable();

            $table->string('shop_title', 70, 20)->nullable();
            $table->text('shop_description')->nullable();
            $table->string('shop_short_description')->nullable();

            $table->text('other_activity_description')->nullable();
            $table->text('mount_description')->nullable();
            $table->text('event_description')->nullable();
            $table->text('tech_tips_description')->nullable();
            $table->text('news_description')->nullable();
            $table->text('index_gallery_description')->nullable();
            $table->text('outdoor_description')->nullable();
            $table->text('indoor_description')->nullable();
            $table->text('ice_description')->nullable();
            $table->text('topo_description')->nullable();
            $table->text('what_we_do_description')->nullable();
            $table->text('products_description')->nullable();
            $table->text('services_description')->nullable();
            
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
