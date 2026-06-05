<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('summits', function (Blueprint $table) {
            $table->string('ka_title')->nullable()->after('title');
            $table->text('ka_description')->nullable()->after('description');
            $table->unsignedBigInteger('mount_id')->nullable()->after('region_id');
            $table->foreign('mount_id')->references('id')->on('mounts')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('summits', function (Blueprint $table) {
            $table->dropForeign(['mount_id']);
            $table->dropColumn(['ka_title', 'ka_description', 'mount_id']);
        });
    }
};
