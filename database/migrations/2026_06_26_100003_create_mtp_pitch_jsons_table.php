<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mtp_pitch_jsons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('json')->nullable();
            $table->foreignId('mtp_pitch_id')->unique()->constrained('mtp_pitchs')->onDelete('cascade');
            $table->foreignId('sector_image_id')->constrained('sector_images')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mtp_pitch_jsons');
    }
};
