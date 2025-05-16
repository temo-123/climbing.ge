<?php

namespace App\Http\Controllers\Api\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Guide\Route;
use Validator;

class IceRouteController extends Controller
{
    public function get_all_routes()
    {
        return Route::latest('id')->get();
    }

    public function get_route_editing_data($id)
    {
        return Route::where('id',strip_tags($id))->first();
    }

    public function get_route_data_for_modal(Request $request)
    {
        return Route::where('id',strip_tags($request->route_id))->first();
    }

    public function edit_route(Request $request)
    {
        $ice_route_validate = $this->ice_route_validate($request->data);
        if ($ice_route_validate != null) { 
            return response()->json([
                $ice_route_validate
            ], 422);
        }
        else{
            $ice_route = Route::where('id', '=', $request->route_id)->first();

            $ice_route->category = $request->data['category'];
            $ice_route->ice_sector_id = $request->data['ice_sector_id'];
            $ice_route->grade = $request->data['grade'];
            $ice_route->name = $request->data['name'];
            $ice_route->text = $request->data['text'] ;
            $ice_route->anchor_type = $request->data["anchor_type"];
            $ice_route->height = $request->data['height'];
            $ice_route->bolts = $request->data['bolts'];
            $ice_route->author = $request->data["author"];
            $ice_route->creation_data = $request->data["creation_data"];
            $ice_route->first_ascent = $request->data["first_ascent"];
            
            $ice_route->update();
        }
    }

    public function del_route($id)
    {
        $ice_route_id=$id;
        $ice_route = Route::where('id',strip_tags($ice_route_id))->first();
        $ice_route ->delete();
    }

    public function add_route(Request $request)
    {
        $ice_route_validate = $this->ice_route_validate($request->data);
        if ($ice_route_validate != null) { 
            return response()->json([
                $ice_route_validate
            ], 422);
        }
        else{
            
            $sector_ice_route_count = Route::where('ice_sector_id',strip_tags($request->data['ice_sector_id']))->count();

            if($sector_ice_route_count == 0){
                $new_ice_route_num = 1;
            }
            else{
                $new_ice_route_num = $sector_ice_route_count+1;
            }

            $ice_route = new Ice_route();
            $ice_route['num']=$new_ice_route_num;

            $ice_route['category']=$request->data["category"];

            $ice_route['ice_sector_id']=$request->data["ice_sector_id"];

            $ice_route['grade']=$request->data["grade"];

            $ice_route['name']=$request->data["name"];
            $ice_route['text']=$request->data["text"];

            $ice_route['anchor_type']=$request->data["anchor_type"];

            $ice_route['height']=$request->data["height"];
            $ice_route['bolts']=$request->data["bolts"];

            $ice_route['author']=$request->data["author"];
            $ice_route['creation_data']=$request->data["creation_data"];
            $ice_route['first_ascent']=$request->data["first_ascent"];

            $ice_route -> save();
        }
    }

    private function ice_route_validate($request)
    {
        $validator = Validator::make($request, [
            'name' => 'required|max:190',
            'grade' => 'required',
            'ice_sector_id' => 'required',
        ]);
        
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
    }

}
