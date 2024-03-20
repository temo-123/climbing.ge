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

class ProductController extends Controller
{
    public function get_all_products()
    {
        return Product::get();
    }

    public function get_user_products()
    {
        return Auth::user()->products;
    }

    public function get_local_products(Request $request)
    {
        $global_products = product::latest('id')->where('published', '=', 1)->get();
        return $products = ProductService::get_locale_product_use_locale($global_products, $request->lang);
    }

    public function get_local_product_in_page(Request $request)
    {
        $global_product_count = Product::latest('id')->where('published', '=', 1)->where('url_title',strip_tags($request->url_title))->count();

        if ($global_product_count > 0) {
            $global_product = Product::latest('id')->where('published', '=', 1)->where('url_title',strip_tags($request->url_title))->first();
            return $product = ProductService::get_locale_product_in_page_use_locale($global_product, $request->lang);
        }
        else{
            // return abort(404);
            return redirect()->away('https://google.com/');
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

        $validator = Validator::make($data, [
            'global_product.category_id' => 'required',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'validation' => $validator->messages()
            ], 422);
        }
    
        // $tour_adding = ProductService::add_content($data, Tour::class, Locale_tour::class, '_product', $request);
        $tour_adding = ProductService::add_content($data, Product::class, Locale_product::class, '_product', $request);
        
        if (!array_key_exists('validation', $tour_adding->original)) {
            $this->create_product_user_relations($tour_adding);
        }
        else {
            return $tour_adding;
        }
    }

    public function create_product_user_relations($tout_id) {
        $new_user_relatione = new Users_tour;
        $new_user_relatione = $tout_id;
        $new_user_relatione = Auth::user()->id;
        $new_user_relatione -> save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function get_product_editing_data(Request $request)
    {
        $product = product::where('id', '=', $request->product_id)->first();
        $us_product = $product->us_product;
        $ru_product = $product->ru_product;
        $ka_product = $product->ka_product;
        $data = [
            'global_product' => $product,
            'us_product' => $us_product,
            'ru_product' => $ru_product,
            'ka_product' => $ka_product
        ];
        return $data;
        // dd($data);
    }

    public function get_similar_product(Request $request)
    {
        $this_product = product::where('published', '=', 1)->where('id', '=', $request->product_id)->first();
        $this_category = Product_category::where('id', '=', $this_product->category_id)->first();

        $global_products = product::latest('id')->where('published', '=', 1)->where('id', '!=', $request->product_id)->where('category_id', '=', $this_category->id)->limit(3)->get();

        return $products = ProductService::get_locale_product_use_locale($global_products, $request->lang);
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit_product(Request $request)
    {
        $data = $request->data;

        $validator = Validator::make($data, [
            'global_product.category_id' => 'required',
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleted_product = Product::where("id", "=", $id)->first();
        $deleted_product -> delete();
    }

    public function global_product_validation($global_data)
    {
        $validator = Validator::make($global_data, [
            'published' => 'required',
            'us_title_for_url_title' => 'required|unique:products,url_title',
            'sale_type' => 'required',
            'category_id' => 'required',
        ]);
        if ($validator->fails()) {
            return $validator->messages();
        }
    }

    public function editing_global_product_validation($global_data)
    {
        $validator = Validator::make($global_data, [
            'published' => 'required',
            'sale_type' => 'required',
            'category_id' => 'required',
        ]);
        if ($validator->fails()) {
            return $validator->messages();
        }
    }

    public function local_product_validation($data)
    {
        $validator = Validator::make($data, [
            'title' => 'required | max:190',
            'short_description' => 'required',
            'text' => 'required',
        ]);
        if ($validator->fails()) {
            return $validator->messages();
        }
    }
}
