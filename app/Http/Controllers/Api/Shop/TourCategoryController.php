<?php

namespace App\Http\Controllers\Api\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Shop\Tour_category;

class TourCategoryController extends Controller
{
    public function get_all_categories()
    {
        return Tour_category::latest('id')->get();
    }
    public function get_category($id)
    {
        return Tour_category::where("id", "=", $id)->first();
    }
}
