<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tracks, per admin and per alert type, when that admin last viewed the
 * alert — so "new since last visit" counts in the admin alerts widget can
 * reset instead of re-showing the same items forever.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_alert_views', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('alert_key');
            $table->timestamp('last_viewed_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'alert_key']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_alert_views');
    }
};
