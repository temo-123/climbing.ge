<?php

namespace App\Http\Controllers\Api\User\Admin\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Shop\Product_category;
use App\Models\Shop\Product_subcategory;
use App\Models\Shop\product_options;

use App\Services\PermissionService;

use Validator;

class ProductCategoryController extends Controller
{

    public function add_product_category(Request $request)
    {
        $auth = PermissionService::authorize('product_category', 'add');
        if ($auth) return $auth;
        $validate = $this->validation($request);

        if ($validate != null) {
            return($validate);
        }
        else{
            $new_product_category = new Product_category;

            $new_product_category['us_name'] = $request->data['us_name'];
            $new_product_category['ka_name'] = $request->data['ka_name'];
            // $new_product_category['ru_name'] = $request->data['ru_name'];

            $new_product_category -> save();
        }
    }

    public function get_editing_product_category(Request $request){
        $auth = PermissionService::authorize('product_category', 'edit');
        if ($auth) return $auth;
        $editing_product_category = Product_category::where("id", "=", request()->id)->first();

        return response()->json($editing_product_category);
    }

    public function edit_product_category(Request $request, $id)
    {
        $auth = PermissionService::authorize('product_category', 'edit');
        if ($auth) return $auth;
        $validate = $this->validation($request);

        if ($validate != null) {
            return($validate);
        }
        else{
            // dd($request->editing_data['us_name']);
            $editing_product_category = Product_category::where("id", "=", $id)->first();

            $editing_product_category['us_name'] = $request->data['us_name'];
            $editing_product_category['ka_name'] = $request->data['ka_name'];
            // $editing_product_category['ru_name'] = $request->data['ru_name'];

            $editing_product_category -> save();
        }
    }

    public function del_product_category($id)
    {
        $auth = PermissionService::authorize('product_category', 'del');
        if ($auth) return $auth;
        // Delete all subcategories first to avoid foreign key constraint violation
        Product_subcategory::where('category_id', $id)->delete();

        $deleted_product_category = Product_category::where("id", "=", $id)->first();
        $deleted_product_category->delete();
    }

    public function validation($request)
    {
        $validator = validator($data = $request->data, [
            'us_name' => 'required',
            'ka_name' => 'required',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->messages(),
            ], 422);
        }
    }
}
