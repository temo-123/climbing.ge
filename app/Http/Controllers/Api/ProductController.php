<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;
use App\Models\Product;
use App\Models\Favorite_product;
use App\Services\GetProductsService;
use App\Services\GetProductService;
use App\Models\Cart;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $global_products = product::latest('id')->where('published', '=', 1)->get();
        return $products = GetProductService::get_locale_product($global_products);
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
        // dd($url_title);
        $global_product = Product::latest('id')->where('published', '=', 1)->where('url_title',strip_tags($url_title))->first();
        return $product = GetProductService::get_locale_product_in_page($global_product);
    }

    // public function get_similar_product(Request $request)
    // {
        
    // }

    public function get_product_price_interval()
    {
        $options = product_options::get();

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
