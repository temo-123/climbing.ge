<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\Favorite_product;
use Auth;

class IndexController extends Controller
{
    public function index ()
    {
    	if (view()->exists('shop.index')) {
    		$products = product::latest('id')->get();
            $products_count = product::count();
            $favorite_products = Favorite_product::get();

            $tags = product::get();
            $tags_count = product::count();

            $price_array = array();
            foreach ($products as $product) {
                if ($product->discount != null) {
                    $old_price = $product->price;
                    $sale = $product->discount;
                    $price_x_sale = $sale * $old_price;
                    $var_1 = $price_x_sale/100;
                    $new_price = $old_price - $var_1;
                    // dd($products[0]);
                    array_push($price_array, ['product_id'=>$product->id, 'old_price'=>$old_price, 'sale'=>$sale, 'new_price'=>$new_price]);
                }
                else{
                    array_push($price_array, ['product_id'=>$product->id, 'old_price'=>$product->price, 'sale'=>'0', 'new_price'=>$product->price]);
                }
            }  

            $favorite_products_array = array();
            $favorite_array = array();
            if (Auth::user()) {
                // $num = 0;
                foreach ($products as $product) { 
                    foreach ($favorite_products as $favorite) {
                        if ($product->id == $favorite->product_id) {
                            if ($favorite->user_id == Auth::user()->id) 
                                array_push($favorite_products_array, 1);
                        }
                    }
                    if (empty($favorite_products_array)) 
                        array_push($favorite_array, ["product_id"=>$product->id, "users_favorite"=>0]);
                    
                    else 
                        array_push($favorite_array, ["product_id"=>$product->id, "users_favorite"=>1]);
                    $favorite_products_array = array();
                }
            }
            // dd($favorite_array);

    		$data = [
    			'title'=>'Shop',
    			'products'=>$products,
                'products_count'=>$products_count,
                'tags'=>$tags,
                'tags_count'=>$tags_count,

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

	public function shop_page($name){
        if (!$name) {
            abort(404);
        }
        if (view()->exists('blog.article_page')) {
            $product = product::latest('id')->where('url_title',strip_tags($name))->first();
            $product_seller_id = $product->user_id;
            $product_id = $product->id;
            $seller = \App\user::where('id', '=', $product_seller_id)->get();

            $product_seller_array = array();
            foreach ($seller as $sel) {array_push($product_seller_array, $sel);}
            $product_seller = $product_seller_array[0];
            $othe_products = product::latest('id')->where('category', '=', $product->category)->get();

            if (Auth::user()) $favorite_products = Favorite_product::where('product_id', '=', $product->id)->where('user_id', '=', Auth::user()->id)->get();
            if (empty($favorite_products[0])) $favorite = 0;
            else $favorite = 1;

            $price_array = array();
            // dd($product->discount);
            if ($product->discount != null) {
                $old_price = $product->price;
                $sale = $product->discount;
                $price_x_sale = $sale * $old_price;
                $var_1 = $price_x_sale/100;
                $new_price = $old_price - $var_1;
                // dd($products[0]);
                array_push($price_array, [$product->id, $new_price]);
                $x = 1;
            }

            $data  = [
                'title'=>$product->title,
                'product'=>$product,
                'othe_products'=>$othe_products,
                'product_seller'=>$product_seller,
                'favorite'=>$favorite,
    			'shop'=>1,
    			
                'image_dir' => 'other_img',
                'price_array'=>$price_array,
                
                // 'articles_gallery'=>$article_gallery,
                // 'comments'=>$comments,
                
                'slider_link'=>'../../assets/img/other_img/slider_img/',
                'all_article_but'=>'other_list',
                'article_map'=>'Other',
                
                'article_edit_link'=>'otherEdit',
                
                // 'other_article'=>$other_outdoors_list,
                'other_article_link'=>'other_page',
                'other_article_img'=>'assets/img/other_img/',
            ];

            return view('blog.shop_page',$data);
        }
        else{
            abort(404);
        }
    }

	public function seller_page($name){
        if (!$name) {
            abort(404);
        }
        if (view()->exists('blog.article_page')) {
            $seller = \App\user::latest('id')->where('id',strip_tags($name))->first();
            $seller_id = $seller ->id;
            $seler_products = product::where('user_id', '=', $seller_id)->get();
            $seler_products_count = product::where('user_id', '=', $seller_id)->count();

            $price_array = array();
            foreach ($seler_products as $product) {
                if ($product->discount != null) {
                    $old_price = $product->price;
                    $sale = $product->discount;
                    $price_x_sale = $sale * $old_price;
                    $var_1 = $price_x_sale/100;
                    $new_price = $old_price - $var_1;
                    // dd($products[0]);
                    array_push($price_array, ['product_id'=>$product->id, 'old_price'=>$old_price, 'sale'=>$sale, 'new_price'=>$new_price]);
                }
                else{
                    array_push($price_array, ['product_id'=>$product->id, 'old_price'=>$product->price, 'sale'=>'0', 'new_price'=>$product->price]);
                }
            }    

            $favorite_products = Favorite_product::get();
            $favorite_products_array = array();
            $favorite_array = array();

            if (Auth::user()) {
                foreach ($seler_products as $product) { 
                    foreach ($favorite_products as $favorite) {
                        if ($product->id == $favorite->product_id) {
                            if ($favorite->user_id == Auth::user()->id) 
                                array_push($favorite_products_array, 1);
                        }
                    }
                    if (empty($favorite_products_array)) 
                        array_push($favorite_array, ["product_id"=>$product->id, "users_favorite"=>0]);
                    
                    else 
                        array_push($favorite_array, ["product_id"=>$product->id, "users_favorite"=>1]);
                    $favorite_products_array = array();
                }
            }
            // dd($favorite_array);

            $data  = [
                'seller'=>$seller,
                'products'=>$seler_products,
                'seler_products_count'=>$seler_products_count,
    			'shop'=>1,
                'favorite_products'=>$favorite_products,
                'favorite_products_array'=>$favorite_products_array,
    			
                'image_dir' => 'other_img',
                'price_array'=>$price_array,
                'loop_num_var' => 0,
                
                'favorite_array'=>$favorite_array,
                // 'articles_gallery'=>$article_gallery,
                // 'comments'=>$comments,
                
                'slider_link'=>'../../assets/img/other_img/slider_img/',
                'all_article_but'=>'other_list',
                'article_map'=>'Other',
                
                'article_edit_link'=>'otherEdit',
                
                // 'other_article'=>$other_outdoors_list,
                'other_article_link'=>'other_page',
                'other_article_img'=>'assets/img/other_img/',
            ];

            return view('blog.seller_page',$data);
        }
        else{
            abort(404);
        }
    }
}
