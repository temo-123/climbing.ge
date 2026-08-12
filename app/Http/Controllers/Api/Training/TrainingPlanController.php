<?php

namespace App\Http\Controllers\Api\Training;

use App\Http\Controllers\Controller;
use App\Models\Training\TrainingPlan;

class TrainingPlanController extends Controller
{
    function get_all_plans()
    {
        $plans = TrainingPlan::with(['translations', 'sessions.planSessionTrainings.training.steps', 'sessions.planSessionTrainings.training.translations'])
            ->where('is_published', 1)
            ->get();

        return $plans->map(fn($plan) => self::format($plan))->values();
    }

    function get_plan_data($id)
    {
        $plan = TrainingPlan::with(['translations', 'sessions.planSessionTrainings.training.steps', 'sessions.planSessionTrainings.training.translations'])
            ->where('slug', strip_tags($id))
            ->where('is_published', 1)
            ->first();

        if (!$plan) {
            return response()->json(['error' => 'Not found'], 404);
        }

        return self::format($plan);
    }

    public static function format(TrainingPlan $plan): array
    {
        return [
            'id' => $plan->slug,
            'name' => $plan->name,
            'emoji' => $plan->emoji,
            'level' => $plan->level,
            'tagline' => $plan->tagline,
            'description' => $plan->description,
            'coachNote' => $plan->coach_note,
            'daysPerWeek' => $plan->days_per_week,
            'weeks' => $plan->weeks,
            'isPreset' => true,
            'sessions' => $plan->sessions->map(fn($session) => [
                'dayIndex' => $session->day_index,
                'dayLabel' => $session->day_label,
                'workouts' => $session->planSessionTrainings->map(
                    fn($pst) => TrainingController::format($pst->training)
                )->values(),
            ])->values(),
            'translations' => $plan->translations->mapWithKeys(fn($t) => [
                $t->locale => [
                    'name' => $t->name,
                    'tagline' => $t->tagline,
                    'description' => $t->description,
                    'coachNote' => $t->coach_note,
                ],
            ]),
        ];
    }
}
