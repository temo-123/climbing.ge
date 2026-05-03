<?php

namespace App\Http\Controllers\Api\User\Admin\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Validator;
use App\Services\PermissionService;

use App\Models\Guide\Article;

use App\Models\Guide\Sector;
use App\Models\Guide\Route;
use App\Models\Guide\ClimbingRoutesJson;
use App\Models\Guide\Sector_image;
use App\Models\Guide\Mtp;
use App\Models\Guide\Mtp_pitch;

use App\Services\SportClimbingRoutesService;

use App\Http\Controllers\Api\User\Admin\Guide\RouteJsonController;

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
        $auth = PermissionService::authorize('route', 'add');
        if ($auth) return $auth;
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
            unset($routeData['sector_image_id']); // Remove sector_image_id from route data

            $saved = Route::insertGetId($routeData);

            // Save JSON data separately if provided
            if(isset($request->data['route_json']) && !empty($request->data['route_json'])) {
                $jsonData = [
                    'route_id' => $saved,
                    'json' => $request->data['route_json'],
                    'sector_image_id' => $request->data['sector_image_id']
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

    public function edit_route(Request $request, $route_id)
    {
        $auth = PermissionService::authorize('route', 'edit');
        if ($auth) return $auth;
        
        // Check if data is received
        if (!$request->has('data')) {
            return response()->json(['error' => 'No data received', 'all' => $request->all()], 400);
        }
        
        $route = Route::where('id', '=', $route_id)->first();

        if (!$route) {
            return response()->json(['error' => 'Route not found'], 404);
        }

        // Save route data (without JSON)
        $routeData = $request->data;
        unset($routeData['route_json']); // Remove JSON from route data
        unset($routeData['sector_image_id']); // Remove sector_image_id from route data
        unset($routeData['id']); // Don't try to update id
        
        $saved = $route->update($routeData);

        // Save JSON data separately if provided
        if(isset($request->data['route_json']) && !empty($request->data['route_json'])) {
            $jsonData = [
                'route_id' => $route_id,
                'json' => $request->data['route_json'],
                'sector_image_id' => $request->data['sector_image_id']
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

    public function del_route(Request $request)
    {
        $auth = PermissionService::authorize('route', 'del');
        if ($auth) return $auth;
        $route = Route::where('id',strip_tags($request->route_id))->first();

        // Delete related JSON data first to avoid foreign key constraint
        ClimbingRoutesJson::where('route_id', $route->id)->delete();

        $route->delete();
    }

    public function get_routes_quantity(Request $request)
    {
        return SportClimbingRoutesService::get_routes_quantity($request);
    }


    public function get_route_editing_data(Request $request)
    {
        $auth = PermissionService::authorize('route', 'edit');
        if ($auth) return $auth;
        $route = Route::where('id',strip_tags($request->route_id))->first();

        // Fetch JSON data from the separate table
        $routeJson = ClimbingRoutesJson::where('route_id', $route->id)->first();
        if ($routeJson) {
            $route->json = $routeJson->json;
            $route->sector_image_id = $routeJson->sector_image_id;
        }

        return $route;
    }

    public function save_route_drawing(Request $request)
    {
        $auth = PermissionService::authorize('route', 'edit');
        if ($auth) return $auth;

        $routeId = $request->route_id;
        $sectorImageId = $request->sector_image_id;
        $json = $request->json;
        $editedImageData = $request->edited_image; // base64 data URL

        if (!$routeId || !$sectorImageId || !$json) {
            return response()->json(['error' => 'route_id, sector_image_id and json are required'], 422);
        }

        // Get the sector image filename
        $sectorImage = Sector_image::find($sectorImageId);
        if (!$sectorImage) {
            return response()->json(['error' => 'Sector image not found'], 404);
        }

        $filename = $sectorImage->image;
        $originalDir = public_path('images/sector_img/origin_img/');
        $editedPath  = public_path('images/sector_img/' . $filename);
        $originalPath = $originalDir . $filename;

        // Ensure origin_img directory exists
        if (!is_dir($originalDir)) {
            mkdir($originalDir, 0775, true);
        }

        // Save original (only once — do not overwrite if already backed up)
        if (!file_exists($originalPath) && file_exists($editedPath)) {
            copy($editedPath, $originalPath);
        }

        // Save the edited image (composite: background + drawn lines)
        if ($editedImageData) {
            // Strip data URL prefix: "data:image/jpeg;base64," or "data:image/png;base64,"
            $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $editedImageData);
            file_put_contents($editedPath, base64_decode($imageData));
        }

        // Save / update JSON in climbing_routes_jsons
        $existing = ClimbingRoutesJson::where('route_id', $routeId)->first();
        if ($existing) {
            $existing->update(['json' => $json, 'sector_image_id' => $sectorImageId]);
        } else {
            ClimbingRoutesJson::create([
                'route_id'        => $routeId,
                'sector_image_id' => $sectorImageId,
                'json'            => $json,
            ]);
        }

        return response()->json(['success' => true, 'filename' => $filename]);
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

