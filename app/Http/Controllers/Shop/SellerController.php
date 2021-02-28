<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\Favorite_product;
use App\User;

class SellerController extends Controller
{
    public function seller_page($name){
        if (!$name) {
            abort(404);
        }
        if (view()->exists('shop.seller_page')) {
            $seller = user::latest('id')->where('id',strip_tags($name))->first();
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

            // if (Auth::user()) {
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
            // }
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

            return view('shop.seller_page',$data);
        }
        else{
            abort(404);
        }
    }
}
