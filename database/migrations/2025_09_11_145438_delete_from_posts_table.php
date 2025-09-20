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
        Schema::dropIfExists('forum_posts');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('forum_posts', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->text('text')->nullable();
            $table->string('image')->nullable();

            $table->string('sector_id')->nullable();
            $table->string('route_id')->nullable();
            $table->string('mtp_id')->nullable();
            $table->string('region_id')->nullable();

            $table->foreignId('topic_id')->constrained();
            $table->foreignId('user_id')->constrained();

            $table->timestamps();
        });
    }
};
