<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Superseded — does nothing. Per-day gym hours live in their own table
     * (2026_10_28_160000_create_article_working_hours_table.php), edited in
     * the article's "Working hours" tab. This file can be deleted.
     */
    public function up(): void
    {
    }

    public function down(): void
    {
    }
};
