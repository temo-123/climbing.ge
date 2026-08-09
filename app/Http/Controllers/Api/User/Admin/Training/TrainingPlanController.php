<?php

namespace App\Http\Controllers\Api\User\Admin\Training;

use App\Http\Controllers\Controller;
use App\Models\Training\TrainingPlan;
use App\Services\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TrainingPlanController extends Controller
{
    protected function withRelations()
    {
        return ['translations', 'sessions.planSessionTrainings.training'];
    }

    function get_all_plans()
    {
        $auth = PermissionService::authorize('training_plan', 'show');
        if ($auth) return $auth;

        return TrainingPlan::with($this->withRelations())->orderByDesc('created_at')->get();
    }

    function get_plan_data($id)
    {
        $auth = PermissionService::authorize('training_plan', 'show');
        if ($auth) return $auth;

        $plan = TrainingPlan::with($this->withRelations())->where('id', strip_tags($id))->first();
        if (!$plan) {
            return response()->json(['error' => 'Not found'], 404);
        }

        return $plan;
    }

    function create_plan(Request $request)
    {
        $auth = PermissionService::authorize('training_plan', 'add');
        if ($auth) return $auth;

        $data = $this->validatePlan($request);

        $plan = DB::transaction(function () use ($data) {
            $plan = TrainingPlan::create([
                'id' => $this->generateId($data['name']),
                'name' => $data['name'],
                'emoji' => $data['emoji'] ?? null,
                'level' => $data['level'],
                'tagline' => $data['tagline'] ?? null,
                'description' => $data['description'] ?? null,
                'coach_note' => $data['coach_note'] ?? null,
                'days_per_week' => $data['days_per_week'],
                'weeks' => $data['weeks'] ?? 0,
                'is_published' => $data['is_published'] ?? 1,
            ]);

            $this->syncSessions($plan, $data['sessions'] ?? []);
            $this->syncTranslations($plan, $data['translations'] ?? []);

            return $plan;
        });

        return $plan->load($this->withRelations());
    }

    function update_plan(Request $request, $id)
    {
        $auth = PermissionService::authorize('training_plan', 'edit');
        if ($auth) return $auth;

        $plan = TrainingPlan::where('id', strip_tags($id))->first();
        if (!$plan) {
            return response()->json(['error' => 'Not found'], 404);
        }

        $data = $this->validatePlan($request);

        DB::transaction(function () use ($plan, $data) {
            $plan->update([
                'name' => $data['name'],
                'emoji' => $data['emoji'] ?? null,
                'level' => $data['level'],
                'tagline' => $data['tagline'] ?? null,
                'description' => $data['description'] ?? null,
                'coach_note' => $data['coach_note'] ?? null,
                'days_per_week' => $data['days_per_week'],
                'weeks' => $data['weeks'] ?? 0,
                'is_published' => $data['is_published'] ?? $plan->is_published,
            ]);

            $this->syncSessions($plan, $data['sessions'] ?? []);
            $this->syncTranslations($plan, $data['translations'] ?? []);
        });

        return $plan->fresh($this->withRelations());
    }

    function del_plan($id)
    {
        $auth = PermissionService::authorize('training_plan', 'del');
        if ($auth) return $auth;

        $plan = TrainingPlan::where('id', strip_tags($id))->first();
        if (!$plan) {
            return response()->json(['error' => 'Not found'], 404);
        }

        $plan->delete();

        return response()->json(['success' => true]);
    }

    function bulk_delete(Request $request)
    {
        $auth = PermissionService::authorize('training_plan', 'del');
        if ($auth) return $auth;

        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'string',
        ]);

        TrainingPlan::whereIn('id', $request->ids)->delete();

        return response()->json(['success' => true, 'count' => count($request->ids)]);
    }

    function bulk_publish(Request $request)
    {
        $auth = PermissionService::authorize('training_plan', 'edit');
        if ($auth) return $auth;

        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'string',
        ]);

        TrainingPlan::whereIn('id', $request->ids)->update(['is_published' => 1]);

        return response()->json(['success' => true, 'count' => count($request->ids)]);
    }

    function bulk_unpublish(Request $request)
    {
        $auth = PermissionService::authorize('training_plan', 'edit');
        if ($auth) return $auth;

        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'string',
        ]);

        TrainingPlan::whereIn('id', $request->ids)->update(['is_published' => 0]);

        return response()->json(['success' => true, 'count' => count($request->ids)]);
    }

    protected function validatePlan(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:120',
            'emoji' => 'nullable|string|max:16',
            'level' => 'required|in:beginner,intermediate,expert,maintenance',
            'tagline' => 'nullable|string|max:200',
            'description' => 'nullable|string',
            'coach_note' => 'nullable|string',
            'days_per_week' => 'required|integer|min:1|max:7',
            'weeks' => 'nullable|integer|min:0',
            'is_published' => 'nullable|boolean',
            'sessions' => 'nullable|array',
            'sessions.*.day_index' => 'required_with:sessions|integer|min:0|max:6',
            'sessions.*.day_label' => 'required_with:sessions|string|max:40',
            'sessions.*.training_ids' => 'nullable|array',
            'sessions.*.training_ids.*' => 'string',
            'translations' => 'nullable|array',
        ]);
    }

    protected function syncSessions(TrainingPlan $plan, array $sessions): void
    {
        $plan->sessions()->delete();
        foreach ($sessions as $session) {
            $planSession = $plan->sessions()->create([
                'day_index' => $session['day_index'],
                'day_label' => $session['day_label'],
            ]);

            foreach (array_values($session['training_ids'] ?? []) as $index => $trainingId) {
                $planSession->planSessionTrainings()->create([
                    'training_id' => $trainingId,
                    'sort_order' => $index,
                ]);
            }
        }
    }

    protected function syncTranslations(TrainingPlan $plan, array $translations): void
    {
        $plan->translations()->delete();
        foreach ($translations as $locale => $translation) {
            if (empty(array_filter($translation ?? []))) continue;
            $plan->translations()->create([
                'locale' => $locale,
                'name' => $translation['name'] ?? null,
                'tagline' => $translation['tagline'] ?? null,
                'description' => $translation['description'] ?? null,
                'coach_note' => $translation['coach_note'] ?? null,
            ]);
        }
    }

    protected function generateId(string $name): string
    {
        $base = Str::slug($name) ?: 'plan';
        $id = $base;
        while (TrainingPlan::where('id', $id)->exists()) {
            $id = $base . '-' . Str::lower(Str::random(6));
        }
        return $id;
    }
}
