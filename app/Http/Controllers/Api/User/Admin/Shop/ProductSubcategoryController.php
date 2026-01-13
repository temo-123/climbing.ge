<?php

namespace App\Http\Controllers\Api\User\Admin\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;

use App\Models\Shop\Product_subcategory;

class ProductSubcategoryController extends Controller
{
    // function get_all_subcategories(Request $request) {
    //     return Product_subcategory::get();
    // }

    function get_subcategories_for_category(Request $request) {
        return Product_subcategory::where("category_id", "=", $request->category_id,)->get();
    }

    function get_editing_subcategory(Request $request) {
        return Product_subcategory::where("id", "=", $request->id,)->first();
    }

    function add_subcategory(Request $request) {
        $validate = $this->validation($request);

        if ($validate != null) {
            return($validate);
        }
        
        // Validate that category_id is provided and not null
        if (empty($request->id)) {
            return response()->json([
                'error' => 'Category ID is required',
            ], 422);
        }

        $new_product_subcategory = new Product_subcategory;

        $new_product_subcategory['us_name'] = $request->data['us_name'];
        $new_product_subcategory['ka_name'] = $request->data['ka_name'];

        $new_product_subcategory['category_id'] = $request->id;

        $new_product_subcategory -> save();
    }

    function edit_subcategory(Request $request) {
        $validate = $this->validation($request);

        if ($validate != null) {
            return($validate);
        }
        else{
            $editing_product_subcategory = Product_subcategory::where("id", "=", $request->id)->first();

            $editing_product_subcategory['us_name'] = $request->data['us_name'];
            $editing_product_subcategory['ka_name'] = $request->data['ka_name'];

            $editing_product_subcategory -> save();
        }
    }
    
    function del_subcategory(Request $request) {
        $deleted_product_subcategory = Product_subcategory::where("id", "=", $request->id)->first();
        $deleted_product_subcategory -> delete();
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
