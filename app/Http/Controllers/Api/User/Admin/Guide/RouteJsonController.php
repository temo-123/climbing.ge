<?php

namespace App\Http\Controllers\Api\User\Admin\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Guide\Route;
use App\Models\Guide\ClimbingRoutesJson;
use App\Services\PermissionService;

use Validator;

class RouteJsonController extends Controller
{
    public function get_editing_route_json(Request $request) {
        $auth = PermissionService::authorize('route_json', 'view');
        if ($auth) return $auth;
        
        $act_route = Route::where('id', '=', $request->route_id)->first();
        return $act_route->json;
    }

    public static function add_route_json($data) {
        $auth = PermissionService::authorize('route_json', 'add');
        if ($auth) return $auth;
        
        $route_validate = (new static)->add_route_json_validate($data);
        if ($route_validate != null) {
            return response()->json([
                $route_validate
            ], 422);
        }
        else{
            $saved = ClimbingRoutesJson::insertGetId($data);

            if(!$saved){
                return response()->json(['error' => 'Error saving route JSON'], 500);
            }
            else{
                return $saved;
            }
        }
    }

    public static function edit_route_json($data) {
        $auth = PermissionService::authorize('route_json', 'edit');
        if ($auth) return $auth;
        
        $existing_json = ClimbingRoutesJson::where('route_id', '=', $data['route_id'])->first();

        if(!$existing_json){
            // Create new record
            return (new static)->add_route_json($data);
        }
        else{
            $route_validate = (new static)->edit_route_json_validate($data);
            if ($route_validate != null) {
                return response()->json([
                    $route_validate
                ], 422);
            }
            else{
                $saved = $existing_json->update($data);

                if(!$saved){
                    return response()->json(['error' => 'Error updating route JSON'], 500);
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
            'route_id' => 'required|unique:climbing_routes_jsons,route_id',
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
