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
            array_push($routes_authors, $route->author);
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

    public function add_route(Request $request)
    {
        $route_validate = $this->route_validate($request->data);
        if ($route_validate != null) {
            return response()->json([
                $route_validate
            ], 422);
        }
        else{

            $sector_route_count = Route::where('sector_id',strip_tags($request->data['sector_id']))->count();
            if($sector_route_count == 0){
                $new_route_num = 1;
            }
            else{
                $new_route_num = $sector_route_count+1;
            }

            // Save route data (without JSON)
            $routeData = $request->data;
            unset($routeData['route_json']); // Remove JSON from route data

            $saved = Route::insertGetId($routeData);

            // Save JSON data separately if provided
            if(isset($request->data['route_json']) && !empty($request->data['route_json'])) {
                $jsonData = [
                    'route_id' => $saved,
                    'json' => $request->data['route_json'],
                    'sector_image_id' => $request->data['sector_id'] // Assuming sector_id is the image reference
                ];
                RouteJsonController::add_route_json($jsonData);
            }

            if(!$saved){
                return response()->json(['error' => 'Error saving route'], 500);
            }
            else{
                return response()->json(['success' => true, 'route_id' => $saved]);
            }
        }
    }

    public function edit_route(Request $request)
    {
        // dd($request->data);
        $route_validate = $this->route_validate($request->data);
        if ($route_validate != null) {
            return response()->json([
                $route_validate
            ], 422);
        }
        else{
            $route = route::where('id', '=', $request->route_id)->first();

            // Save route data (without JSON)
            $routeData = $request->data;
            unset($routeData['route_json']); // Remove JSON from route data

            $saved = $route->update($routeData);

            // Save JSON data separately if provided
            if(isset($request->data['route_json']) && !empty($request->data['route_json'])) {
                $jsonData = [
                    'route_id' => $request->route_id,
                    'json' => $request->data['route_json'],
                    'sector_image_id' => $request->data['sector_id'] // Assuming sector_id is the image reference
                ];
                RouteJsonController::edit_route_json($jsonData);
            }
            
            if(!$saved){
                return response()->json([
                    'errors' => "Saving error",
                ], 500);
            }

            return response()->json(['success' => true]);
        }
    }

    public function del_route(Request $request)
    {
        $route = Route::where('id',strip_tags($request->route_id))->first();
        $route -> delete();
    }

    public function get_routes_quantity(Request $request)
    {
        return SportClimbingRoutesService::get_routes_quantity($request);
    }


    public function get_route_editing_data(Request $request)
    {
        $route = Route::where('id',strip_tags($request->route_id))->first();

        // Fetch JSON data from the separate table
        $routeJson = ClimbingRoutesJson::where('route_id', $route->id)->first();
        if ($routeJson) {
            $route->json = $routeJson->json;
        }

        return $route;
    }


    private function route_validate($request)
    {
        $validator = Validator::make($request, [
            'name' => 'required|max:190',
            'grade' => 'required',
            'sector_id' => 'required',
        ]);
        
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
    }
}

