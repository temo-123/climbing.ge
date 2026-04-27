<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

// Guide models
use App\Models\Guide\Article;
use App\Models\Guide\Locale_article;
use App\Models\Guide\Sector;
use App\Models\Guide\Route;

// Shop models
use App\Models\Shop\Product;
use App\Models\Shop\Locale_product;
use App\Models\Shop\Locale_service;
use App\Models\Shop\Service;
use App\Models\Shop\Locale_tour;
use App\Models\Shop\Tour;

// Blog models
use App\Models\Blog\Post;
use App\Models\Blog\Locale_post;

// Summit models
use App\Models\Summit\Summit;

// Services
use App\Services\ArticlesService;
use App\Services\ProductService;
use App\Services\TourService;
use App\Services\ServicesService;
use App\Services\PostService;
use App\Services\SearchService;

use LanguageDetection\Language;

class SearchController extends Controller
{
    private $detector;

    public function __construct(Language $detector)
    {
        $this->detector = $detector;
    }

    // -------------------------------------------------------------------------
    // Live-suggest endpoint (called while user types)
    // -------------------------------------------------------------------------

    public function suggest(Request $request): JsonResponse
    {
        $query  = trim($request->input('query', ''));
        $locale = SearchService::normalizeLocale($request->input('locale', 'us'));
        $site   = $request->input('site', 'guide');

        if (mb_strlen($query) < 2) {
            return response()->json([]);
        }

        if (!$this->valid_lang($query)) {
            return response()->json(['error' => 'Use only English or Georgian text'], 422);
        }

        $suggestions = [];

        switch ($site) {
            case 'guide':
                foreach (Locale_article::where('locale', $locale)->where('title', 'like', "%{$query}%")->limit(5)->pluck('title') as $t) {
                    $suggestions[] = ['text' => $t, 'type' => 'article'];
                }
                foreach (Sector::where('name', 'like', "%{$query}%")->limit(3)->pluck('name') as $n) {
                    $suggestions[] = ['text' => $n, 'type' => 'sector'];
                }
                foreach (Route::where('name', 'like', "%{$query}%")->limit(3)->pluck('name') as $n) {
                    $suggestions[] = ['text' => $n, 'type' => 'route'];
                }
                $fuzzyPool = Locale_article::where('locale', $locale)->get(['title']);
                break;

            case 'shop':
                foreach (Locale_product::where('locale', $locale)->where('title', 'like', "%{$query}%")->limit(5)->pluck('title') as $t) {
                    $suggestions[] = ['text' => $t, 'type' => 'product'];
                }
                foreach (Locale_tour::where('locale', $locale)->where('title', 'like', "%{$query}%")->limit(3)->pluck('title') as $t) {
                    $suggestions[] = ['text' => $t, 'type' => 'tour'];
                }
                foreach (Locale_service::where('locale', $locale)->where('title', 'like', "%{$query}%")->limit(3)->pluck('title') as $t) {
                    $suggestions[] = ['text' => $t, 'type' => 'service'];
                }
                $fuzzyPool = Locale_product::where('locale', $locale)->get(['title']);
                break;

            case 'blog':
                foreach (Locale_post::where('locale', $locale)->where('title', 'like', "%{$query}%")->limit(5)->pluck('title') as $t) {
                    $suggestions[] = ['text' => $t, 'type' => 'post'];
                }
                foreach (Locale_article::where('locale', $locale)->where('title', 'like', "%{$query}%")->limit(3)->pluck('title') as $t) {
                    $suggestions[] = ['text' => $t, 'type' => 'news'];
                }
                $fuzzyPool = Locale_post::where('locale', $locale)->get(['title']);
                break;

            case 'summit':
                $tf = $locale === 'ka' ? 'ka_title' : 'title';
                foreach (Summit::where('published', 1)->where($tf, 'like', "%{$query}%")->limit(5)->pluck($tf) as $t) {
                    $suggestions[] = ['text' => $t, 'type' => 'summit'];
                }
                foreach (Locale_article::where('locale', $locale)->where('title', 'like', "%{$query}%")->limit(3)->pluck('title') as $t) {
                    $suggestions[] = ['text' => $t, 'type' => 'mount_route'];
                }
                $fuzzyPool = Summit::where('published', 1)->get([$tf]);
                break;

            default:
                return response()->json([]);
        }

        // Fuzzy fill-up when fewer than 3 exact results
        if (count($suggestions) < 3 && !empty($fuzzyPool)) {
            $existing  = array_column($suggestions, 'text');
            $fuzzy     = SearchService::fuzzyFilter($fuzzyPool, $query, isset($tf) ? $tf : 'title');
            $fillType  = $site === 'blog' ? 'post' : ($site === 'summit' ? 'summit' : ($site === 'shop' ? 'product' : 'article'));
            foreach ($fuzzy as $item) {
                $val = isset($tf) ? ($item->$tf ?? '') : ($item->title ?? '');
                if ($val && !in_array($val, $existing) && count($suggestions) < 3) {
                    $suggestions[] = ['text' => $val, 'type' => $fillType];
                    $existing[] = $val;
                }
            }
        }

        return response()->json(array_slice($suggestions, 0, 3));
    }

