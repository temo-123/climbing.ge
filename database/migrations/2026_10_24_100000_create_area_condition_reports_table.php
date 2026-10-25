<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Short "how is it right now" reports on outdoor / ice areas (dry, wet,
     * ice in, hazard, closure…). Logged-in only. user_id is SET NULL so
     * AccountDeletionService can keep them anonymously.
     */
    public function up(): void
    {
        Schema::create('area_condition_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('article_id');
            $table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->string('condition', 32);
            $table->string('note', 500)->nullable();
            $table->boolean('admin_hidden')->default(false);
            $table->timestamps();

            $table->index(['article_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('area_condition_reports');
    }
};
