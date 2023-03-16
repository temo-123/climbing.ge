<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Validator;
use Auth;

use App\Services\GetProductsService;
use App\Services\GetProductService;
use App\Services\URLTitleService;

use App\Models\Product;
use App\Models\Locale_product;
use App\Models\Favorite_product;
use App\Models\Cart;
use App\Models\Product_option;
use App\Models\Option_image;
use App\Models\Product_category;

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
        dd('create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validation_issets = [];

        $data = $request->data;

        $ka_validate = $this->local_product_validation($data['ka_data']);
        if ($ka_validate != null) {
            $validation_issets['ka_info_validation'] = $ka_validate;
        }
        else{
            $validation_issets['ka_info_validation'] = false;
        }

        $us_validate = $this->local_product_validation($data['us_data']);
        if ($us_validate != null) {
            $validation_issets['us_info_validation'] = $us_validate;
        }
        else{
            $validation_issets['us_info_validation'] = false;
        }

        $ru_validate = $this->local_product_validation($data['ru_data']);
        if ($ru_validate != null) {
            $validation_issets['ru_info_validation'] = $ru_validate;
        }
        else{
            $validation_issets['ru_info_validation'] = false;
        }

        $global_validate = $this->global_product_validation($data['global_data']);
        if ($global_validate != null) {
            $validation_issets['global_info_validation'] = $global_validate;
        }
        else{
            $validation_issets['global_info_validation'] = false;
        }


        if (
            !$validation_issets['global_info_validation'] && 
            !$validation_issets['ru_info_validation'] && 
            !$validation_issets['ka_info_validation'] && 
            !$validation_issets['us_info_validation']
        ) {
            $saiving_issets['ka_info_status'] = $this->add_locale_product($data['ka_data'], 'ka');
            $saiving_issets['ru_info_status'] = $this->add_locale_product($data['ru_data'], 'ru');
            $saiving_issets['us_info_status'] = $this->add_locale_product($data['us_data'], 'us');

            if (
                $saiving_issets['ka_info_status'] != 'Error' &&
                $saiving_issets['ru_info_status'] != 'Error' &&
                $saiving_issets['us_info_status'] != 'Error'
            ) {
                $action_article_id = $this->addGlobalProduct(
                    $data['global_data'],

                    $saiving_issets['ka_info_status'],
                    $saiving_issets['ru_info_status'],
                    $saiving_issets['us_info_status']                    
                );
            }
        }
        else{            
            return response()->json([
                'Data validation' => $validation_issets
            ], 422);
        }
    }

    public function addGlobalProduct($global_data, $ka_info_id, $ru_info_id, $us_info_id)
    {
        $url_title = URLTitleService::get_url_title($global_data["us_title_for_url_title"]); // make url_title from us_title value

        $new_global_product = new Product;
        
        $new_global_product['url_title'] = $url_title;

        $new_global_product['category_id']=$global_data["category_id"];
        $new_global_product['published']=$global_data["published"];
        $new_global_product['sale_type']=$global_data["sale_type"];
        $new_global_product['mead_in_georgia']=$global_data["mead_in_georgia"];
        $new_global_product['material']=$global_data["material"];

        $new_global_product['us_product_id']=$us_info_id;
        $new_global_product['ka_product_id']=$ka_info_id;
        $new_global_product['ru_product_id']=$ru_info_id;
        
        $new_global_product -> save();
    }

    public function add_locale_product($data, $locale)
    {
        $new_local_product = new Locale_product;
        
        $new_local_product['title']=$data["title"];
        $new_local_product['locale']=$locale;
        $new_local_product['short_description']=$data["short_description"];
        $new_local_product['text']=$data["text"];

        $saved = $new_local_product->save();

        if(!$saved){
            App::abort(500, 'Error');
        }
        else{
            return $new_local_product->id;
        }
    }

    // public function data()
    // {
    //     # code...
    // }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = product::where('id', '=', $id)->first();
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
    public function edit_product_data(Request $request)
    {
        $validation_issets = [];

        $data = $request->data;

        $ka_validate = $this->local_product_validation($data['ka_data']);
        if ($ka_validate != null) {
            $validation_issets['ka_info_validation'] = $ka_validate;
        }
        else{
            $validation_issets['ka_info_validation'] = false;
        }

        $us_validate = $this->local_product_validation($data['us_data']);
        if ($us_validate != null) {
            $validation_issets['us_info_validation'] = $us_validate;
        }
        else{
            $validation_issets['us_info_validation'] = false;
        }

        $ru_validate = $this->local_product_validation($data['ru_data']);
        if ($ru_validate != null) {
            $validation_issets['ru_info_validation'] = $ru_validate;
        }
        else{
            $validation_issets['ru_info_validation'] = false;
        }


        if (
            !$validation_issets['ru_info_validation'] && 
            !$validation_issets['ka_info_validation'] && 
            !$validation_issets['us_info_validation']
        ) {
            $local_products_id = $this->editGlobalProduct(
                $data['global_data'],  
                $request->product_id,              
                $request['change_url_title'],  
                $data['us_data']['title']              
            );

            // dd($data['ka_data'], $local_products_id['ka_product_id']);
            if ($local_products_id != 'Error') {
                $this->edit_locale_product($data['ka_data'], $local_products_id['ka_product_id']);
                $this->edit_locale_product($data['us_data'], $local_products_id['us_product_id']);
                $this->edit_locale_product($data['ru_data'], $local_products_id['ru_product_id']);
            }
        }
        else{            
            return response()->json([
                'Data validation' => $validation_issets
            ], 422);
        }
    }

    public function editGlobalProduct($global_data, $id, $change_url_title, $us_title)
    {
        $edit_global_product = Product::where('id', '=', $id)->first();
        
        if($change_url_title){
            $url_title = URLTitleService::get_url_title($us_title); // make url_title from us_title value
            $edit_global_product['url_title'] = $url_title;
        }

        $edit_global_product['category_id']=$global_data["category_id"];
        $edit_global_product['published']=$global_data["published"];
        $edit_global_product['sale_type']=$global_data["sale_type"];
        $edit_global_product['mead_in_georgia']=$global_data["mead_in_georgia"];
        $edit_global_product['material']=$global_data["material"];
        
        $saved = $edit_global_product -> save();

        if(!$saved){
            App::abort(500, 'Error');
        }
        else {
            $data = [
                'us_product_id' => $edit_global_product->us_product_id,
                'ka_product_id' => $edit_global_product->ka_product_id,
                'ru_product_id' => $edit_global_product->ru_product_id
            ];
            return $data;
        }

    }

    public function edit_locale_product($data, $id)
    {
        $edit_local_product = Locale_product::where('id', '=', $id)->first();
        
        $edit_local_product['title']=$data["title"];
        $edit_local_product['short_description']=$data["short_description"];
        $edit_local_product['text']=$data["text"];

        // dd($edit_local_product);
        $saved = $edit_local_product->save();
        
        if(!$saved){
            App::abort(500, 'Error');
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
                            
        return GetProductService::get_locale_product($global_products);
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
        ]);
        if ($validator->fails()) {
            return $validator->messages();
        }
    }

    public function local_product_validation($data)
    {
        // dd($data);
        $validator = Validator::make($data, [
            'title' => 'required | max:190',
            // 'short_description' => 'required',
            'text' => 'required',
        ]);
        if ($validator->fails()) {
            return $validator->messages();
        }
    }
}
