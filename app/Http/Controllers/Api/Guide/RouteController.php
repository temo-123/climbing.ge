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

    // public function add_route(Request $request)
    // {
    //     $route_validate = $this->route_validate($request->data);
    //     if ($route_validate != null) {
    //         return response()->json([
    //             $route_validate
    //         ], 422);
    //     }
    //     else{

    //         $sector_route_count = Route::where('sector_id',strip_tags($request->data['sector_id']))->count();
    //         if($sector_route_count == 0){
    //             $new_route_num = 1;
    //         }
    //         else{
    //             $new_route_num = $sector_route_count+1;
    //         }

    //         // Save route data (without JSON)
    //         $routeData = $request->data;
    //         unset($routeData['route_json']); // Remove JSON from route data
    //         unset($routeData['sector_image_id']); // Remove sector_image_id from route data

    //         $saved = Route::insertGetId($routeData);

    //         // Save JSON data separately if provided
    //         if(isset($request->data['route_json']) && !empty($request->data['route_json'])) {
    //             $jsonData = [
    //                 'route_id' => $saved,
    //                 'json' => $request->data['route_json'],
    //                 'sector_image_id' => $request->data['sector_image_id']
    //             ];
    //             RouteJsonController::add_route_json($jsonData);
    //         }

    //         if(!$saved){
    //             return response()->json(['error' => 'Error saving route'], 500);
    //         }
    //         else{
    //             return response()->json(['success' => true, 'route_id' => $saved]);
    //         }
    //     }
    // }

    // public function edit_route(Request $request)
    // {
    //     // dd($request->data);
    //     $route_validate = $this->route_validate($request->data);
    //     if ($route_validate != null) {
    //         return response()->json([
    //             $route_validate
    //         ], 422);
    //     }
    //     else{
    //         $route = route::where('id', '=', $request->route_id)->first();

    //         // Save route data (without JSON)
    //         $routeData = $request->data;
    //         unset($routeData['route_json']); // Remove JSON from route data
    //         unset($routeData['sector_image_id']); // Remove sector_image_id from route data

    //         $saved = $route->update($routeData);

    //         // Save JSON data separately if provided
    //         if(isset($request->data['route_json']) && !empty($request->data['route_json'])) {
    //             $jsonData = [
    //                 'route_id' => $request->route_id,
    //                 'json' => $request->data['route_json'],
    //                 'sector_image_id' => $request->data['sector_image_id']
    //             ];
    //             RouteJsonController::edit_route_json($jsonData);
    //         }
            
    //         if(!$saved){
    //             return response()->json([
    //                 'errors' => "Saving error",
    //             ], 500);
    //         }

    //         return response()->json(['success' => true]);
    //     }
    // }

    // public function del_route(Request $request)
    // {
    //     $route = Route::where('id',strip_tags($request->route_id))->first();

    //     // Delete related JSON data first to avoid foreign key constraint
    //     ClimbingRoutesJson::where('route_id', $route->id)->delete();

    //     $route->delete();
    // }

    public function get_routes_quantity(Request $request)
    {
        return SportClimbingRoutesService::get_routes_quantity($request);
    }


    // public function get_route_editing_data(Request $request)
    // {
    //     $route = Route::where('id',strip_tags($request->route_id))->first();

    //     // Fetch JSON data from the separate table
    //     $routeJson = ClimbingRoutesJson::where('route_id', $route->id)->first();
    //     if ($routeJson) {
    //         $route->json = $routeJson->json;
    //         $route->sector_image_id = $routeJson->sector_image_id;
    //     }

    //     return $route;
    // }


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
        $category = $route_type === 'boulder' ? 'bouldering' : 'sport climbing';
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

    // private function route_validate($request)
    // {
    //     $validator = Validator::make($request, [
    //         'name' => 'required|max:190',
    //         'grade' => 'required',
    //         'sector_id' => 'required',
    //     ]);
        
    //     if ($validator->fails()) {
    //         return response()->json($validator->errors(), 422);
    //     }
    // }
}

