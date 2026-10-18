<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Account deletion can keep a user's reviews and MTP ascents anonymously
     * (user_id = NULL) instead of deleting them — AccountDeletionService.
     * mtp_reviews / mtp_ascents switch from cascade to SET NULL; the service now
     * deletes them explicitly when the user doesn't keep them.
     */
    public function up(): void
    {
        Schema::table('sport_route_reviews', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->change();
        });

        foreach (['mtp_reviews', 'mtp_ascents'] as $table_name) {
            Schema::table($table_name, function (Blueprint $table) {
                $table->dropForeign(['user_id']);
            });
            Schema::table($table_name, function (Blueprint $table) {
                $table->unsignedBigInteger('user_id')->nullable()->change();
                $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            });
        }
    }

    public function down(): void
    {
        foreach (['mtp_reviews', 'mtp_ascents'] as $table_name) {
            Schema::table($table_name, function (Blueprint $table) {
                $table->dropForeign(['user_id']);
            });
            Schema::table($table_name, function (Blueprint $table) {
                $table->unsignedBigInteger('user_id')->nullable(false)->change();
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }

        Schema::table('sport_route_reviews', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
        });
    }
};
