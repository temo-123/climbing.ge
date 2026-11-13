<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Seasonal campaigns: "the right content at the right time of year" e-mails
 * (ice in early winter, shady crags in summer, mountaineering mid-summer…).
 * docs/FUNCTIONS_DOCS/user/seasonal_campaigns.md
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seasonal_campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            $table->string('content_type', 20)->default('article');   // article | sector
            $table->string('category', 30);                            // articles.category (sector: its area's category)
            $table->string('sun_filter', 10)->default('any');          // sectors only: any | shade | sun
            $table->char('start_md', 5);                               // MM-DD, season window (may wrap the new year)
            $table->char('end_md', 5);
            $table->unsignedSmallInteger('every_days')->default(14);   // min days between two mails of this campaign to one user
            $table->unsignedTinyInteger('items_per_mail')->default(2);
            $table->string('pick', 10)->default('popular');            // popular | random | newest
            $table->string('audience', 12)->default('interested');     // interested | all | inactive
            $table->unsignedSmallInteger('inactive_days')->nullable(); // audience = inactive
            $table->unsignedInteger('max_per_run')->default(500);      // users per daily run (SMTP safety)
            $table->string('subject_us', 150);
            $table->string('subject_ka', 150)->nullable();
            $table->text('intro_us')->nullable();
            $table->text('intro_ka')->nullable();
            $table->boolean('enabled')->default(false);
            $table->timestamp('last_run_at')->nullable();
            $table->unsignedInteger('last_run_sent')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seasonal_campaigns');
    }
};
