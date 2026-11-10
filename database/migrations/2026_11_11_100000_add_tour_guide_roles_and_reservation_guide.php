<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Tour guides: a "general" guide per tour + the guide in charge of each reservation
 * (docs/FUNCTIONS_DOCS/shop/tour_guides.md).
 *
 * user_tours.is_main                 the tour's general guide (new reservations go to them).
 *                                    Backfill: the first guide added (lowest id) of every tour.
 * tour_reservations.guide_id         guide in charge (one of the tour's guides). Backfill:
 *                                    the tour's general guide. NULL when the tour has no guide.
 * tour_reservation_guide_changes     history of switches (who, from, to, by whom, note).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_tours', function (Blueprint $table) {
            if (!Schema::hasColumn('user_tours', 'is_main')) {
                $table->boolean('is_main')->default(false)->after('user_id');
            }
        });

        Schema::table('tour_reservations', function (Blueprint $table) {
            if (!Schema::hasColumn('tour_reservations', 'guide_id')) {
                $table->unsignedBigInteger('guide_id')->nullable()->after('tour_id');
                $table->index('guide_id');
                $table->foreign('guide_id')->references('id')->on('users')->nullOnDelete();
            }
        });

        if (!Schema::hasTable('tour_reservation_guide_changes')) {
            Schema::create('tour_reservation_guide_changes', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('reservation_id');
                $table->foreign('reservation_id')->references('id')->on('tour_reservations')->cascadeOnDelete();
                $table->unsignedBigInteger('from_user_id')->nullable();
                $table->foreign('from_user_id')->references('id')->on('users')->nullOnDelete();
                $table->unsignedBigInteger('to_user_id')->nullable();
                $table->foreign('to_user_id')->references('id')->on('users')->nullOnDelete();
                $table->unsignedBigInteger('changed_by')->nullable();
                $table->foreign('changed_by')->references('id')->on('users')->nullOnDelete();
                $table->string('reason', 30)->nullable(); // manual | handover | guide_removed
                $table->string('note', 500)->nullable();
                $table->timestamps();

                $table->index('reservation_id');
            });
        }

        // backfill: first guide of each tour = general; reservations → general guide
        $first = DB::table('user_tours')->selectRaw('tour_id, MIN(id) as id')->groupBy('tour_id')->pluck('id');
        if ($first->count() && !DB::table('user_tours')->where('is_main', true)->exists()) {
            DB::table('user_tours')->whereIn('id', $first)->update(['is_main' => true]);
        }
        $mains = DB::table('user_tours')->where('is_main', true)->pluck('user_id', 'tour_id');
        foreach ($mains as $tour_id => $user_id) {
            DB::table('tour_reservations')->where('tour_id', $tour_id)->whereNull('guide_id')->update(['guide_id' => $user_id]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('tour_reservation_guide_changes');
        Schema::table('tour_reservations', function (Blueprint $table) {
            if (Schema::hasColumn('tour_reservations', 'guide_id')) {
                $table->dropForeign(['guide_id']);
                $table->dropIndex(['guide_id']);
                $table->dropColumn('guide_id');
            }
        });
        Schema::table('user_tours', function (Blueprint $table) {
            if (Schema::hasColumn('user_tours', 'is_main')) $table->dropColumn('is_main');
        });
    }
};
