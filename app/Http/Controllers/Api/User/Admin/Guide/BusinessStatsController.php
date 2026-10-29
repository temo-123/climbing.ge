<?php

namespace App\Http\Controllers\Api\User\Admin\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Guide\Article;
use App\Models\Guide\Locale_article;
use App\Models\Guide\Suport_local_bisnes;
use App\Services\BusinessStatsService;
use App\Services\PermissionService;

class BusinessStatsController extends Controller
{
    // statistics modal in the admin "Local businesses" table
    public function get_bisnes_stats(Request $request, $bisnes_id)
    {
        $auth = PermissionService::authorize('local_bisnes', 'show');
        if ($auth) return $auth;

        $bisnes = Suport_local_bisnes::findOrFail($bisnes_id);
        $stats = BusinessStatsService::stats($bisnes->id, (int) $request->query('days', 30));

        // article id → title + link data for the "shown on" table
        $articles = Article::whereIn('id', array_keys($stats['by_article']))->get(['id', 'category', 'url_title', 'us_article_id']);
        $titles = Locale_article::whereIn('id', $articles->pluck('us_article_id'))->pluck('title', 'id');
        $stats['by_article'] = collect($stats['by_article'])->map(function ($count, $article_id) use ($articles, $titles) {
            $article = $articles->firstWhere('id', (int) $article_id);
            return [
                'article_id' => (int) $article_id,
                'title'      => $article ? ($titles[$article->us_article_id] ?? $article->url_title) : '#' . $article_id,
                'category'   => $article?->category,
                'url_title'  => $article?->url_title,
                'count'      => $count,
            ];
        })->values();

        $stats['bisnes'] = ['id' => $bisnes->id, 'url_title' => $bisnes->url_title, 'published' => $bisnes->published];

        return response()->json($stats);
    }
}
