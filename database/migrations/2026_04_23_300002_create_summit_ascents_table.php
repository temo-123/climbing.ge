<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('summit_ascents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('summit_id');
            $table->foreign('summit_id')->references('id')->on('summits')->cascadeOnDelete();
            $table->string('name');
            $table->string('surname');
            $table->string('email')->nullable();
            $table->unsignedBigInteger('route_id')->nullable();
            $table->foreign('route_id')->references('id')->on('routes')->nullOnDelete();
            $table->string('other_route')->nullable();
            $table->text('comment')->nullable();
            $table->string('photo')->nullable();
            $table->boolean('is_gps_validated')->default(false);
            $table->decimal('user_latitude', 10, 8)->nullable();
            $table->decimal('user_longitude', 11, 8)->nullable();
            $table->date('ascent_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('summit_ascents');
    }
};
