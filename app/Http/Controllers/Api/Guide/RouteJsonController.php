<?php

namespace App\Http\Controllers\Api\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Guide\Route;
use App\Models\Guide\Routes_json;

use Validator;

class RouteJsonController extends Controller
{
    public function get_editing_route_json(Request $request) {
        $act_route = Route::where('id', '=', $request->route_id)->first();
        return $act_route->json;
    }

    public static function add_route_json($data) {
        $route_validate = (new static)->add_route_json_validate($data);
        if ($route_validate != null) { 
            return response()->json([
                $route_validate
            ], 422);
        }
        else{
            $saved = Routes_json::insertGetId($data);
                
            if(!$saved){
                App::abort(500, 'Error');
            }
            else{
                return $saved;
            }
        }
    }

    public static function edit_route_json($data) {
        $ceck_json_count = Routes_json::where('id', '=', $data['route_id'])->count();
        if($ceck_json_count >= 0){
            (new static)->add_route_json($data);
        }
        else{
            $route_validate = (new static)->edit_route_json_validate($data);
            if ($route_validate != null) { 
                return response()->json([
                    $route_validate
                ], 422);
            }
            else{
                $ceck_json = Routes_json::where('id', '=', $data['route_id'])->first();
                $saved = $ceck_json->update($request->$data); 

                if(!$saved){
                    return 'Error';
                }

                return $saved;
            }
        }
    }
    
    public function del_route_json() {
        
    }

    public static function add_route_json_validate($data) {
        $validator = Validator::make($data, [
            'json' => 'required',
            'route_id' => 'required|unique:routes_jsons,route_id',
            'sector_image_id' => 'required',
        ]);
        
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
    }

    public static function edit_route_json_validate($data) {
        $validator = Validator::make($data, [
            'json' => 'required',
            // 'route_id' => 'required|unique:routes_jsons,route_id',
            'sector_image_id' => 'required',
        ]);
        
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
    }
}
