<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Renamed slugs → 301 redirects (App\Http\Middleware\RedirectRenamedSlugs).
     * One row per old URL: /{segment}/{old_slug} (and /ka/…) → /{segment}/{new_slug}.
     * Rows are added automatically when a content slug changes (RecordsSlugRedirects).
     */
    public function up(): void
    {
        Schema::create('url_redirects', function (Blueprint $table) {
            $table->id();
            $table->string('segment', 40);                              // outdoor, product, event, news…
            $table->string('old_slug', 255)->collation('utf8mb4_bin');  // exact: case, symbols, non-Latin letters
            $table->string('new_slug', 255)->collation('utf8mb4_bin');
            $table->timestamps();
            $table->unique(['segment', 'old_slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('url_redirects');
    }
};
