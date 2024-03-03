<?php

namespace App\Http\Controllers\Api\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Validator;

use App\Models\Article;

use App\Models\Sector;
use App\Models\Route;
use App\Models\Mtp;
use App\Models\Mtp_pitch;

use App\Services\SportClimbingRoutesService;

class RouteController extends Controller
{
    public function index()
    {
        return Route::latest('id')->get();
    }

    public function get_routes_for_forum($sector_id)
    {
        return Route::where('sector_id','=', $sector_id)->get();
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

    public function edit_route(Request $request)
    {
        $route_validate = $this->route_validate($request->data);
        if ($route_validate != null) { 
            return response()->json([
                $route_validate
            ], 422);
        }
        else{
        // $request->user()->authorizeRoles(['manager', 'admin']);
// dd($request->data);
        // if ($request -> isMethod('post')) {
            // $this->route_validate($request);

            $route = route::where('id', '=', $request->route_id)->first();

            // $category = $this->get_route_category($request);

            $route->category = $request->data['category'];
            $route->sector_id = $request->data['sector_id'];
            $route->grade = $request->data['grade'];
            $route->or_grade = $request->data['or_grade'];
            $route->name = $request->data['name'];
            $route->text = $request->data['text'] ;

            $route->anchor_type = $request->data["anchor_type"];
            $route->bolts_type = $request->data["bolts_type"];
            // $route['bolts_type']=$request->data["bolts_type"];

            $route->height = $request->data['height'];
            $route->bolts = $request->data['bolts'];
            $route->author = $request->data["author"];
            $route->creation_data = $request->data["creation_data"];
            $route->first_ascent = $request->data["first_ascent"];
            
            $route->update();
        }
    }

    public function destroy($id)
    {
        $route_id=$id;
        $route = Route::where('id',strip_tags($route_id))->first();
        $route ->delete();
    }

    public function add_route(Request $request)
    {
        // dd($request->validate);
        // $request->user()->authorizeRoles(['manager', 'admin']);

        
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

            $route = new Route();
            $route['num']=$new_route_num;

            // $category = $this->get_route_category($request);
            $route['category']=$request->data["category"];

            $route['sector_id']=$request->data["sector_id"];

            $route['grade']=$request->data["grade"];
            $route['or_grade']=$request->data["or_grade"];

            $route['name']=$request->data["name"];
            $route['text']=$request->data["text"];

            $route['anchor_type']=$request->data["anchor_type"];
            $route['bolts_type']=$request->data["bolts_type"];

            $route['height']=$request->data["height"];
            $route['bolts']=$request->data["bolts"];

            $route['author']=$request->data["author"];
            $route['creation_data']=$request->data["creation_data"];
            $route['first_ascent']=$request->data["first_ascent"];

            $route -> save();
        }
    }


    public function get_routes_quantity(Request $request)
    {
        return SportClimbingRoutesService::get_routes_quantity($request);
    }


    public function get_route_editing_data(Request $request)
    {
        return Route::where('id',strip_tags($request->route_id))->first();
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

