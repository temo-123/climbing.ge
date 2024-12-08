<?php

namespace App\Http\Controllers\Api\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;

use App\Models\Shop\Product_subcategory;

class ProductSubcategoryController extends Controller
{
    function get_all_subcategories(Request $request) {
        
    }
    function get_subcategories_for_category(Request $request) {
        return Product_subcategory::where("category_id", "=", $request->category_id,)->get();
    }
    function get_subcategory(Request $request) {
        return Product_subcategory::where("id", "=", $request->id,)->first();
    }
    function create_subcategory(Request $request) {
        // dd($request->category_id);
        $validate = $this->validation($request);

        if ($validate != null) {
            return($validate);
        }
        else{
            $new_product_category = new Product_subcategory;

            $new_product_category['us_name'] = $request->data['us_name'];
            $new_product_category['ka_name'] = $request->data['ka_name'];

            $new_product_category['category_id'] = $request->category_id;

            $new_product_category -> save();
        }
    }
    function edit_subcategory(Request $request) {
        $validate = $this->validation($request);

        if ($validate != null) {
            return($validate);
        }
        else{
            $editing_product_category = Product_subcategory::where("id", "=", $request->id)->first();

            $editing_product_category['us_name'] = $request->data['us_name'];
            $editing_product_category['ka_name'] = $request->data['ka_name'];

            $editing_product_category -> save();
        }
    }
    function del_subcategory(Request $request) {
        $deleted_product_category = Product_subcategory::where("id", "=", $request->id)->first();
        $deleted_product_category -> delete();
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
