<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Sport / bouldering route ascents (logbook ticks) — the single-pitch
     * counterpart of mtp_ascents. Logged-in only, self-reported date.
     * user_id is SET NULL so AccountDeletionService can keep them anonymously.
     */
    public function up(): void
    {
        Schema::create('route_ascents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('route_id');
            $table->foreign('route_id')->references('id')->on('routes')->onDelete('cascade');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->date('ascent_date');
            $table->string('ascent_style', 32);
            $table->unsignedSmallInteger('attempts')->nullable();
            $table->string('suggested_grade', 32)->nullable();
            $table->text('comment')->nullable();
            $table->string('photo')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'ascent_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('route_ascents');
    }
};
