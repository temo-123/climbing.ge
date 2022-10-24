<?php

namespace App\Services;

use App\Models\Locale_product;
// use App\Models\Ru_product;
// use App\Models\Ka_product;
use App\Models\Product;
use App\Models\Product_image;
use App\Models\Product_option;

// use App\Models\Product_image;

use Carbon\Carbon;

class GetProductService
{
    // public static function get_locale_product($global_product)
    // {
    //     $locale = request()->segment(1, '');

    //     $products = array();

    //     if($locale == "ru"){
    //         foreach ($global_product as $product) {
    //             array_push($products, [
    //                 "global_product"=>$product,
    //                 "locale_product"=>Product::find($product->id)->ru_product,
    //                 "category"=>Product::find($product->id)->product_category,
    //                 "product_images"=>(new static)->get_product_images($product->id),
    //             ]);
    //         }
    //     }
    //     elseif ($locale == "ka") {
    //         foreach ($global_product as $product) {
    //             array_push($products, [
    //                 "global_product"=>$product,
    //                 "locale_product"=>Product::find($product->id)->ka_product,
    //                 "category"=>Product::find($product->id)->product_category,
    //                 "product_images"=>(new static)->get_product_images($product->id),
    //             ]);
    //         }
    //     } 
    //     else {
    //         foreach ($global_product as $product) {
    //             array_push($products, [
    //                 "global_product"=>$product,
    //                 "locale_product"=>Product::find($product->id)->us_product,
    //                 "category"=>Product::find($product->id)->product_category,
    //                 "product_images"=>(new static)->get_product_images($product->id),
    //             ]);
    //         }
    //     }

    //     return $products;
    // }

    public static function get_locale_product_use_locale($global_product, $locale)
    {
        // $locale = request()->segment(1, '');

        $products = array();

        if($locale == "ru"){
            foreach ($global_product as $product) {
                array_push($products, [
                    "global_product"=>$product,
                    "locale_product"=>Product::find($product->id)->ru_product,
                    "category"=>Product::find($product->id)->product_category,
                    "max_price"=>(new static)->get_product_price($product->id, 'max'),
                    "min_price"=>(new static)->get_product_price($product->id, 'min'),
                    "product_images"=>(new static)->get_product_images($product->id),
                ]);
            }
        }
        elseif ($locale == "ka") {
            foreach ($global_product as $product) {
                array_push($products, [
                    "global_product"=>$product,
                    "locale_product"=>Product::find($product->id)->ka_product,
                    "category"=>Product::find($product->id)->product_category,
                    "max_price"=>(new static)->get_product_price($product->id, 'max'),
                    "min_price"=>(new static)->get_product_price($product->id, 'min'),
                    "product_images"=>(new static)->get_product_images($product->id),
                ]);
            }
        } 
        else {
            foreach ($global_product as $product) {
                array_push($products, [
                    "global_product"=>$product,
                    "locale_product"=>Product::find($product->id)->us_product,
                    "category"=>Product::find($product->id)->product_category,
                    "max_price"=>(new static)->get_product_price($product->id, 'max'),
                    "min_price"=>(new static)->get_product_price($product->id, 'min'),
                    "product_images"=>(new static)->get_product_images($product->id),
                ]);
            }
        }

        return $products;
    }


    // public static function get_locale_product_in_page($product)
    // {
    //     $locale = request()->segment(1, '');

    //     $product_data = [];
    //     $product_option = [];
    //     $product_image = [];

    //     if($locale == "ru"){
    //         $ru_product_id = $product->ru_product_id;
    //         $locale_product = Locale_product::where('id', '=', $ru_product_id)->get();
                
    //     }
    //     elseif ($locale == "ka") {
    //         $ka_product_id = $product->ka_product_id;
    //         $locale_product = Locale_product::where('id', '=', $ka_product_id)->get();
                
    //     } else {
    //         $us_product_id = $product->us_product_id;

    //         $locale_product = Locale_product::where('id', '=', $us_product_id)->get();
    //         $global_product = Product::where('us_product_id', '=', $us_product_id)->first();

    //         $options = product_option::where('product_id', '=', $global_product->id)->get();
            
    //         foreach($options as $option){
    //             // foreach($options as $option){
    //                 $product_images = Product_image::where('option_id', '=', $option->id)->get();
    //                 array_push($product_option, [ 
    //                     'option' => $option, 
    //                     'images' => $product_images
    //                 ]);
    //                 // array_push($product_image, $product_images);
    //             // }
    //         }

