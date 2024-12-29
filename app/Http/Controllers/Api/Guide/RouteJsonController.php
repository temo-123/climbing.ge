<?php

namespace App\Http\Controllers\Api\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Guide\Route;
use App\Models\Guide\Route_line;

class RouteJsonController extends Controller
{
    public function get_editing_route_json(Request $request) {
        $act_route = Route::where('id', '=', $request->route_id)->first();
        return $act_route->json;
    }

    public static function add_route_json($data) {
        $saved = Route_line::insertGetId($data);
            
        if(!$saved){
            App::abort(500, 'Error');
        }
        else{
            return $saved;
        }
    }

    public static function edit_route_json($data) {
        $ceck_json_count = Route_line::where('id', '=', $data['route_id'])->count();
        if($ceck_json_count >= 0){
            (new static)->add_route_json($data);
        }
        else{
            $ceck_json = Route_line::where('id', '=', $data['route_id'])->first();
            $saved = $ceck_json->update($request->$data); 

            if(!$saved){
                return 'Error';
            }

            return $saved;
        }
    }
    
    public function del_route_json() {
        
    }
}
