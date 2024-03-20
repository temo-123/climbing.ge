<?php

namespace App\Services;

use App\Models\Shop\Locale_product;
use App\Models\Shop\Product;
use App\Models\Shop\Option_image;
use App\Models\Shop\Product_option;

use App\Services\Abstract\LocaleContentService;

use Carbon\Carbon;

class ProductService extends LocaleContentService
{
    public static function get_locale_product_use_locale($global_product, $locale = 'en')
    {
        $reponce = array();

        $products = (new static)->get_locale_content_use_locale($global_product, Locale_product::class, '_product_id', $locale);

        foreach ($products as $product) {
            if($product['global_data']->product_options->count() > 0){

                $old_min_price = (new static)->get_product_price($product['global_data'], 'min');
                $old_max_price = (new static)->get_product_price($product['global_data'], 'max');

                if($product['global_data']->discount != null || $product['global_data']->discount > 0){
                    array_push($reponce, [
                        "global_product"=>$product['global_data'],
                        "locale_product"=>$product['locale_data'],
                        // "category"=>$product['global_data']->product_category,
                        "max_price"=>$old_max_price,
                        "min_price"=>$old_min_price,

                        "reviews"=>ReitingService::colculate_stars($product['global_data']->feedbacks),

                        "new_max_price"=>(new static)->colculate_product_discount($old_max_price, $product['global_data']->discount),
                        "new_min_price"=>(new static)->colculate_product_discount($old_min_price, $product['global_data']->discount),

                        "product_images"=>(new static)->get_product_images($product['global_data']),
                    ]);
                }
                else{
                    array_push($reponce, [
                        "global_product"=>$product['global_data'],
                        "locale_product"=>$product['locale_data'],
                        // "category"=>$product['global_data']->product_category,
                        "max_price"=>(new static)->get_product_price($product['global_data'], 'max'),
                        "min_price"=>(new static)->get_product_price($product['global_data'], 'min'),
                        "product_images"=>(new static)->get_product_images($product['global_data']),
                    ]);
                }
            }
        }
        
        return $reponce;
    }

    public static function get_locale_product_in_page_use_locale($product, $locale)
    {
        $product_data = [];
        $product_option = [];

        $product = (new static)->get_locale_content_in_page($product, Locale_product::class, '_product_id', $locale);

        $options = product_option::where('product_id', '=', $product['global_data']->id)->get();
        
        foreach($options as $option){
            $product_image = [];
            $product_images = Option_image::where('option_id', '=', $option->id)->get();
            array_push($product_option, [ 
                'option' => $option, 
                'images' => $product_images
            ]);
        }

        $old_min_price = (new static)->get_product_price($product['global_data'], 'min');
        $old_max_price = (new static)->get_product_price($product['global_data'], 'max');
        
        if($product['global_data']->discount != null || $product['global_data']->discount > 0){
            array_push($product_data, [
                "global_product"=>$product['global_data'],
                "locale_product"=>$product['locale_data'],
                "max_price"=>$old_max_price,
                "min_price"=>$old_min_price,

                "reviews"=>ReitingService::colculate_stars($product['global_data']->feedbacks),

                "new_max_price"=>(new static)->colculate_product_discount($old_max_price, $product['global_data']->discount),
                "new_min_price"=>(new static)->colculate_product_discount($old_min_price, $product['global_data']->discount),

                'product_option' => $product_option,
            ]);
        }
        else{
            array_push($product_data, [
                "global_product"=>$product['global_data'],
                "locale_product"=>$product['locale_data'],
                "max_price"=>(new static)->get_product_price($product['global_data'], 'max'),
                "min_price"=>(new static)->get_product_price($product['global_data'], 'min'),
                'product_option' => $product_option,
            ]);
        }

        return $product_data[0];
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

    public static function colculate_product_discount($old_price, $discount)
    {
        $price_x_sale = $discount * $old_price;
        $var_1 = $price_x_sale/100;
        return $old_price - $var_1;
    }

    public static function get_product_price($product, $interval)
    {
        // dd($product->product_options->count());
        $options_count = $product->product_options->count();

        if($options_count){
            $options = $product->product_options;
            $prices = [];

            foreach ($options as $option) {
                array_push($prices, $option->price);
            }

            if ($interval == 'max') {
                return max($prices);
            }
            else if ($interval == 'min') {
                return min($prices);
            }
            else {
                return 'error';
            }
        }
    }

    public static function get_product_images($product)
    {
        $product_images = [];

        $options = $product->product_options;

        foreach ($options as $option) {
            $images = Option_image::where('option_id', '=', $option -> id)->get();
            foreach ($images as $image) {
                array_push($product_images, $image->image);
            }

        }
        return $product_images;
    }
    

    public static function get_product_options_on_page($product_id)
    {
        $product_options = [];
        $product_images = [];

        $options = Product_option::where('product_id', '=', $product_id)->get();

        foreach ($options as $option) {
            $product_images = Option_image::where('option_id', '=', $option->id)->get();

            array_push($product_options, [
                'product_option' => $option,
                'product_images' => $product_images,
            ]);
        }

        return $product_options;
    }
}