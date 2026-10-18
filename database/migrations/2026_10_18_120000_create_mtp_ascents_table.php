<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Multi-pitch ascents — like summit ascents, but logged-in only (same as MTP
     * reviews) and always self-reported with a user-chosen date (MTPs have no
     * coordinates to GPS-validate against).
     */
    public function up(): void
    {
        Schema::create('mtp_ascents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mtp_id');
            $table->foreign('mtp_id')->references('id')->on('mtps')->onDelete('cascade');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->date('ascent_date');
            $table->time('ascent_time')->nullable();
            $table->string('ascent_style', 32)->nullable();
            $table->text('comment')->nullable();
            $table->string('photo')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mtp_ascents');
    }
};
