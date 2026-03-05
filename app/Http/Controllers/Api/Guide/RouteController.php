<?php

namespace App\Http\Controllers\Api\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Validator;

use App\Models\Guide\Article;

use App\Models\Guide\Sector;
use App\Models\Guide\Route;
use App\Models\Guide\ClimbingRoutesJson;
use App\Models\Guide\Mtp;
use App\Models\Guide\Mtp_pitch;

use App\Services\SportClimbingRoutesService;

use App\Http\Controllers\Api\Guide\RouteJsonController;

class RouteController extends Controller
{
    public function get_all_routes()
    {
        return Route::latest('id')->get();
    }

    public function get_routes_by_category(Request $request)
    {
        return Route::where('category', $request->category)->latest('id')->get();
    }

    public function get_routes_by_category_array(Request $request)
    {
        $searchTerms = $request->categories;
        
        return Route::
                    where(function ($q) use ($searchTerms) {
                        foreach ($searchTerms as $term) {
                            $q->orWhere('category', $term);
                        }
                    })
                ->latest('id')
            ->get();
    }


    public function routes_authers() {
        $routes = Route::get('author');

        $routes_authors = [];

        foreach ($routes as $route) {
            // Split author names by the specified symbols
            $author = trim($route->author);
            if (!empty($author) && $author !== ' ' && $author !== null) {

                // Split by the specified symbols: comma, hyphen, plus, ampersand, arrow
                $split_authors = preg_split('/,|\+|&|->|-/', $author);
                
                // Clean up each author name (trim spaces and handle capitalization)
                foreach ($split_authors as $split_author) {
                    $clean_author = trim($split_author);
                    if (!empty($clean_author)) {
                        // Capitalize first letter of each word for consistency
                        $clean_author = ucwords(strtolower($clean_author));
                        array_push($routes_authors, $clean_author);
                    }
                }
            }
        }
        
        $full_routes_authors = array_diff($routes_authors, [null, "", " "]);

        $authors = array_count_values($full_routes_authors);
        arsort($authors);
        
        return $authors;
    }

