<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// use Auth;
// use App\Models\Product;
// use App\Models\Favorite_product;
// use App\Services\GetProductsService;
// use App\Services\GetProductService;
// use App\Services\testService;
// use App\Models\Cart;

class IndexController extends Controller
{
    public function index ()
    {
    	if (view()->exists('shop.index')) {
            return view('shop.index');
    	}
    	abort(404);
    }
}
