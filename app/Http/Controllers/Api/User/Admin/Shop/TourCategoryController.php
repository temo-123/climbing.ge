<?php

namespace App\Http\Controllers\Api\User\Admin\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Shop\Tour_category;
use App\Services\PermissionService;

class TourCategoryController extends Controller
{
    public function get_editing_category($category_id)
    {
        $auth = PermissionService::authorize('tour_category', 'view');
        // if ($auth) return $auth;
        
        return Tour_category::where('id', '=', $category_id)->first();
    }

    public function add_category(Request $request)
    {
        $auth = PermissionService::authorize('tour_category', 'add');
        // if ($auth) return $auth;
        
        // dd($request->data);
        $validate = $this->validation($request);

        if ($validate != null) {
            return($validate);
        }
        else{
            $new_product_category = new Tour_category;

            $new_product_category['us_name'] = $request->data['us_name'];
            $new_product_category['ka_name'] = $request->data['ka_name'];
            // $new_product_category['ru_name'] = $request->data['ru_name'];

            $new_product_category -> save();
        }
    }

    public function edit_category(Request $request)
    {
        $auth = PermissionService::authorize('tour_category', 'edit');
        // if ($auth) return $auth;
        
        $validate = $this->validation($request);

        if ($validate != null) {
            return($validate);
        }
        else{
            $editing_product_category = Tour_category::where("id", "=", $request->category_id)->first();

            $editing_product_category['us_name'] = $request->data['us_name'];
            $editing_product_category['ka_name'] = $request->data['ka_name'];
            // $editing_product_category['ru_name'] = $request->data['ru_name'];

            $editing_product_category -> save();
        }
    }

    public function del_category($id)
    {
        $auth = PermissionService::authorize('tour_category', 'del');
        // if ($auth) return $auth;
        
        $deleted_product_category = Tour_category::where("id", "=", $id)->first();
        $deleted_product_category -> delete();
    }

    public function validation($request)
    {
        $validator = validator($data = $request->data, [
            'us_name' => 'required',
            // 'ru_name' => 'required',
            'ka_name' => 'required',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->messages(),
            ], 422);
        }
    }
}