    public function routes_authers_by_categories(Request $request) {
        // Map frontend category values to database categories
        $categoryMap = [
            'sport' => 'sport climbing',
            'boulder' => 'bouldering',
            'ice' => 'ice climbing',
            'dry' => 'dry tooling',
            'mtp' => 'mtp',
        ];
        
        // Reverse map for database to frontend
        $dbToFrontendMap = [
            'sport climbing' => 'sport',
            'bouldering' => 'boulder',
            'ice climbing' => 'ice',
            'dry tooling' => 'dry',
            'mtp' => 'mtp',
        ];
        
        // Get route_categories from request body (POST) or query parameters
        $routeCategories = $request->input('route_categories');
        
        // If not in body, check query parameters
        if ($routeCategories === null) {
            $routeCategories = $request->query('route_categories');
        }
        
        // If still null, check for 'categories' parameter
        if ($routeCategories === null) {
            $routeCategories = $request->query('categories');
        }
        
        // If still null, return all authors (fallback to original method)
        if ($routeCategories === null) {
            return $this->routes_authers();
        }
        
        // Ensure it's an array
        if (!is_array($routeCategories)) {
            $routeCategories = [$routeCategories];
        }
        
        // Map frontend categories to database categories
        $dbCategories = [];
        $hasMtp = false;
        foreach ($routeCategories as $category) {
            if (isset($categoryMap[$category])) {
                $dbCategory = $categoryMap[$category];
                if ($dbCategory === 'mtp') {
                    $hasMtp = true;
                } else {
                    $dbCategories[] = $dbCategory;
                }
            }
        }
        
        // Build nested structure: { AuthorName: { category: count, ... } }
        $authorsCategories = [];

        // Query routes for non-mtp categories
        if (!empty($dbCategories)) {
            $routes = Route::whereIn('category', $dbCategories)->get(['author', 'category']);

            foreach ($routes as $route) {
                // Get the frontend category name
                $frontendCategory = $dbToFrontendMap[$route->category] ?? $route->category;
                
                // Split author names by the specified symbols
                $author = trim($route->author);
                if (!empty($author) && $author !== ' ' && $author !== null) {

                    // Split by the specified symbols: comma, hyphen, plus, ampersand, arrow
                    $split_authors = preg_split('/,|\+|&|->|-/', $author);
                    
                    // Clean up each author name (trim spaces and handle capitalization)
                    foreach ($split_authors as $split_author) {
                        $clean_author = trim($split_author);
                        if (!empty($clean_author)) {
                            // Capitalize first letter of each word for consistency
                            $clean_author = ucwords(strtolower($clean_author));
                            
                            // Initialize author if not exists
                            if (!isset($authorsCategories[$clean_author])) {
                                $authorsCategories[$clean_author] = [];
                            }
                            
                            // Initialize category for this author if not exists
                            if (!isset($authorsCategories[$clean_author][$frontendCategory])) {
                                $authorsCategories[$clean_author][$frontendCategory] = 0;
                            }
                            
                            // Increment count
                            $authorsCategories[$clean_author][$frontendCategory]++;
                        }
                    }
                }
            }
        }

        // Query MTPs if 'mtp' category is requested
        if ($hasMtp) {
            $mtps = Mtp::get(['author']);

            foreach ($mtps as $mtp) {
                // Split author names by the specified symbols
                $author = trim($mtp->author);
                if (!empty($author) && $author !== ' ' && $author !== null) {

                    // Split by the specified symbols: comma, hyphen, plus, ampersand, arrow
                    $split_authors = preg_split('/,|\+|&|->|-/', $author);
                    
                    // Clean up each author name (trim spaces and handle capitalization)
                    foreach ($split_authors as $split_author) {
                        $clean_author = trim($split_author);
                        if (!empty($clean_author)) {
                            // Capitalize first letter of each word for consistency
                            $clean_author = ucwords(strtolower($clean_author));
                            
                            // Initialize author if not exists
                            if (!isset($authorsCategories[$clean_author])) {
                                $authorsCategories[$clean_author] = [];
                            }
                            
                            // Initialize mtp category for this author if not exists
                            if (!isset($authorsCategories[$clean_author]['mtp'])) {
                                $authorsCategories[$clean_author]['mtp'] = 0;
                            }
                            
                            // Increment count
                            $authorsCategories[$clean_author]['mtp']++;
                        }
                    }
                }
            }
        }
        
        // Sort by total routes per author (descending)
        uasort($authorsCategories, function($a, $b) {
            $sumA = array_sum($a);
            $sumB = array_sum($b);
            return $sumB - $sumA;
        });
        
        return $authorsCategories;
    }

    public function show($id)
    {
        return route::where('id',strip_tags($id))->get();
    }

    public function get_route_for_modal(Request $request)
    {
        $route = route::where('id',strip_tags($request->route_id))->first();
        $revs = $route->review;

        $route['reviews_count'] = $revs->count();

        if($revs->count() > 0){
            $total = 0;
            foreach ($revs as $rev) {
                $total = $total + $rev->stars;
            }
            $total = $total / $revs->count();
            $route['reviews_stars'] = round($total, 1);
        }

        return $route;
    }

    public function get_routes_quantity(Request $request)
    {
        return SportClimbingRoutesService::get_routes_quantity($request);
    }

    public function get_related_routes_jsons(Request $request)
    {
        $sectorImageId = strip_tags($request->sector_image_id);
        $excludeRouteId = $request->has('exclude_route_id') ? strip_tags($request->exclude_route_id) : null;

        $query = ClimbingRoutesJson::where('sector_image_id', $sectorImageId);

        if ($excludeRouteId) {
            $query->where('route_id', '!=', $excludeRouteId);
        }

        $relatedJsons = $query->with('route')->get();

        // Return only the JSON data
        return $relatedJsons->pluck('json');
    }

