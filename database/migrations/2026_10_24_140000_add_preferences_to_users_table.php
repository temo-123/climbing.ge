<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Personal defaults applied on every device after login (lang and grade
     * already existed): mountaineering grade system, animations on/off, and
     * when the climber answered (or skipped) the welcome questions.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('mount_grade', 16)->nullable()->after('grade');
            $table->boolean('animations')->default(true)->after('mount_grade');
            $table->timestamp('onboarded_at')->nullable()->after('animations');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['mount_grade', 'animations', 'onboarded_at']);
        });
    }
};
