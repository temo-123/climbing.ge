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

        // Add quantity to product options
        foreach ($products as $product) {
            if (isset($product['product_option'])) {
                foreach ($product['product_option'] as &$option) {
                    if (isset($option['option'])) {
                        $option['option']['quantity'] = $option['option']->quantity ?? 0;
                    }
                }
            }
        }

        return $products;
    }

    public function get_donation_products(Request $request)
    {
        $global_products = Product::latest('id')->where('published', '=', 1)->where('is_donation_product', '=', 1)->get();
        $products = ProductService::get_locale_product_use_locale($global_products, $request->lang);

        // Add quantity to product options
        foreach ($products as $product) {
            if (isset($product['product_option'])) {
                foreach ($product['product_option'] as &$option) {
                    if (isset($option['option'])) {
                        $option['option']['quantity'] = $option['option']->quantity ?? 0;
                    }
                }
            }
        }

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
        $global_products = product::latest('id')->where('discount', '!=', null)->where('published', '=', 1)->get();
        return $products = ProductService::get_locale_product_use_locale($global_products, $request->lang);
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


    public function add_product(Request $request)
    {
        $data = json_decode($request->data, true );
        
        $validator = Validator::make($data['global_product'], [
            'subcategory_id' => 'required',
            'brand_id' => 'required',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'validation' => $validator->messages()
            ], 422);
        }
    
        // $product_adding = ProductService::add_content($data, product::class, Locale_product::class, '_product', $request);
        $product_adding = ProductService::add_content($data, Product::class, Locale_product::class, '_product', $request);
        
        if (!array_key_exists('validation', $product_adding->original)) {
            $this->create_product_user_relations($product_adding->original['global_product_id']);
        }
        else {
            return $product_adding;
        }
    }

    public function create_product_user_relations($product_id, $user_id = null) {
        $new_user_relatione = new User_product;
        $new_user_relatione['product_id'] = $product_id;
        if($user_id == null){
            $new_user_relatione['user_id'] = Auth::user()->id;
        }
        else{
            $new_user_relatione['user_id'] = $user_id;
        }
        $new_user_relatione -> save();
    }

    public function change_user_relation(Request $request) 
    {
        if($request['data']['old_user_id'] != []){
            $user_relatione = User_product::where('product_id', '=', $request['data']['product_id'])->where('user_id', '=', $request['data']['old_user_id'])->first();
            if($user_relatione != []){
                $user_relatione['user_id'] = $request['data']['new_user_id'];
                $user_relatione -> save();
            }
        }
        else{
            $this->create_product_user_relations($request['data']['product_id'], $request['data']['new_user_id']);
        }
    }
    
    public function get_product_editing_data(Request $request)
    {
        $product = product::where('id', '=', $request->product_id)->first();
        $us_product = $product->us_product;
        // $ru_product = $product->ru_product;
        $ka_product = $product->ka_product;
        $data = [
            'global_product' => $product,
            'us_product' => $us_product,
            // 'ru_product' => $ru_product,
            'ka_product' => $ka_product
        ];
        return $data;
        // dd($data);
    }

    public function get_similar_product(Request $request)
    {
        $this_product = product::where('published', '=', 1)->where('id', '=', $request->product_id)->count();

        if($this_product > 0){
            $this_product = product::where('published', '=', 1)->where('id', '=', $request->product_id)->first();
            if ($this_product->subcategory_id != null) {
                if($this_product->product_subcategory){
                    $product_subcategory = $this_product->product_subcategory;
                    if($product_subcategory->count() > 0){
                        if($product_subcategory->products->count() > 1){
                            if ($product_subcategory->products->count() > 3) {
                                $similar_products = $product_subcategory->products->where('published', '=', 1)->where('id', '!=', $request->product_id)->random(3);
                            }
                            else{
                                $similar_products = $product_subcategory->products->where('published', '=', 1)->where('id', '!=', $request->product_id)->random($product_subcategory->products->count()-1);
                            }
                            return $products = ProductService::get_locale_product_use_locale(array_slice($similar_products->all(), 0, 3), $request->lang);
                        }
                    }
                }
            }
        }
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

    public function edit_product(Request $request)
    {
        // $data = $request->data;
        $data = json_decode($request->data, true );

        $validator = Validator::make($data['global_product'], [
            // 'category_id' => 'required',

            'subcategory_id' => 'required',
            'brand_id' => 'required',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'validation' => $validator->messages()
            ], 422);
        }

        $article_editing = ProductService::edit_content(Product::class, Locale_product::class, '_product', $request);

        if(array_key_exists('validation', $article_editing->original)){
            return $article_editing;
        }
    }

    public function update(Request $request, $id)
    {
        $page_product = Product::latest('id')
                            ->where('published', '=', 1)
                            ->where('id', '=', $id)
                            ->first();

        $global_products = Product::latest('id')
                            ->where('published', '=', 1)
                            ->where('id', '!=', $page_product->id)
                            ->where('url_title', '!=', $request->url_title)
                            ->where('category_id', '=', $page_product->category_id)
                            ->get();
                            
        return ProductService::get_locale_product($global_products);
    }

    public function del_product(Request $request)
    {
        // $deleted_product = Product::where("id", "=", $id)->first();
        // $deleted_product -> delete();

        ProductService::del_content($request->product_id, Product::class, Locale_product::class, '_product', 'image', 'images/product_option_img/');
    }
}
