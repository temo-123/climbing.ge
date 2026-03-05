<?php

namespace App\Http\Controllers\Api\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;

use App\Models\Shop\Product_brand;
use App\Models\Shop\Locale_brand;

class ProductBrandController extends Controller
{
    function get_all_brands(Request $request) {
        $brands = Product_brand::get();
        $returned_brands = [];
        foreach ($brands as $brand) {
            array_push($returned_brands, [
                'global_brand' => $brand,
                'us_brand' => $brand->us_brand
            ]);
        }
        return $returned_brands;
    }
    function get_brand(Request $request) {
        $brand = Product_brand::where("id", "=", $request->id)->first();
        $brand->us_brand;
        $brand->ka_brand;
        return $brand;
    }
   
}
