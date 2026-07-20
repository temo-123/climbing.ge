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
        Schema::create('content_notification_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->morphs('notifiable');
            $table->string('notification_key');
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->unique(
                ['user_id', 'notifiable_type', 'notifiable_id', 'notification_key'],
                'content_notif_log_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_notification_logs');
    }
};