    // -------------------------------------------------------------------------
    // Unified search endpoints
    // -------------------------------------------------------------------------

    public function guideSearch(Request $request): JsonResponse
    {
        $query  = trim($request->input('query', ''));
        $locale = SearchService::normalizeLocale($request->input('locale', 'us'));

        if (mb_strlen($query) < 2) {
            return response()->json(['error' => 'Query too short'], 422);
        }

        if (!$this->valid_lang($query)) {
            return response()->json(['error' => 'Use only English or Georgian text'], 422);
        }

        $articlesResult = SearchService::localeSearch(
            Locale_article::class, 'global_article',
            ArticlesService::class, 'get_locale_article_use_locale',
            $query, $locale
        );

        $descField    = $locale === 'ka' ? 'ka_description' : 'us_description';
        $exactSectors = Sector::with('article')
            ->where(function ($q) use ($query, $descField) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere($descField, 'like', "%{$query}%");
            })->get();

        $sectorsSuggested = false;
        $sectorBest       = null;
        if ($exactSectors->isEmpty()) {
            $allSectors   = Sector::get(['id', 'name']);
            $fuzzySectors = SearchService::fuzzyFilter($allSectors, $query, 'name');
            if (!empty($fuzzySectors)) {
                $sectorsSuggested = true;
                $sectorBest       = $fuzzySectors[0]->name ?? null;
                $sectorIds        = array_map(fn($s) => $s->id, $fuzzySectors);
                $exactSectors     = Sector::with('article')->whereIn('id', $sectorIds)->get();
            }
        }

