<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sport_route_reviews', function (Blueprint $table) {
            if (!Schema::hasColumn('sport_route_reviews', 'published'))
                $table->tinyInteger('published')->default(1)->after('stars');
            if (!Schema::hasColumn('sport_route_reviews', 'admin_hidden'))
                $table->tinyInteger('admin_hidden')->default(0)->after('published');
            if (!Schema::hasColumn('sport_route_reviews', 'hidden_reason'))
                $table->string('hidden_reason', 50)->nullable()->after('admin_hidden');
            if (!Schema::hasColumn('sport_route_reviews', 'hidden_reason_text'))
                $table->text('hidden_reason_text')->nullable()->after('hidden_reason');
        });

        Schema::table('mtp_reviews', function (Blueprint $table) {
            if (!Schema::hasColumn('mtp_reviews', 'published'))
                $table->tinyInteger('published')->default(1)->after('stars');
            if (!Schema::hasColumn('mtp_reviews', 'admin_hidden'))
                $table->tinyInteger('admin_hidden')->default(0)->after('published');
            if (!Schema::hasColumn('mtp_reviews', 'hidden_reason'))
                $table->string('hidden_reason', 50)->nullable()->after('admin_hidden');
            if (!Schema::hasColumn('mtp_reviews', 'hidden_reason_text'))
                $table->text('hidden_reason_text')->nullable()->after('hidden_reason');
        });

        Schema::table('article_comments', function (Blueprint $table) {
            if (!Schema::hasColumn('article_comments', 'admin_hidden'))
                $table->tinyInteger('admin_hidden')->default(0)->after('published');
            if (!Schema::hasColumn('article_comments', 'hidden_reason'))
                $table->string('hidden_reason', 50)->nullable()->after('admin_hidden');
            if (!Schema::hasColumn('article_comments', 'hidden_reason_text'))
                $table->text('hidden_reason_text')->nullable()->after('hidden_reason');
        });

        Schema::table('product_feedbacks', function (Blueprint $table) {
            if (!Schema::hasColumn('product_feedbacks', 'admin_hidden'))
                $table->tinyInteger('admin_hidden')->default(0)->after('published');
            if (!Schema::hasColumn('product_feedbacks', 'hidden_reason'))
                $table->string('hidden_reason', 50)->nullable()->after('admin_hidden');
            if (!Schema::hasColumn('product_feedbacks', 'hidden_reason_text'))
                $table->text('hidden_reason_text')->nullable()->after('hidden_reason');
        });
    }

    public function down(): void
    {
        Schema::table('sport_route_reviews', function (Blueprint $table) {
            $table->dropColumn(['published', 'admin_hidden', 'hidden_reason', 'hidden_reason_text']);
        });

        Schema::table('mtp_reviews', function (Blueprint $table) {
            $table->dropColumn(['published', 'admin_hidden', 'hidden_reason', 'hidden_reason_text']);
        });

        Schema::table('article_comments', function (Blueprint $table) {
            $table->dropColumn(['admin_hidden', 'hidden_reason', 'hidden_reason_text']);
        });

        Schema::table('product_feedbacks', function (Blueprint $table) {
            $table->dropColumn(['admin_hidden', 'hidden_reason', 'hidden_reason_text']);
        });
    }
};