    public function get_most_popular_routes($route_type)
    {
        // Map frontend route types to database categories
        $categoryMap = [
            'sport' => 'sport climbing',
            'boulder' => 'bouldering',
            'ice' => 'ice climbing',
            'dry' => 'dry tooling',
        ];

        $category = $categoryMap[$route_type] ?? 'sport climbing';
        $perPage = request('per_page', 10);
        $minReviews = request('min_reviews', 1);
        
        // Validate per_page values
        $validPerPage = [5, 10, 15, 25, 50];
        if (!in_array($perPage, $validPerPage)) {
            $perPage = 10;
        }
        
        // Validate min_reviews
        $minReviews = max(1, (int)$minReviews);
        
        $routes = Route::where('category', $category)
            ->with(['review', 'sector', 'sector.article'])
            ->get()
            ->map(function ($route) {
                $reviewCount = $route->review->count();
                $averageStars = $reviewCount > 0 
                    ? round($route->review->avg('stars'), 1) 
                    : 0;
                



                // Get region and spot name through article relationship
                $regionName = 'Unknown Region';
                $spotName = 'Unknown Spot';
                $articleUrlTitle = null;
                if ($route->sector && $route->sector->article) {
                    $article = $route->sector->article;
                    
                    // Get region
                    $regions = $article->outdoor_region;
                    if ($regions && $regions->count() > 0) {
                        // Try us_name first, then ka_name
                        $region = $regions->first();
                        $regionName = $region->us_name ?: $region->ka_name ?: 'Unknown Region';
                    }
                    
                    // Get spot name (article title)
                    $localeArticle = $article->global_article_us ?: $article->global_article_ka;
                    if ($localeArticle) {
                        $spotName = $localeArticle->title ?: 'Unknown Spot';
                    }
                    
                    // Get article URL title for navigation
                    $articleUrlTitle = $article->url_title;
                }
                




                return [
                    'name' => $route->name ?? 'Unnamed Route',
                    'review_count' => $reviewCount,
                    'stars' => $averageStars,
                    'region' => $regionName,
                    'spot_name' => $spotName,
                    'article_url_title' => $articleUrlTitle,

                    'sector' => $route->sector->name ?? 'Unknown Sector',
                    'route_id' => $route->id,
                    'grade' => $route->grade,
                    'or_grade' => $route->or_grade,
                    'height' => $route->height,
                    'sector_id' => $route->sector->id ?? null,
                    'article_id' => $route->sector->article_id ?? null
                ];
            })
            ->filter(function ($route) use ($minReviews) {
                return $route['review_count'] >= $minReviews;
            })
            ->sortByDesc(function ($route) {
                // Sort by stars first, then by review count
                return [$route['stars'], $route['review_count']];
            })
            ->values();

        // Convert to paginated collection
        $currentPage = request('page', 1);
        $total = $routes->count();
        $start = ($currentPage - 1) * $perPage;
        $paginatedRoutes = $routes->slice($start, $perPage)->values();
        
        return response()->json([
            'data' => $paginatedRoutes,
            'pagination' => [
                'current_page' => (int)$currentPage,
                'per_page' => (int)$perPage,
                'total' => $total,
                'last_page' => ceil($total / $perPage),
                'from' => $total > 0 ? $start + 1 : 0,
                'to' => min($start + $perPage, $total)
            ]
        ]);
    }

    public function get_route_jsons_for_sector_image(Request $request)
    {
        $sectorImageId = strip_tags($request->sector_image_id);

        $routeJsons = ClimbingRoutesJson::where('sector_image_id', $sectorImageId)
            ->with('route')
            ->get();

        return $routeJsons->map(function($item) {
            return [
                'route_id' => $item->route_id,
                'json' => $item->json,
                'route_name' => $item->route ? $item->route->name : null
            ];
        });
    }
}
