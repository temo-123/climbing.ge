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
        Schema::table('summits', function (Blueprint $table) {
            $table->unsignedBigInteger('mount_id')->nullable()->after('longitude');
            $table->foreign('mount_id')->references('id')->on('mounts')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('summits', function (Blueprint $table) {
            $table->dropForeign(['mount_id']);
            $table->dropColumn('mount_id');
        });
    }
};
