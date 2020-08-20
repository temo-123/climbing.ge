<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAboutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('abouts', function (Blueprint $table) {
            $table->increments('id');

            $table->string('title')->nullable();
            $table->text('text')->nullable();
            
            $table->string('short_description')->nullable();
            
            $table->text('image')->nullable();
            
            $table->string('fb_link')->nullable();
            $table->string('google_link')->nullable();
            $table->string('twit_link')->nullable();
            $table->string('inst_link')->nullable();
            
            $table->text('map')->nullable();

            $table->string('user_1')->nullable();
            $table->string('user_2')->nullable();
            $table->string('mail_1')->nullable();
            $table->string('mail_2')->nullable();
            $table->string('num_1')->nullable();
            $table->string('num_2')->nullable();

            $table->string('leng_1_1')->nullable();
            $table->string('leng_1_2')->nullable();
            $table->string('leng_2_1')->nullable();
            $table->string('leng_2_2')->nullable();
            $table->string('inst_1')->nullable();
            $table->string('inst_2')->nullable();
            $table->string('fb_1')->nullable();
            $table->string('fb_2')->nullable();

            $table->string('activity_title_1')->nullable();
            $table->string('activity_title_2')->nullable();
            $table->string('activity_title_3')->nullable();
            $table->string('activity_title_4')->nullable();
            
            $table->string('activity_text_1')->nullable();
            $table->string('activity_text_2')->nullable();
            $table->string('activity_text_3')->nullable();
            $table->string('activity_text_4')->nullable();
            
            $table->string('activity_img_1')->nullable();
            $table->string('activity_img_2')->nullable();
            $table->string('activity_img_3')->nullable();
            $table->string('activity_img_4')->nullable();
            
            $table->string('activity_link_1')->nullable();
            $table->string('activity_link_2')->nullable();
            $table->string('activity_link_3')->nullable();
            $table->string('activity_link_4')->nullable();

            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keyword')->nullable();

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
        Schema::dropIfExists('abouts');
    }
}
