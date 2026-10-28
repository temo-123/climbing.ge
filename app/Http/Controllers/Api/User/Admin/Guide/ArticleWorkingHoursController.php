<?php

namespace App\Http\Controllers\Api\User\Admin\Guide;

use App\Http\Controllers\Controller;
use App\Models\Guide\Article;
use App\Services\GymHoursService;
use App\Services\PermissionService;
use Illuminate\Http\Request;

/** The "Working hours" tab of an indoor gym article (article_working_hours). */
class ArticleWorkingHoursController extends Controller
{
    public function get_working_hours($article_id)
    {
        $auth = PermissionService::authorize('article', 'show');
        if ($auth) return $auth;

        $article = Article::findOrFail($article_id);
        return response()->json([
            'week'        => GymHoursService::week_for($article->id),
            'open_time'   => $article->open_time ? substr($article->open_time, 0, 5) : null,
            'closed_time' => $article->closed_time ? substr($article->closed_time, 0, 5) : null,
            'work_status' => $article->work_status,
        ]);
    }

    public function save_working_hours(Request $request, $article_id)
    {
        $auth = PermissionService::authorize('article', 'edit');
        if ($auth) return $auth;

        $article = Article::findOrFail($article_id);
        if ($article->category !== 'indoor') {
            return response()->json(['message' => 'Working hours are for indoor gyms only.'], 422);
        }

        $request->validate([
            'days'           => 'present|array|max:7',
            'days.*.weekday' => 'required|integer|between:1,7|distinct',
            'days.*.open'    => 'nullable|string|max:8',
            'days.*.close'   => 'nullable|string|max:8',
            'days.*.closed'  => 'nullable|boolean',
        ]);

        $week = GymHoursService::save($article, $request->input('days', []));
        $article->refresh();

        return response()->json([
            'week'        => $week,
            'open_time'   => $article->open_time ? substr($article->open_time, 0, 5) : null,
            'closed_time' => $article->closed_time ? substr($article->closed_time, 0, 5) : null,
        ]);
    }
}
