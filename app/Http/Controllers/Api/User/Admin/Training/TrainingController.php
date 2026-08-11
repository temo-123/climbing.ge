<?php

namespace App\Http\Controllers\Api\User\Admin\Training;

use App\Http\Controllers\Controller;
use App\Models\Training\Training;
use App\Services\Abstract\ImageControllService;
use App\Services\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TrainingController extends Controller
{
    function get_all_trainings()
    {
        $auth = PermissionService::authorize('training', 'show');
        if ($auth) return $auth;

        return Training::with(['steps', 'translations', 'product.us_product', 'product.product_subcategory'])->orderByDesc('created_at')->get();
    }

    function get_training_data($id)
    {
        $auth = PermissionService::authorize('training', 'show');
        if ($auth) return $auth;

        $training = Training::with(['steps', 'translations', 'product.us_product', 'product.product_subcategory'])->where('id', strip_tags($id))->first();
        if (!$training) {
            return response()->json(['error' => 'Not found'], 404);
        }

        return $training;
    }

    function search_products(Request $request)
    {
        $auth = PermissionService::authorize('training', 'show');
        if ($auth) return $auth;

        $query = $request->query('query', '');
        $subcategory_id = $request->query('subcategory_id');

        $products = \App\Models\Shop\Product::with('us_product')
            ->when($subcategory_id, function ($q) use ($subcategory_id) {
                $q->where('subcategory_id', $subcategory_id);
            })
            ->when($query, function ($q) use ($query) {
                $q->whereHas('us_product', function ($sq) use ($query) {
                    $sq->where('title', 'like', '%' . $query . '%');
                });
            })
            ->limit($subcategory_id ? 500 : 40)
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'title' => optional($p->us_product)->title ?? 'Product #' . $p->id,
                ];
            });

        return response()->json(['products' => $products]);
    }

    function create_training(Request $request)
    {
        $auth = PermissionService::authorize('training', 'add');
        if ($auth) return $auth;

        $data = $request->validate([
            'name' => 'required|string|max:120',
            'description' => 'nullable|string',
            'type' => 'required|in:fingerboard,campus,flexibility,strength,endurance',
            'difficulty' => 'nullable|in:easy,medium,hard',
            'target_muscle' => 'nullable|string|max:160',
            'coach_tip' => 'nullable|string',
            'image_url' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:10240',
            'product_id' => 'nullable|exists:products,id',
            'hang_time' => 'nullable|integer',
            'rest_time' => 'nullable|integer',
            'reps' => 'nullable|integer',
            'sets' => 'nullable|integer',
            'recover_time' => 'nullable|integer',
            'is_published' => 'nullable|boolean',
            'steps' => 'nullable|array',
            'steps.*.phase' => 'required_with:steps|in:prepare,hang,rest,recover,work,stretch',
            'steps.*.duration_seconds' => 'required_with:steps|integer',
            'translations' => 'nullable|array',
        ]);

        $image_url = $data['image_url'] ?? null;
        if ($request->hasFile('image')) {
            $uploaded = ImageControllService::image_upload('/images/training_img/', $request, 'image', 2);
            if ($uploaded) {
                $image_url = url('/images/training_img/' . $uploaded);
            }
        }

        $training = DB::transaction(function () use ($data, $request, $image_url) {
            $training = Training::create([
                'id' => $this->generateId($data['name']),
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'type' => $data['type'],
                'difficulty' => $data['difficulty'] ?? 'medium',
                'target_muscle' => $data['target_muscle'] ?? null,
                'coach_tip' => $data['coach_tip'] ?? null,
                'image_url' => $image_url,
                'product_id' => $data['product_id'] ?? null,
                'hang_time' => $data['hang_time'] ?? 7,
                'rest_time' => $data['rest_time'] ?? 3,
                'reps' => $data['reps'] ?? 6,
                'sets' => $data['sets'] ?? 4,
                'recover_time' => $data['recover_time'] ?? 180,
                'is_published' => $data['is_published'] ?? 1,
            ]);

            $this->syncSteps($training, $data['steps'] ?? [], $request);
            $this->syncTranslations($training, $data['translations'] ?? []);

            return $training;
        });

        return $training->load(['steps', 'translations']);
    }

    function update_training(Request $request, $id)
    {
        $auth = PermissionService::authorize('training', 'edit');
        if ($auth) return $auth;

        $training = Training::where('id', strip_tags($id))->first();
        if (!$training) {
            return response()->json(['error' => 'Not found'], 404);
        }

        $data = $request->validate([
            'name' => 'required|string|max:120',
            'description' => 'nullable|string',
            'type' => 'required|in:fingerboard,campus,flexibility,strength,endurance',
            'difficulty' => 'nullable|in:easy,medium,hard',
            'target_muscle' => 'nullable|string|max:160',
            'coach_tip' => 'nullable|string',
            'image_url' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:10240',
            'product_id' => 'nullable|exists:products,id',
            'hang_time' => 'nullable|integer',
            'rest_time' => 'nullable|integer',
            'reps' => 'nullable|integer',
            'sets' => 'nullable|integer',
            'recover_time' => 'nullable|integer',
            'is_published' => 'nullable|boolean',
            'steps' => 'nullable|array',
            'steps.*.phase' => 'required_with:steps|in:prepare,hang,rest,recover,work,stretch',
            'steps.*.duration_seconds' => 'required_with:steps|integer',
            'translations' => 'nullable|array',
        ]);

        $image_url = $data['image_url'] ?? null;
        if ($request->hasFile('image')) {
            $uploaded = ImageControllService::image_upload('/images/training_img/', $request, 'image', 2);
            if ($uploaded) {
                $image_url = url('/images/training_img/' . $uploaded);
            }
        }

        DB::transaction(function () use ($training, $data, $request, $image_url) {
            $training->update([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'type' => $data['type'],
                'difficulty' => $data['difficulty'] ?? 'medium',
                'target_muscle' => $data['target_muscle'] ?? null,
                'coach_tip' => $data['coach_tip'] ?? null,
                'image_url' => $image_url,
                'product_id' => $data['product_id'] ?? null,
                'hang_time' => $data['hang_time'] ?? 7,
                'rest_time' => $data['rest_time'] ?? 3,
                'reps' => $data['reps'] ?? 6,
                'sets' => $data['sets'] ?? 4,
                'recover_time' => $data['recover_time'] ?? 180,
                'is_published' => $data['is_published'] ?? $training->is_published,
            ]);

            $this->syncSteps($training, $data['steps'] ?? [], $request);
            $this->syncTranslations($training, $data['translations'] ?? []);
        });

        return $training->fresh(['steps', 'translations']);
    }

    function del_training($id)
    {
        $auth = PermissionService::authorize('training', 'del');
        if ($auth) return $auth;

        $training = Training::where('id', strip_tags($id))->first();
        if (!$training) {
            return response()->json(['error' => 'Not found'], 404);
        }

        if (DB::table('plan_session_trainings')->where('training_id', $training->id)->exists()) {
            return response()->json(['error' => 'Cannot delete a training that is used in a training plan'], 422);
        }

        $training->delete();

        return response()->json(['success' => true]);
    }

    function bulk_delete(Request $request)
    {
        $auth = PermissionService::authorize('training', 'del');
        if ($auth) return $auth;

        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'string',
        ]);

        $blocked = DB::table('plan_session_trainings')->whereIn('training_id', $request->ids)->pluck('training_id')->unique()->values();
        $deletable = array_diff($request->ids, $blocked->all());

        if (!empty($deletable)) {
            Training::whereIn('id', $deletable)->delete();
        }

        return response()->json(['success' => true, 'count' => count($deletable), 'skipped' => $blocked]);
    }

    function bulk_publish(Request $request)
    {
        $auth = PermissionService::authorize('training', 'edit');
        if ($auth) return $auth;

        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'string',
        ]);

        Training::whereIn('id', $request->ids)->update(['is_published' => 1]);

        return response()->json(['success' => true, 'count' => count($request->ids)]);
    }

    function bulk_unpublish(Request $request)
    {
        $auth = PermissionService::authorize('training', 'edit');
        if ($auth) return $auth;

        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'string',
        ]);

        Training::whereIn('id', $request->ids)->update(['is_published' => 0]);

        return response()->json(['success' => true, 'count' => count($request->ids)]);
    }

    protected function syncSteps(Training $training, array $steps, ?Request $request = null): void
    {
        $training->steps()->delete();
        foreach (array_values($steps) as $index => $step) {
            $image_url = $step['image_url'] ?? null;

            if ($request && $request->hasFile("steps.$index.image")) {
                $uploaded = ImageControllService::image_upload('/images/training_step_img/', $request, "steps.$index.image", 2);
                if ($uploaded) {
                    $image_url = url('/images/training_step_img/' . $uploaded);
                }
            }

            $training->steps()->create([
                'step_order' => $index + 1,
                'phase' => $step['phase'],
                'label' => $step['label'] ?? null,
                'duration_seconds' => $step['duration_seconds'],
                'image_url' => $image_url,
                'instructions' => $step['instructions'] ?? null,
            ]);
        }
    }

    protected function syncTranslations(Training $training, array $translations): void
    {
        $training->translations()->delete();
        foreach ($translations as $locale => $translation) {
            if (empty(array_filter($translation ?? []))) continue;
            $training->translations()->create([
                'locale' => $locale,
                'name' => $translation['name'] ?? null,
                'description' => $translation['description'] ?? null,
                'coach_tip' => $translation['coach_tip'] ?? null,
                'target_muscle' => $translation['target_muscle'] ?? null,
            ]);
        }
    }

    protected function generateId(string $name): string
    {
        $base = Str::slug($name) ?: 'training';
        $id = $base;
        while (Training::where('id', $id)->exists()) {
            $id = $base . '-' . Str::lower(Str::random(6));
        }
        return $id;
    }
}
