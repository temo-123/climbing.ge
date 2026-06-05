<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('summit_ascent_routes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ascent_id');
            $table->foreign('ascent_id')->references('id')->on('summit_ascents')->cascadeOnDelete();
            $table->unsignedBigInteger('route_id')->nullable();
            $table->foreign('route_id')->references('id')->on('routes')->nullOnDelete();
            $table->string('other_route_name')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('summit_ascent_routes');
    }
};
