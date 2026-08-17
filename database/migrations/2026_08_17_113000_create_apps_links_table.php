<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Admin-configurable external app links (App Store / Google Play / etc),
 * keyed by a short keyword the frontend looks up by (e.g. 'app_store',
 * 'google_play') rather than a numeric id, so a specific badge/link can be
 * fetched without knowing its row id ahead of time.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('apps_links', function (Blueprint $table) {
            $table->id();
            $table->string('keyword', 60)->unique();
            $table->text('link');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('apps_links');
    }
};
