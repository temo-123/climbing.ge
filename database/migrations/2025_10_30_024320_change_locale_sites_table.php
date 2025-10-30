<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('locale_sites', function (Blueprint $table) {
            $table->dropColumn([
                'locale',
                'guid_title',
                'guid_description',
                'guid_short_description',
                'films_title',
                'films_description',
                'films_short_description',
                'forum_title',
                'forum_description',
                'forum_short_description',
                'shop_title',
                'shop_description',
                'shop_short_description',
                'other_activity_description',
                'mount_description',
                'event_description',
                'tech_tips_description',
                'news_description',
                'index_gallery_description',
                'outdoor_description',
                'indoor_description',
                'ice_description',
                'topo_description',
                'what_we_do_description',
                'products_description',
                'services_description',
                'terms_of_use'
            ]);

            $table->text('ka_data')->nullable()->after('id');
            $table->text('us_data')->nullable()->after('id');
            $table->string('slug')->nullable()->after('id');

            // $table->string('slug')->unique()->after('id');
            // $table->unique('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('locale_sites', function (Blueprint $table) {
            $table->dropColumn(['slug', 'us_data', 'ka_data']);

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
            $table->text('terms_of_use')->nullable();
        });
    }
};
