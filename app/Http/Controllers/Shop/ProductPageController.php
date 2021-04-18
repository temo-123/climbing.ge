<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\Product_image;
use App\Models\Favorite_product;
use App\Models\Product_category;

use App\User;
use App\Services\GetProductsService;

use Auth;

class ProductPageController extends Controller
{
    public function shop_page($name){
        if (view()->exists('shop.product_page')) {
            $global_product = Product::latest('id')->where('url_title',strip_tags($name))->first();
            $product = GetProductsService::get_locale_product_in_page($global_product);

            $category = Product_category::where('id',strip_tags($global_product -> category_id))->first();

            $product_images = Product_image::latest('id')->where('product_id',strip_tags($global_product->id))->get();
            $first_product_images = Product_image::latest('id')->where('product_id',strip_tags($global_product->id))->first();
            $product_seller = user::where('id', strip_tags($global_product->user_id))->first();

            // $othe_products = Product::where('category_id', strip_tags($global_product->category_id))->get();
            // $othe_products = Product::get();
            $othe_products_count = product::latest('id')->where('published', '=', 1)->where('category_id', '=', $global_product->category_id)->where('id', '!=', $global_product->id)->limit(3)->count();
            if ($othe_products_count == 0) {
                $global_othe_products = product::latest('id')->where('published', '=', 1)->where('id', '!=', $global_product->id)->limit(3)->get();
            }
            else{
                $global_othe_products = product::latest('id')->where('published', '=', 1)->where('category_id', '=', $global_product->category_id)->where('id', '!=', $global_product->id)->limit(3)->get();
            }
            $othe_products = GetProductsService::get_locale_product($global_othe_products);
            $other_product_price_array = array();
            foreach ($othe_products as $other_product) {
                if ($other_product['discount'] != null) {
                    $old_price = $other_product['price'];
                    $sale = $other_product['discount'];
                    $price_x_sale = $sale * $old_price;
                    $var_1 = $price_x_sale/100;
                    $new_price = $old_price - $var_1;
                    // dd($products[0]);
                    array_push($other_product_price_array, ['product_id'=>$other_product['id'], 'old_price'=>$old_price, 'sale'=>$sale, 'new_price'=>$new_price]);
                }
                else{
                    array_push($other_product_price_array, ['product_id'=>$other_product['id'], 'old_price'=>$other_product['price'], 'sale'=>'0', 'new_price'=>$other_product['price']]);
                }
            }  

            // dd($othe_products);

            if (Auth::user()) {
                $favorite_products = Favorite_product::where('product_id', strip_tags($global_product->id))->where('user_id', strip_tags(Auth::user()->id))->get();
            }
            if (empty($favorite_products[0])) {
                $favorite = 0;
            }
            else $favorite = 1;

            $price_array = array();
            
            if ($global_product->discount != null) {
                $old_price = $global_product->price;
                $sale = $global_product->discount;
                $price_x_sale = $sale * $old_price;
                $var_1 = $price_x_sale/100;
                $new_price = $old_price - $var_1;
                array_push($price_array, [$global_product->id, $new_price]);
                $x = 1;
            }

            $data  = [
                'title'=>$product[0]->title,
                'product'=>$product[0],
                'othe_products'=>$othe_products,
                'product_seller'=>$product_seller,
                'favorite'=>$favorite,
                'shop'=>1,
                
                'global_product' => $global_product,
                "category" => $category,
    			
                'image_dir' => 'other_img',
                'price_array'=>$price_array,
                'other_product_price_array'=>$other_product_price_array,
                
                'product_images'=>$product_images,
                'first_product_images'=>$first_product_images,
                
                'slider_link'=>'../../assets/img/other_img/slider_img/',
                'all_article_but'=>'other_list',
                'article_map'=>'Other',
                
                'article_edit_link'=>'otherEdit',
                'zoom_num' => 0,
                
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