    //         array_push($product_data, [
    //             'local_product' => $locale_product,
    //             'global_product' => $global_product,
    //             'product_option' => $product_option,
    //             // 'product_images' => $product_images
    //             // 'options' => $options
    //         ]);
    //     }

    //     return $product_data;
    // }

    public static function get_locale_product_in_page_use_locale($product, $locale)
    {
        // $locale = request()->segment(1, '');

        $product_data = [];
        $product_option = [];
        $product_image = [];

        if($locale == "ru"){
            $ru_product_id = $product->ru_product_id;
            $locale_product = Locale_product::where('id', '=', $ru_product_id)->get();
                
        }
        elseif ($locale == "ka") {
            $ka_product_id = $product->ka_product_id;
            $locale_product = Locale_product::where('id', '=', $ka_product_id)->get();
                
        } else {
            $us_product_id = $product->us_product_id;

            $locale_product = Locale_product::where('id', '=', $us_product_id)->get();
            $global_product = Product::where('us_product_id', '=', $us_product_id)->first();

            $options = product_option::where('product_id', '=', $global_product->id)->get();
            
            foreach($options as $option){
                // foreach($options as $option){
                    $product_images = Product_image::where('option_id', '=', $option->id)->get();
                    array_push($product_option, [ 
                        'option' => $option, 
                        'images' => $product_images
                    ]);
                    // array_push($product_image, $product_images);
                // }
            }

            array_push($product_data, [
                'local_product' => $locale_product,
                'global_product' => $global_product,
                'product_option' => $product_option,
                // 'product_images' => $product_images
                // 'options' => $options
            ]);
        }

        return $product_data;
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

    public function colculate_product_discount($old_price, $discount)
    {
        // $old_price = $price->price;
        // $sale = $price->discount;
        // $price_x_sale = $sale * $old_price;
        // $var_1 = $price_x_sale/100;
        // $new_price = $old_price - $var_1;
        
        // return $new_price;
    }

    public function get_product_price($product_id, $interval)
    {
        $options_count = Product_option::where('product_id', '=', $product_id)->count();

        if($options_count){
            $options = Product_option::where('product_id', '=', $product_id)->get('price');
            $prices = [];

            foreach ($options as $option) {
                array_push($prices, $option->price);
            }

            if ($interval == 'max') {
                return max($prices);
            }
            elseif ($interval == 'min') {
                return min($prices);
            }
            else {
                return 'error';
            }
        }
    }

    public function get_product_images($product_id)
    {
        $product_images = [];

        $options = Product_option::where('product_id', '=', $product_id)->get();

        foreach ($options as $option) {
            $images = Product_image::where('option_id', '=', $option -> id)->get();
            foreach ($images as $image) {
                array_push($product_images, $image->image);
            }

        }
        return $product_images;
    }

    public function get_product_images_on_page($option_id)
    {
        $images = Product_image::where('option_id', '=', $option_id)->get();
        return $images;
    }

    public function get_product_options_on_page($product_id)
    {
        $product_options = [];
        $product_images = [];

        $options = Product_option::where('product_id', '=', $product_id)->get();

        foreach ($options as $option) {
            $product_images = (new static)->get_product_images_on_page($option->id);

            array_push($product_options, [
                'product_option' => $option,
                'product_images' => $product_images,
            ]);
        }

        return $product_options;
    }

    // public function get_all_products_min_and_max_price()
    // {
    //     $options = Product_option::get('price');
    //     $min = 0;
    //     $max = 0;
    //     $prices = [];
    //     foreach ($options as $value) {
    //         array_push($prices, $value->price);
    //     }

    //     $min = min($prices);
    //     $max = max($prices);

    //     return ['min_price'=>$min, 'max_price'=>$max];
    // }

    // public function get_product_min_and_max_price($product_id)
    // {
    //     $options = Product_option::where('product_id', '=', $product_id)->get('price');

    //     $min = 0;
    //     $max = 0;
    //     $prices = [];
    //     foreach ($options as $value) {
    //         array_push($prices, $value->price);
    //     }

    //     $min = min($prices);
    //     $max = max($prices);

    //     return ['min_price'=>$min, 'max_price'=>$max];
    // }
}