<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\Favorite_product;
use App\User;

class ProductPageController extends Controller
{
    public function shop_page($name){
        if (!$name) {
            abort(404);
        }
        if (view()->exists('shop.product_page')) {
            $product = product::latest('id')->where('url_title',strip_tags($name))->first();
            $product_seller_id = $product->user_id;
            $product_id = $product->id;
            $seller = user::where('id', '=', $product_seller_id)->get();

            $product_seller_array = array();
            foreach ($seller as $sel) {
                array_push($product_seller_array, $sel);
            }
            $product_seller = $product_seller_array[0];
            $othe_products = product::latest('id')->where('category', '=', $product->category)->get();

            // if (Auth::user()) {
            //     $favorite_products = Favorite_product::where('product_id', '=', $product->id)->where('user_id', '=', Auth::user()->id)->get();
            // }
            if (empty($favorite_products[0])) {
                $favorite = 0;
            }
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

            return view('shop.product_page',$data);
        }
        else{
            abort(404);
        }
    }
}
