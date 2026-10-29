<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * A climber's Strava account (OAuth), used to import an activity as the
     * GPS track of a summit ascent. Tokens are stored encrypted (model casts).
     */
    public function up(): void
    {
        Schema::create('strava_connections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('athlete_id');
            $table->string('athlete_name')->nullable();
            $table->text('access_token');
            $table->text('refresh_token');
            $table->dateTime('expires_at');
            $table->string('scope')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('strava_connections');
    }
};
