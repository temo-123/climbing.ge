<?php

namespace App\Http\Controllers\Api\Guide;

use App\Http\Controllers\Controller;
use App\Models\Guide\Article;
use App\Services\GymHoursService;

/** Public: indoor gyms' opening hours per weekday (GymHoursService). */
class GymHoursController extends Controller
{
    // one gym (its page) — null when it has the same hours every day
    public function get_gym_hours($article_id)
    {
        $article = Article::where('id', $article_id)->where('category', 'indoor')->whereIn('published', [1, 2])->first();
        return response()->json(['week' => $article ? GymHoursService::week_for($article->id) : null]);
    }

    // every gym with per-day hours (the indoor list: cards, "open now")
    public function get_all_gym_hours()
    {
        return response()->json(GymHoursService::all_indoor_weeks());
    }
}
