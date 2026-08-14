<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Training\UserPlanState;
use App\Models\Training\UserTrainingHistory;
use App\Models\Training\UserWorkout;
use Illuminate\Http\Request;

class UserTrainingSyncController extends Controller
{
    /**
     * Full-state sync for the training mobile app: custom workouts, plan
     * activation state, and workout history. Each request carries the
     * device's entire current set per user; the response returns this
     * user's entire canonical set back. `id`/`date` values are echoed back
     * unchanged -- the app merges local/remote rows by matching on them.
     */
    public function sync(Request $request)
    {
        $request->validate([
            'workouts' => 'sometimes|array',
            'plans' => 'sometimes|array',
            'history' => 'sometimes|array',
        ]);

        $userId = $request->user()->id;

        foreach ($request->input('workouts', []) as $row) {
            $this->syncWorkout($userId, $row);
        }

        foreach ($request->input('plans', []) as $row) {
            $this->syncPlanState($userId, $row);
        }

        foreach ($request->input('history', []) as $row) {
            $this->syncHistory($userId, $row);
        }

        return response()->json([
            'workouts' => UserWorkout::where('user_id', $userId)->whereNull('deleted_at')->get()
                ->map(fn($w) => self::formatWorkout($w))->values(),
            'plans' => UserPlanState::where('user_id', $userId)->get()
                ->map(fn($p) => self::formatPlanState($p))->values(),
            'history' => UserTrainingHistory::where('user_id', $userId)->get()
                ->map(fn($h) => self::formatHistory($h))->values(),
            'syncedAt' => now()->toISOString(),
        ]);
    }

    private function syncWorkout(int $userId, array $row): void
    {
        if (empty($row['id'])) {
            return;
        }

        if (!empty($row['deletedAt'])) {
            UserWorkout::where('user_id', $userId)->where('client_id', $row['id'])->delete();
            return;
        }

        $existing = UserWorkout::where('user_id', $userId)->where('client_id', $row['id'])->first();

        if ($existing && !$this->isNewer($row['updatedAt'] ?? null, $existing->updated_at)) {
            return;
        }

        UserWorkout::updateOrCreate(
            ['user_id' => $userId, 'client_id' => $row['id']],
            [
                'name' => $row['name'] ?? '',
                'description' => $row['description'] ?? null,
                'type' => $row['type'] ?? null,
                'hang_time' => $row['hangTime'] ?? 0,
                'rest_time' => $row['restTime'] ?? 0,
                'reps' => $row['reps'] ?? 0,
                'sets' => $row['sets'] ?? 0,
                'recover_time' => $row['recoverTime'] ?? 0,
                'updated_at' => $row['updatedAt'] ?? null,
                'deleted_at' => null,
            ]
        );
    }

    private function syncPlanState(int $userId, array $row): void
    {
        if (empty($row['id'])) {
            return;
        }

        $existing = UserPlanState::where('user_id', $userId)->where('plan_id', $row['id'])->first();

        if ($existing && !$this->isNewer($row['activatedAt'] ?? null, $existing->activated_at)) {
            return;
        }

        UserPlanState::updateOrCreate(
            ['user_id' => $userId, 'plan_id' => $row['id']],
            [
                'is_active' => $row['isActive'] ?? false,
                'activated_at' => $row['activatedAt'] ?? null,
                'start_date' => $row['startDate'] ?? null,
                'notifications_enabled' => $row['notificationsEnabled'] ?? false,
                'notification_time' => $row['notificationTime'] ?? null,
                'notification_ids' => $row['notificationIds'] ?? [],
                'calendar_enabled' => $row['calendarEnabled'] ?? false,
                'calendar_event_ids' => $row['calendarEventIds'] ?? [],
            ]
        );
    }

    private function syncHistory(int $userId, array $row): void
    {
        if (empty($row['date'])) {
            return;
        }

        $existing = UserTrainingHistory::where('user_id', $userId)->where('date', $row['date'])->first();

        if ($existing && !$this->isNewer($row['updatedAt'] ?? null, $existing->updated_at)) {
            return;
        }

        UserTrainingHistory::updateOrCreate(
            ['user_id' => $userId, 'date' => $row['date']],
            [
                'workout_name' => $row['workoutName'] ?? '',
                'workout_type' => $row['workoutType'] ?? null,
                'reps_completed' => $row['repsCompleted'] ?? 0,
                'sets_completed' => $row['setsCompleted'] ?? 0,
                'status' => $row['status'] ?? 'success',
                'plan_id' => $row['planId'] ?? null,
                'updated_at' => $row['updatedAt'] ?? null,
            ]
        );
    }

    /**
     * Plain string comparison, matching the app's own conflict resolution
     * (`mergeById` in utils/sync.ts) -- never parse these as dates.
     */
    private function isNewer(?string $incoming, ?string $stored): bool
    {
        if ($incoming === null) {
            return false;
        }

        if ($stored === null) {
            return true;
        }

        return $incoming >= $stored;
    }

    private static function formatWorkout(UserWorkout $w): array
    {
        return [
            'id' => $w->client_id,
            'name' => $w->name,
            'description' => $w->description,
            'type' => $w->type,
            'hangTime' => $w->hang_time,
            'restTime' => $w->rest_time,
            'reps' => $w->reps,
            'sets' => $w->sets,
            'recoverTime' => $w->recover_time,
            'updatedAt' => $w->updated_at,
            'deletedAt' => $w->deleted_at,
        ];
    }

    private static function formatPlanState(UserPlanState $p): array
    {
        return [
            'id' => $p->plan_id,
            'isActive' => (bool) $p->is_active,
            'activatedAt' => $p->activated_at,
            'startDate' => $p->start_date,
            'notificationsEnabled' => (bool) $p->notifications_enabled,
            'notificationTime' => $p->notification_time,
            'notificationIds' => $p->notification_ids ?? [],
            'calendarEnabled' => (bool) $p->calendar_enabled,
            'calendarEventIds' => $p->calendar_event_ids ?? [],
        ];
    }

    private static function formatHistory(UserTrainingHistory $h): array
    {
        return [
            'date' => $h->date,
            'workoutName' => $h->workout_name,
            'workoutType' => $h->workout_type,
            'repsCompleted' => $h->reps_completed,
            'setsCompleted' => $h->sets_completed,
            'status' => $h->status,
            'planId' => $h->plan_id,
            'updatedAt' => $h->updated_at,
        ];
    }
}
