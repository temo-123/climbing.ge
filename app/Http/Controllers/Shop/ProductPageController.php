<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\Product_image;
use App\Models\Favorite_product;
use App\User;
use App\Services\GetProductsService;
use Auth;

class ProductPageController extends Controller
{
    public function shop_page($name){
        if (view()->exists('shop.product_page')) {
            $global_product = Product::latest('id')->where('url_title',strip_tags($name))->first();
            $product = GetProductsService::get_locale_product_in_page($global_product);

            $product_images = Product_image::latest('id')->where('product_id',strip_tags($global_product->id))->get();
            $first_product_images = Product_image::latest('id')->where('product_id',strip_tags($global_product->id))->first();
            $product_seller = user::where('id', strip_tags($global_product->user_id))->first();

            $othe_products = Product::where('category_id', strip_tags($global_product->category_id))->get();
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
    			
                'image_dir' => 'other_img',
                'price_array'=>$price_array,
                
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
