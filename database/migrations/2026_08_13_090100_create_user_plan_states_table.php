<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Training mobile app sync: per-user activation state for a training
     * plan. The plan's actual content stays in `training_plans` (served by
     * the public training API) -- this table only mirrors the app's local
     * activation fields (isActive, notification/calendar handles, etc).
     * `plan_id` matches the training API's plan id (see `training_plans.slug`).
     */
    public function up(): void
    {
        Schema::create('user_plan_states', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('plan_id', 64);

            $table->boolean('is_active')->default(0);
            $table->string('activated_at', 32)->nullable();
            $table->string('start_date', 32)->nullable();
            $table->boolean('notifications_enabled')->default(0);
            $table->string('notification_time', 5)->nullable();
            // Device-local Expo notification / calendar handles -- meaningless
            // across devices, stored/echoed verbatim.
            $table->json('notification_ids')->nullable();
            $table->boolean('calendar_enabled')->default(0);
            $table->json('calendar_event_ids')->nullable();

            $table->unique(['user_id', 'plan_id'], 'uniq_user_plan_state');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_plan_states');
    }
};
