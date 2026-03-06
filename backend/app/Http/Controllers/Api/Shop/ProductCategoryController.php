<?php

namespace App\Http\Controllers\Api\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Shop\Product_category;
use App\Models\Shop\product_options;

use Validator;

class ProductCategoryController extends Controller
{
    public function get_all_product_category()
    {
        return $categories = Product_category::latest('id')->get();
    }

    public function get_category($id)
    {
        return Product_category::where("id", "=", $id)->first();
    }

}
