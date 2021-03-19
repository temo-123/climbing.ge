<?php

namespace App\Services;

use App\Models\Us_product;
use App\Models\Ru_product;
use App\Models\Ka_product;

use App\Models\Product_image;

use Carbon\Carbon;

class GetProductsService
{
    public static function get_locale_product($global_product)
    {
        $locale = request()->segment(1, '');

        $products = array();

        if($locale == "ru"){
            foreach ($global_product as $product) {
                $product_images = Product_image::where('product_id', '=', $product->id)->first();
                $image = array();
                if ($product_images != NULL) {
                    $image = $product_images->image;
                }

                $ru_products = Ru_product::where('id', '=', $product->ru_product_id,)->get();
                foreach ($ru_products as $ru_product) {
                    if ($ru_product->id == $product->ru_product_id) {
                        array_push($products, [$ru_products,    
                                                                "id"=>$product->id,
                                                                "url_title"=>$product->url_title, 
                                                                "price"=>$product->price, 
                                                                "currency"=>$product->currency, 
                                                                "discount"=>$product->discount, 
                                                                "category_id"=>$product->category_id, 
                                                                "user_id"=>$product->user_id,
                                                                "image"=>$image
                                                                ]);
                                                                
                    }
                }
                $image = array();
            }
        }
        elseif ($locale == "ka") {
            foreach ($global_product as $product) {
                $product_images = Product_image::where('product_id', '=', $product->id)->first();
                $image = array();
                if ($product_images != NULL) {
                    $image = $product_images->image;
                }

                $ka_products = Ka_product::where('id', '=', $product->ka_product_id,)->get();
                foreach ($ka_products as $ka_product) {
                    if ($ka_product->id == $product->ka_product_id) {
                        array_push($products, [$ka_products,    
                                                                "id"=>$product->id,
                                                                "url_title"=>$product->url_title, 
                                                                "price"=>$product->price, 
                                                                "currency"=>$product->currency, 
                                                                "discount"=>$product->discount, 
                                                                "category_id"=>$product->category_id, 
                                                                "user_id"=>$product->user_id,
                                                                "image"=>$image
                                                                ]);
                                                                
                    }
                }
                $image = array();
            }
        } 
        else {
            foreach ($global_product as $product) {
                $product_images = Product_image::where('product_id', '=', $product->id)->first();
                $image = array();
                if ($product_images != NULL) {
                    $image = $product_images->image;
                }

                $us_products = Us_product::where('id', '=', $product->us_product_id,)->get();
                foreach ($us_products as $us_product) {
                    if ($us_product->id == $product->us_product_id) {
                        array_push($products, [$us_products,    
                                                                "id"=>$product->id,
                                                                "url_title"=>$product->url_title, 
                                                                "price"=>$product->price, 
                                                                "currency"=>$product->currency, 
                                                                "discount"=>$product->discount, 
                                                                "category_id"=>$product->category_id, 
                                                                "user_id"=>$product->user_id,
                                                                "image"=>$image
                                                                ]);
                                                                
                    }
                }
                $image = array();
            }
        }

        return $products;
    }


    public static function get_locale_product_in_page($product)
    {
        $locale = request()->segment(1, '');

        if($locale == "ru"){
            $ru_product_id = $product->ru_product_id;
            $locale_product = Ru_product::where('id', '=', $ru_product_id)->get();
                
        }
        elseif ($locale == "ka") {
            $ka_product_id = $product->ka_product_id;
            $locale_product = Ka_product::where('id', '=', $ka_product_id)->get();
                
        } else {
            $us_product_id = $product->us_product_id;
            $locale_product = Us_product::where('id', '=', $us_product_id)->get();       
        }

        return $locale_product;
    }


    public static function get_new_product_pin($global_product)
    {
        $time_array = array();

        foreach ($global_product as $product) {
            if ($product[0][0]->created_at->lt(Carbon::now()->subDays(30))){
                $time = 0;
                array_push($time_array, ['id'=>$product[0][0]->id, 'name'=>$product['url_title'], 'time'=>$time]);
            } else {
                $time = 1;
                array_push($time_array, ['id'=>$product[0][0]->id, 'name'=>$product['url_title'], 'time'=>$time]);
            }
        }
        
        return $time_array;
    }
}