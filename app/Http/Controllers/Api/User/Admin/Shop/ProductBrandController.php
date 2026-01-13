<?php

namespace App\Http\Controllers\Api\User\Admin\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;

use App\Models\Shop\Product_brand;
use App\Models\Shop\Locale_brand;

class ProductBrandController extends Controller
{
    // function get_all_brands(Request $request) {
    //     $brands = Product_brand::get();
    //     $returned_brands = [];
    //     foreach ($brands as $brand) {
    //         array_push($returned_brands, [
    //             'global_brand' => $brand,
    //             'us_brand' => $brand->us_brand
    //         ]);
    //     }
    //     return $returned_brands;
    // }
    function get_editing_brand(Request $request) {
        $brand = Product_brand::where("id", "=", $request->id)->first();
        $brand->us_brand;
        $brand->ka_brand;
        return $brand;
    }
    function create_brand(Request $request) {
        $validation_issets = [];

        $ka_validate = (new static)->local_content_validate($request->data['ka_brand']);
        if ($ka_validate != null) {
            $validation_issets['ka_info_validation'] = $ka_validate;
        }
        else{
            $validation_issets['ka_info_validation'] = false;
        }

        $us_validate = (new static)->local_content_validate($request->data['us_brand']);
        if ($us_validate != null) {
            $validation_issets['us_info_validation'] = $us_validate;
        }
        else{
            $validation_issets['us_info_validation'] = false;
        }

        if (
            !$validation_issets['ka_info_validation'] && 
            !$validation_issets['us_info_validation']
        ) {
            $global_data['us_brand_id'] = $this->add_locale_content($request->data['us_brand'], 'us');
            $global_data['ka_brand_id'] = $this->add_locale_content($request->data['ka_brand'], 'ka');

            $saved = Product_brand::insertGetId($global_data); 

            if(!$saved){
                App::abort(500, 'Error');
            }
            else{
                return $saved;
            }
        }
        else{            
            return response()->json([
                'validation' => $validation_issets
            ], 422);
        }
    }

    private static function add_locale_content($data, $locale)
    {
        $data['locale']=$locale;

        $saved = Locale_brand::insertGetId($data);
        
        if(!$saved){
            App::abort(500, 'Error');
        }
        else{
            return $saved;
        }
    }
    function edit_brand(Request $request) {
        $validation_issets = [];

        // $data = json_decode($request->data, true );

        $ka_validate = (new static)->local_content_validate($request->data['ka_brand']);
        if ($ka_validate != null) {
            $validation_issets['ka_info_validation'] = $ka_validate;
        }
        else{
            $validation_issets['ka_info_validation'] = false;
        }

        $us_validate = (new static)->local_content_validate($request->data['us_brand']);
        if ($us_validate != null) {
            $validation_issets['us_info_validation'] = $us_validate;
        }
        else{
            $validation_issets['us_info_validation'] = false;
        }

        if (
            !$validation_issets['ka_info_validation'] && 
            !$validation_issets['us_info_validation']
        ) {
            $editing_brand = Product_brand::where('id', '=', $request->id)->first();
            
            $this->edit_locale_content($editing_brand->us_brand->id, $request->data['us_brand']);
            $this->edit_locale_content($editing_brand->ka_brand->id, $request->data['ka_brand']);
        }
        else{            
            return response()->json([
                'validation' => $validation_issets
            ], 422);
        }
    }
    function edit_locale_content($id, $data) {
        $editing_locale_content = Locale_brand::where('id', '=', $id)->first();
        
        $saved = $editing_locale_content->update($data);

        if(!$saved){
            // App::abort(500, 'Error');
            return 'Error';
        }
        else{
            return $editing_locale_content->id;
        }
    }
    function del_brand(Request $request) {
        $deleted_product_brand = Product_brand::where("id", "=", $request->id)->first();

        $us_deleted_product_brand = $deleted_product_brand->us_brand;
        $ka_deleted_product_brand = $deleted_product_brand->ka_brand;

        $deleted_product_brand -> delete();
        $us_deleted_product_brand -> delete();
        $ka_deleted_product_brand -> delete();
    }

    private static function local_content_validate($data)
    {
        $validator = Validator::make($data, [
            'title' => 'required | max:190',
            'short_description' => 'required',
        ]);

        if ($validator->fails()) {
            return $validator->messages();
        }
    }
}
