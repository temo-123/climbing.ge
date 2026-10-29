<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * "Find a partner" board on the guide (/partners): a climber posts a day,
     * area, discipline and grade range; others reach them via their climber
     * profile. Posts disappear from the board after climb_date.
     */
    public function up(): void
    {
        Schema::create('partner_posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('article_id')->nullable();
            $table->foreign('article_id')->references('id')->on('articles')->onDelete('set null');
            $table->string('place', 120)->nullable();
            $table->date('climb_date');
            $table->string('discipline', 24);
            $table->string('grade_from', 8)->nullable();
            $table->string('grade_to', 8)->nullable();
            $table->string('note', 500)->nullable();
            $table->string('contact', 120)->nullable();
            $table->boolean('admin_hidden')->default(false);
            $table->timestamps();

            $table->index(['climb_date', 'admin_hidden']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partner_posts');
    }
};
