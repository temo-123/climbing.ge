<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * A GPS track proving a summit ascent: a GPX file the climber uploaded or
     * a Strava activity they imported. One per ascent. `verified` = the track
     * passes within SummitTrackService::VERIFY_RADIUS_M of the summit.
     * `points` (simplified [[lat, lng, ele], …]) is kept for GPX only — Strava
     * data may only be shown to its owner, so only the derived numbers are
     * stored for Strava tracks.
     */
    public function up(): void
    {
        Schema::create('summit_ascent_tracks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ascent_id')->unique();
            $table->foreign('ascent_id')->references('id')->on('summit_ascents')->onDelete('cascade');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('source', 10);                       // gpx | strava
            $table->unsignedBigInteger('strava_activity_id')->nullable();
            $table->boolean('verified')->default(false);
            $table->unsignedInteger('closest_m')->nullable();    // nearest track point → summit
            $table->unsignedInteger('distance_m')->default(0);
            $table->unsignedInteger('elevation_gain_m')->default(0);
            $table->integer('max_elevation_m')->nullable();
            $table->unsignedInteger('duration_s')->nullable();
            $table->dateTime('started_at')->nullable();
            $table->json('points')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('summit_ascent_tracks');
    }
};
