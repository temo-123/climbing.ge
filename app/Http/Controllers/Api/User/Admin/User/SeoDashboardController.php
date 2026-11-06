<?php

namespace App\Http\Controllers\Api\User\Admin\User;

use App\Http\Controllers\Controller;
use App\Services\PermissionService;
use App\Services\Seo\SearchConsoleClient;
use App\Services\Seo\SearchConsoleSync;
use App\Services\Seo\SeoDashboardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

/**
 * Admin SEO dashboard (user.climbing.ge/seo_dashboard): Search Console data + content quality.
 * Docs: docs/FUNCTIONS_DOCS/user/seo_dashboard.md
 */
class SeoDashboardController extends Controller
{
    public function __construct(private SeoDashboardService $seo)
    {
    }

    private function days(Request $request): int
    {
        return in_array((int) $request->query('days'), [7, 28, 90], true) ? (int) $request->query('days') : 28;
    }

    public function status()
    {
        if ($auth = PermissionService::authorize('seo', 'show')) return $auth;

        return response()->json([
            'configured' => SearchConsoleClient::configured(),
            'properties' => array_values((array) config('services.search_console.properties', [])),
            'last_sync'  => Cache::get(SearchConsoleSync::STATUS_KEY),
            'can_sync'   => ! PermissionService::authorize('seo', 'edit'),
        ]);
    }

    public function overview(Request $request)
    {
        if ($auth = PermissionService::authorize('seo', 'show')) return $auth;
        return response()->json($this->seo->overview($this->days($request)));
    }

    public function pages(Request $request)
    {
        if ($auth = PermissionService::authorize('seo', 'show')) return $auth;

        $filter = in_array($request->query('filter'), ['all', 'losing', 'striking', 'low_ctr'], true) ? $request->query('filter') : 'all';
        $host   = $request->query('host') ?: null;
        return response()->json($this->seo->pages($this->days($request), $filter, $host));
    }

    public function page_queries(Request $request, string $url_hash)
    {
        if ($auth = PermissionService::authorize('seo', 'show')) return $auth;
        if (! preg_match('/^[0-9a-f]{40}$/', $url_hash)) {
            return response()->json(['error' => 'Bad page id'], 422);
        }
        return response()->json($this->seo->pageQueries($url_hash));
    }

    public function content_quality()
    {
        if ($auth = PermissionService::authorize('seo', 'show')) return $auth;
        return response()->json($this->seo->contentQuality());
    }

    /** Pull the last few days now (the nightly run does this at 05:30). */
    public function sync()
    {
        if ($auth = PermissionService::authorize('seo', 'edit')) return $auth;
        if (! SearchConsoleClient::configured()) {
            return response()->json(['error' => 'Search Console is not configured'], 422);
        }
        try {
            return response()->json(app(SearchConsoleSync::class)->run(5));
        } catch (\Throwable $e) {
            SearchConsoleSync::recordFailure($e);
            return response()->json(['error' => $e->getMessage()], 502);
        }
    }
}