        $exactRoutes = Route::with('sector')
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('grade', 'like', "%{$query}%");
            })->get();

        $routesSuggested = false;
        $routeBest       = null;
        if ($exactRoutes->isEmpty()) {
            $allRoutes   = Route::get(['id', 'name']);
            $fuzzyRoutes = SearchService::fuzzyFilter($allRoutes, $query, 'name');
            if (!empty($fuzzyRoutes)) {
                $routesSuggested = true;
                $routeBest       = $fuzzyRoutes[0]->name ?? null;
                $routeIds        = array_map(fn($r) => $r->id, $fuzzyRoutes);
                $exactRoutes     = Route::with('sector')->whereIn('id', $routeIds)->get();
            }
        }

        $suggested  = $articlesResult['suggested'] || $sectorsSuggested || $routesSuggested;
        $best_match = $articlesResult['best_match'] ?? $sectorBest ?? $routeBest;

        return response()->json([
            'suggested'      => $suggested,
            'original_query' => $suggested ? $query : null,
            'best_match'     => $suggested ? $best_match : null,
            'articles'       => $articlesResult['results'],
            'sectors'        => $exactSectors->toArray(),
            'routes'         => $exactRoutes->toArray(),
        ]);
    }

    public function shopSearch(Request $request): JsonResponse
    {
        $query  = trim($request->input('query', ''));
        $locale = SearchService::normalizeLocale($request->input('locale', 'us'));

        if (mb_strlen($query) < 2) {
            return response()->json(['error' => 'Query too short'], 422);
        }

        if (!$this->valid_lang($query)) {
            return response()->json(['error' => 'Use only English or Georgian text'], 422);
        }

        $productsResult = SearchService::localeSearch(
            Locale_product::class, 'global_product',
            ProductService::class, 'get_locale_product_use_locale',
            $query, $locale
        );

        $toursResult = SearchService::localeSearch(
            Locale_tour::class, 'global_tour',
            TourService::class, 'get_tours_use_locale',
            $query, $locale
        );

        $servicesResult = SearchService::localeSearch(
            Locale_service::class, 'global_service',
            ServicesService::class, 'get_locale_services_use_locale',
            $query, $locale
        );

        $suggested  = $productsResult['suggested'] || $toursResult['suggested'] || $servicesResult['suggested'];
        $best_match = $productsResult['best_match'] ?? $toursResult['best_match'] ?? $servicesResult['best_match'];

        return response()->json([
            'suggested'      => $suggested,
            'original_query' => $suggested ? $query : null,
            'best_match'     => $suggested ? $best_match : null,
            'products'       => $productsResult['results'],
            'tours'          => $toursResult['results'],
            'services'       => $servicesResult['results'],
        ]);
    }

    public function blogSearch(Request $request): JsonResponse
    {
        $query  = trim($request->input('query', ''));
        $locale = SearchService::normalizeLocale($request->input('locale', 'us'));

        if (mb_strlen($query) < 2) {
            return response()->json(['error' => 'Query too short'], 422);
        }

        if (!$this->valid_lang($query)) {
            return response()->json(['error' => 'Use only English or Georgian text'], 422);
        }

        $postsResult = SearchService::localeSearch(
            Locale_post::class, 'global_post',
            PostService::class, 'get_locale_posts_use_locale',
            $query, $locale
        );

        $newsResult = SearchService::localeSearch(
            Locale_article::class, 'global_article',
            ArticlesService::class, 'get_locale_article_use_locale',
            $query, $locale,
            fn($article) => $article->category === 'news'
        );

        $suggested  = $postsResult['suggested'] || $newsResult['suggested'];
        $best_match = $postsResult['best_match'] ?? $newsResult['best_match'];

        return response()->json([
            'suggested'      => $suggested,
            'original_query' => $suggested ? $query : null,
            'best_match'     => $suggested ? $best_match : null,
            'posts'          => $postsResult['results'],
            'news'           => $newsResult['results'],
        ]);
    }

    public function summitSearch(Request $request): JsonResponse
    {
        $query  = trim($request->input('query', ''));
        $locale = SearchService::normalizeLocale($request->input('locale', 'us'));

        if (mb_strlen($query) < 2) {
            return response()->json(['error' => 'Query too short'], 422);
        }

        if (!$this->valid_lang($query)) {
            return response()->json(['error' => 'Use only English or Georgian text'], 422);
        }

        $titleField  = $locale === 'ka' ? 'ka_title' : 'title';
        $descField   = $locale === 'ka' ? 'ka_description' : 'description';

        $summitsResult = SearchService::directSearch(
            Summit::class, [$titleField, $descField], $query, true
        );

        $mountResult = SearchService::localeSearch(
            Locale_article::class, 'global_article',
            ArticlesService::class, 'get_locale_article_use_locale',
            $query, $locale,
            fn($article) => $article->category === 'mount_route'
        );

        $suggested  = $summitsResult['suggested'] || $mountResult['suggested'];
        $best_match = $summitsResult['best_match'] ?? $mountResult['best_match'];

        return response()->json([
            'suggested'      => $suggested,
            'original_query' => $suggested ? $query : null,
            'best_match'     => $suggested ? $best_match : null,
            'summits'        => $summitsResult['results'],
            'mount_routes'   => $mountResult['results'],
        ]);
    }

    // -------------------------------------------------------------------------
    // Legacy endpoints (backward compatibility)
    // -------------------------------------------------------------------------

    public function productSearch(Request $request)
    {
        if (!$this->check_leng($request->query_request)) {
            return response()->json(['error' => 'Language error'], 400);
        }
        $locale = 'us';
        return SearchService::universalSearch(Locale_product::class, ProductService::class, 'get_locale_product_use_locale', 'product', 'global_product_' . $locale, $request->query_request, $locale);
    }

    public function articleSearch(Request $request)
    {
        if (!$this->check_leng($request->query_request)) {
            return response()->json(['error' => 'Language error'], 400);
        }
        $locale = 'us';
        return SearchService::universalSearch(Locale_article::class, ArticlesService::class, 'get_locale_article_use_locale', 'article', 'global_article_' . $locale, $request->query_request, $locale);
    }

    public function filmSearch(Request $request)
    {
        return response()->json([]);
    }

    private function check_leng($text)
    {
        $language = (string) $this->detector->detect($text);
        return $language === 'ka' || $language === 'en';
    }

    private function valid_lang(string $text): bool
    {
        // Allow only Latin (en) and Georgian (ka) characters, digits, spaces, basic punctuation
        return (bool) preg_match('/^[A-Za-zა-ჿ0-9\s\-.,!?\'"()]+$/u', $text);
    }
}
