<?php

namespace App\Http\Controllers\Api\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Validator;
use Auth;

use App\Services\ProductService;
use App\Services\URLTitleService;

use App\Models\Shop\Product;
use App\Models\Shop\Locale_product;
use App\Models\Shop\Favorite_product;
use App\Models\Shop\Cart;
use App\Models\Shop\Product_option;
use App\Models\Shop\Option_image;
use App\Models\Shop\Product_image;
use App\Models\Shop\Product_category;
use App\Models\User\User_product;

class ProductController extends Controller
{
    public function get_all_products()
    {
        $products = Product::latest('id')->get();
        $returned_array = [];
        foreach($products as $product){
            array_push($returned_array, [
                'product' => $product,
                'user' => $product->user->first(),
                'options' => $product->product_options->count()
            ]);
        }
        return $returned_array;
    }

    public function get_products_for_index(Request $request)
    {
        $global_products = Product::latest('id')->where('published', '=', 1)->get();
        $products = ProductService::get_locale_product_use_locale($global_products, $request->lang);

        return $products;
    }

    public function get_user_products()
    {
        $products = Auth::user()->products;
        $returned_array = [];
        foreach($products as $product){
            array_push($returned_array, [
                'product' => $product,
                'options' => $product->product_options->count()
            ]);
        }
        return $returned_array;
    }

    public function get_product_options(Request $request, $product_id)
    {
        $product = Product::where('id', '=', $product_id)->first();
        if ($product) {
            $product_options = $product->product_options;
            $product_option = $product_options->map(function($option) {
                return [
                    'id' => $option->id,
                    'name' => $option->name ?? 'Option ' . $option->id,
                    'price' => $option->price,
                    'quantity' => $option->warehouse->where('general', '=', 1)->first()->pivot->quantity ?? 0,
                    'image' => $option->images->first()->image ?? null,
                ];
            });
            return $product_option;
        } else {
            return response()->json(['error' => 'Product not found'], 400);
        }
    }

    public function get_local_products(Request $request)
    {
        $global_products = Product::latest('id')->where('published', '=', 1)->get();
        $products = ProductService::get_locale_product_use_locale($global_products, $request->lang);

        return $products;
    }

    public function get_donation_products(Request $request)
    {
        $global_products = Product::latest('id')->where('published', '=', 1)->where('is_donation_product', '=', 1)->get();
        $products = ProductService::get_locale_product_use_locale($global_products, $request->lang);

        return $products;
    }

    public function get_current_products()
    {
        $products = Product::where('published', '=', 1)->whereHas('product_options')->with('product_options')->get();
        $returned_products = [];
        foreach($products as $product){
            $locale_product = ProductService::get_locale_product_in_page_use_locale($product, 'en'); // assuming english for admin
            array_push($returned_products, [
                'id' => $product->id,
                'title' => $locale_product['locale_product']->title ?? 'No title',
                'options' => $product->product_options->map(function($option) {
                    return [
                        'id' => $option->id,
                        'name' => $option->name ?? 'Option ' . $option->id,
                        'price' => $option->price,
                        'quantity' => $option->quantity,
                        'images' => $option->images->map(function($image) {
                            return [
                                'id' => $image->id,
                                'image' => $image->image
                            ];
                        })
                    ];
                })
            ]);
        }
        return $returned_products;
    }
    public function get_local_saled_products(Request $request)
    {
        // Get products that have at least one option with a discount, and are published
        $global_products = Product::where('published', '=', 1)
            ->whereHas('product_options', function($query) {
                $query->where('discount', '>', 0);
            })
            ->latest('id')
            ->get();
        
        return ProductService::get_locale_product_use_locale($global_products, $request->lang);
    }

    public function get_local_product_in_page(Request $request)
    {
        $global_product_count = Product::latest('id')->where('url_title',strip_tags($request->url_title))
                                                                ->where(
                                                                function($query) {
                                                                    return $query
                                                                            ->where('published', '=', 2)
                                                                            ->orWhere('published', '=', 1);
                                                                    })
                                                                ->count();

        if ($global_product_count > 0) {
            $global_product = Product::latest('id')->where('url_title',strip_tags($request->url_title))
                                                                ->where(
                                                                function($query) {
                                                                    return $query
                                                                            ->where('published', '=', 2)
                                                                            ->orWhere('published', '=', 1);
                                                                    })
                                                                ->with(['product_options.warehouse'])
                                                                ->first();
            return $product = ProductService::get_locale_product_in_page_use_locale($global_product, $request->lang);
        }
        else{
            return abort(404);
            // return redirect()->away('https://google.com/');
        }
    }

    public function get_user_favorite_products()
    {
        if (Auth::user()) {
            $fav_products = Favorite_product::where('user_id', '=', Auth::user()->id)->get();
            $products = array();
            foreach ($fav_products as $fav_product) {
                $global_product = Product::where('id', '=', $fav_product->product_id)->get();
                $product = ProductService::get_locale_product_use_locale($global_product, 'en');
                array_push($products, $product[0]);
            }
            return $products;
        }
        else{
            return 'Plees login!';
        }
    }

    public function get_quick_product(Request $request)
    {
        $global_product = Product::where('id', '=', $request->product_id)->get();
        return ProductService::get_locale_product_use_locale($global_product, $request->lang);
    }

    public function get_similar_product(Request $request)
    {
        $target  = 4;
        $lang    = $request->lang;
        $current = (int) $request->product_id;

        $this_product = Product::where('published', 1)->where('id', $current)->first();
        if (!$this_product) return [];

        $excluded = collect([$current]);
        $pool     = collect();

        // 1 — same subcategory
        if ($this_product->subcategory_id) {
            $sub = Product::where('published', 1)
                ->where('subcategory_id', $this_product->subcategory_id)
                ->whereNotIn('id', $excluded)
                ->inRandomOrder()
                ->limit($target)
                ->get();
            $pool     = $pool->merge($sub);
            $excluded = $excluded->merge($sub->pluck('id'));
        }

        // 2 — same brand (fill remaining slots)
        if ($pool->count() < $target && $this_product->brand_id) {
            $need  = $target - $pool->count();
            $brand = Product::where('published', 1)
                ->where('brand_id', $this_product->brand_id)
                ->whereNotIn('id', $excluded)
                ->inRandomOrder()
                ->limit($need)
                ->get();
            $pool     = $pool->merge($brand);
            $excluded = $excluded->merge($brand->pluck('id'));
        }

        // 3 — random published (final fallback)
        if ($pool->count() < $target) {
            $need   = $target - $pool->count();
            $latest = Product::where('published', 1)
                ->whereNotIn('id', $excluded)
                ->inRandomOrder()
                ->limit($need)
                ->get();
            $pool = $pool->merge($latest);
        }

        return ProductService::get_locale_product_use_locale(
            $pool->take($target)->all(),
            $lang
        );
    }

    public function get_product_gallery($product_id)
    {
        return Product_image::where('product_id', $product_id)->get();
    }

    public function get_product_price_interval()
    {
        $options = Product_colors::get();

        $option_price = [];
        foreach($options as $option){
            array_push($option_price, $option->price);
        }
        // max(array(28, 36, 87, 12));
        // return $min_price = min($option_price);
        return $max_price = max($option_price);
    }

}
