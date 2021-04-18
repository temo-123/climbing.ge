<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;
use App\Models\Product;
use App\Models\Favorite_product;
use App\Services\GetProductsService;

class IndexController extends Controller
{
    public function index ()
    {
    	if (view()->exists('shop.index')) {
            // $products = product::latest('id')->get();
            $global_products = product::latest('id')->where('published', '=', 1)->get();
            $products = GetProductsService::get_locale_product($global_products);

            $products_count = product::count();

            $price_array = array();
            foreach ($products as $product) {
                if ($product['discount'] != null) {
                    $old_price = $product['price'];
                    $sale = $product['discount'];
                    $price_x_sale = $sale * $old_price;
                    $var_1 = $price_x_sale/100;
                    $new_price = $old_price - $var_1;
                    // dd($products[0]);
                    array_push($price_array, ['product_id'=>$product['id'], 'old_price'=>$old_price, 'sale'=>$sale, 'new_price'=>$new_price]);
                }
                else{
                    array_push($price_array, ['product_id'=>$product['id'], 'old_price'=>$product['price'], 'sale'=>'0', 'new_price'=>$product['price']]);
                }
            }  

            $favorite_products_array = array();
            $favorite_array = array();
            $favorite_products = Favorite_product::get();
            if (Auth::user()) {
                // $num = 0;
                foreach ($products as $product) { 
                    foreach ($favorite_products as $favorite) {
                        if ($product['id'] == $favorite->product_id) {
                            if ($favorite->user_id == Auth::user()->id) 
                                array_push($favorite_products_array, 1);
                        }
                    }
                    if (empty($favorite_products_array)) 
                        array_push($favorite_array, ["product_id"=>$product['id'], "users_favorite"=>0]);
                    
                    else 
                        array_push($favorite_array, ["product_id"=>$product['id'], "users_favorite"=>1]);
                    $favorite_products_array = array();
                }
            }

            // dd($favorite_array);
            // dd($price_array);

    		$data = [
    			'title'=>'Climbing store',
    			'products'=>$products,
                'products_count'=>$products_count,

                'favorite_array'=>$favorite_array,

                'price_array'=>$price_array,
                'loop_num_var' => 0,
                // 'num'=>0,

    			'shop'=>1,
                'num' => 1,
                
                'articles_link' => 'other_page',
                'image_dir' => 'shop_img',
    		];
    		return view('shop.index',$data);
    	}
    	abort(404);
    }
}
