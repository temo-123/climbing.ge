<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Indoor gyms whose opening hours differ by weekday: one row per day
     * (weekday 1 = Monday … 7 = Sunday), open / close time or closed.
     * No rows = the same hours every day (articles.open_time / closed_time).
     * Edited in the admin article's "Working hours" tab.
     */
    public function up(): void
    {
        Schema::create('article_working_hours', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('article_id');
            $table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');
            $table->unsignedTinyInteger('weekday');          // 1 = Monday … 7 = Sunday
            $table->time('opens_at')->nullable();
            $table->time('closes_at')->nullable();           // before opens_at = closes after midnight
            $table->boolean('is_closed')->default(false);
            $table->timestamps();

            $table->unique(['article_id', 'weekday']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('article_working_hours');
    }
};
