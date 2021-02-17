<?php

namespace App\Http\Controllers\User\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Product_category;

class CategoryController extends Controller
{
    public function add_product_category()
    {
        # code...
    }

    public function edit_product_category()
    {
        # code...
    }

    public function del_product_category()
    {
        # code...
    }

    public function get_product_category_data()
    {
        return Product_category::latest('id')->get();
    }
}
