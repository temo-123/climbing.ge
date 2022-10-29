<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;
use App\Models\Product;
use App\Models\Favorite_product;
use App\Models\Cart;
use App\Models\Product_colors;
use App\Models\Product_category;

use App\Services\GetProductsService;
use App\Services\GetProductService;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }


    public function get_local_products(Request $request)
    {
        $global_products = product::latest('id')->where('published', '=', 1)->get();
        return $products = GetProductService::get_locale_product_use_locale($global_products, $request->lang);
    }

    public function get_local_product_in_page(Request $request)
    {
        $global_product = Product::latest('id')->where('published', '=', 1)->where('url_title',strip_tags($request->url_title))->first();
        return $product = GetProductService::get_locale_product_in_page_use_locale($global_product, $request->lang);
    }

    public function get_user_favorite_products()
    {
        if (Auth::user()) {
            $fav_products = Favorite_product::where('user_id', '=', Auth::user()->id)->get();
            $products = array();
            foreach ($fav_products as $fav_product) {
                $global_product = Product::where('id', '=', $fav_product->product_id)->get();
                $product = GetProductService::get_locale_product_use_locale($global_product, 'en');
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
        return GetProductService::get_locale_product_use_locale($global_product, $request->lang);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    public function data()
    {
        # code...
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($url_title)
    {

    }

    public function get_similar_product(Request $request)
    {
        $this_product = product::where('published', '=', 1)->where('id', '=', $request->product_id)->first();
        $this_category = Product_category::where('id', '=', $this_product->category_id)->first();
        $global_products = product::latest('id')->where('published', '=', 1)->where('id', '!=', $request->product_id)->where('category_id', '=', $this_category->id)->get();
        return $products = GetProductService::get_locale_product_use_locale($global_products, $request->lang);
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
    public function edit($id)
    {
        //
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
        $page_product = product::latest('id')
                            ->where('published', '=', 1)
                            ->where('id', '=', $id)
                            ->first();

        $global_products = product::latest('id')
                            ->where('published', '=', 1)
                            ->where('id', '!=', $page_product->id)
                            ->where('url_title', '!=', $request->url_title)
                            ->where('category_id', '=', $page_product->category_id)
                            ->get();
                            
        return $products = GetProductService::get_locale_product($global_products);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
