<?php

namespace App\Http\Controllers\Api\User\Admin\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Guide\Region;
use App\Models\Guide\Article_region;
use Validator;
use App\Services\PermissionService;

class RegionController extends Controller
{
    public function add_region(Request $request)
    {
        $auth = PermissionService::authorize('region', 'add');
        // if ($auth) return $auth;
        
        $validate = $this->region_validate($request->data);

        if ($validate != null) {
            return response()->json([
                'validation' => $validate
            ], 422);
        }
        else{
            $region = new Region;

            $region['us_name'] = $request->data['us_name'];
            // $region['ru_name'] = $request->data['ru_name'];
            $region['ka_name'] = $request->data['ka_name'];

            $region['us_text'] = $request->data['us_text'];
            // $region['ru_text'] = $request->data['ru_text'];
            $region['ka_text'] = $request->data['ka_text'];

            $region['map'] = $request->data['map'];

            $region -> save();
        }
    }

    public function get_editing_region_data(Request $request)
    {
        $auth = PermissionService::authorize('region', 'edit');
        // if ($auth) return $auth;
        
        return Region::where('id',strip_tags($request->id))->first();
    }

    public function edit_region(Request $request)
    {
        $auth = PermissionService::authorize('region', 'edit');
        // if ($auth) return $auth;
        
        $validate = $this->region_validate($request->data);


        if ($validate != null) {
            return response()->json([
                'validation' => $validate
            ], 422);
        }
        else{
            $editing_region = Region::where('id',strip_tags($request->id))->first();

            $editing_region['us_name'] = $request->data['us_name'];
            // $editing_region['ru_name'] = $request->data['ru_name'];
            $editing_region['ka_name'] = $request->data['ka_name'];

            $editing_region['us_text'] = $request->data['us_text'];
            // $editing_region['ru_text'] = $request->data['ru_text'];
            $editing_region['ka_text'] = $request->data['ka_text'];

            $editing_region['map'] = $request->data['map'];

            $editing_region -> save();
        }
    }

    public function del_region(Request $request)
    {
        $auth = PermissionService::authorize('region', 'del');
        // if ($auth) return $auth;
        
        $region = Region::where('id',strip_tags($request->id))->first();
        $region -> delete();
    }

    public function region_validate($data)
    {
        $validator = Validator::make($data, [
            'us_name' => 'required',
            // 'ru_name' => 'required',
            'ka_name' => 'required',
        ]);
        if ($validator->fails()) {
            return $validator->messages();
        }
    }

    
}
