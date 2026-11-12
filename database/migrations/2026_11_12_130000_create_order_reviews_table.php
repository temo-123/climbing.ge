<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Order (delivery / service) review left from the "order complete" email — admin only.
 * Product reviews from the same page go to product_feedbacks (shown on product pages).
 * orders.review_requested_at: the "order complete" mail was sent (sent once).
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('order_reviews')) {
            Schema::create('order_reviews', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('order_id')->unique();
                $table->unsignedBigInteger('user_id')->nullable()->index();
                $table->unsignedTinyInteger('stars');            // overall delivery / service, 1–5
                $table->unsignedTinyInteger('delivery_speed')->nullable();   // 1–5
                $table->unsignedTinyInteger('packaging')->nullable();        // 1–5
                $table->text('text')->nullable();
                $table->timestamps();

                $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete();
                $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            });
        }

        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'review_requested_at')) {
                $table->timestamp('review_requested_at')->nullable()->after('status_updating_data');
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_reviews');
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'review_requested_at')) $table->dropColumn('review_requested_at');
        });
    }
};
