<?php

namespace App\Http\Controllers\Api\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;

use App\Models\Shop\Product_subcategory;

class ProductSubcategoryController extends Controller
{
    function get_all_subcategories(Request $request) {
        return Product_subcategory::get();
    }

    function get_subcategories_for_category(Request $request) {
        return Product_subcategory::where("category_id", "=", $request->category_id,)->get();
    }

    function get_subcategory(Request $request) {
        return Product_subcategory::where("id", "=", $request->id,)->first();
    }

}
