<?php

namespace App\Http\Controllers\Api\Training;

use App\Http\Controllers\Controller;
use App\Models\Training\Training;
use Illuminate\Http\Request;

class TrainingController extends Controller
{
    function get_all_trainings(Request $request)
    {
        $query = Training::with(['steps', 'translations'])->where('is_published', 1);

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        return $query->get()->map(fn($training) => self::format($training))->values();
    }

    function get_training_data($id)
    {
        $training = Training::with(['steps', 'translations'])
            ->where('id', strip_tags($id))
            ->where('is_published', 1)
            ->first();

        if (!$training) {
            return response()->json(['error' => 'Not found'], 404);
        }

        return self::format($training);
    }

    public static function format(Training $training): array
    {
        return [
            'id' => $training->id,
            'name' => $training->name,
            'description' => $training->description,
            'type' => $training->type,
            'difficulty' => $training->difficulty,
            'targetMuscle' => $training->target_muscle,
            'coachTip' => $training->coach_tip,
            'imageUrl' => $training->image_url,
            'productId' => $training->product_id,
            'hangTime' => $training->hang_time,
            'restTime' => $training->rest_time,
            'reps' => $training->reps,
            'sets' => $training->sets,
            'recoverTime' => $training->recover_time,
            'steps' => $training->steps->map(fn($step) => [
                'order' => $step->step_order,
                'phase' => $step->phase,
                'label' => $step->label,
                'durationSeconds' => $step->duration_seconds,
                'imageUrl' => $step->image_url,
                'instructions' => $step->instructions,
            ])->values(),
            'translations' => $training->translations->mapWithKeys(fn($t) => [
                $t->locale => [
                    'name' => $t->name,
                    'description' => $t->description,
                    'coachTip' => $t->coach_tip,
                    'targetMuscle' => $t->target_muscle,
                ],
            ]),
        ];
    }
}
