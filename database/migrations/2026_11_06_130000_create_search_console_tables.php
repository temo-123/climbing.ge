<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Google Search Console data, synced nightly by `php artisan app:sync-search-console`
 * (App\Services\Seo\SearchConsoleSync) for the admin SEO dashboard.
 *
 * search_console_pages   — one row per day per URL (clicks, impressions, CTR, position),
 *                          with the content record the URL belongs to.
 * search_console_queries — last 28 days, per URL + search query (replaced on every sync).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('search_console_pages', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->char('url_hash', 40);                 // sha1(url): long URLs can't be a unique key
            $table->text('url');
            $table->string('host', 100);
            $table->string('locale', 2)->default('en');   // en | ka (from the /ka prefix)
            $table->string('content_type', 30)->nullable(); // article | product | event | …
            $table->unsignedBigInteger('content_id')->nullable();
            $table->unsignedInteger('clicks')->default(0);
            $table->unsignedInteger('impressions')->default(0);
            $table->decimal('position', 6, 2)->default(0);
            $table->timestamps();

            $table->unique(['date', 'url_hash']);
            $table->index(['content_type', 'content_id']);
            $table->index('url_hash');
        });

        Schema::create('search_console_queries', function (Blueprint $table) {
            $table->id();
            $table->char('url_hash', 40);
            $table->string('query', 500);
            $table->unsignedInteger('clicks')->default(0);
            $table->unsignedInteger('impressions')->default(0);
            $table->decimal('position', 6, 2)->default(0);
            $table->timestamps();

            $table->index('url_hash');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('search_console_queries');
        Schema::dropIfExists('search_console_pages');
    }
};
