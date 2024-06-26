<?php

namespace App\Http\Controllers\Api\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Shop\Cart;
use App\Models\Shop\Product;
use App\Models\Shop\Option_image;
use App\Models\Shop\Product_option;
use App\Models\Shop\Favorite_product;
use App\Models\User\User_adreses;
use auth;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()) {
            $user = Auth::user();
            $cart_items = Cart::where('user_id', '=', $user->id)->get();
            $products = array();
            $product_image = '';
            foreach ($cart_items as $cart_item) {

                $option = Product_option::where('id', strip_tags($cart_item->option_id))->get();

                foreach ($option as $opt) {
                    $product = Product::where('id', strip_tags($opt->product_id))->get();
                    // dd($product);
                
                    $images = Option_image::where('option_id', strip_tags($opt->id))->get();
                    $image_count = Option_image::where('option_id', strip_tags($opt->id))->count();
    
                    foreach($images as $image){
                        if ($image_count == 1) {
                            $product_image = $image->image;
                        }
                        if ($image_count == 1 && $image->general_image == NULL) {
                            # code...
                        } 
                        else {
                            # code...
                        }
                    }

                    array_push($products, [
                        "id"=>$cart_item->id,
                        "user_id"=>$cart_item->user_id,
                        "product"=>$product[0],
                        "option"=>$option[0],
                        "quantity"=>$cart_item->quantity,
                        "product_image" => $product_image,
                    ]);
                
                    // dd($products);
                    // echo $product_image;
                }
            }
            
            return $products;
        }
    }

    public function update_quantity(Request $request)
    {
        $cart_item = Cart::where('id', '=', $request->item_id)->first();
        
        $cart_item['quantity'] = $request->quantity;
        
        $cart_item -> save();
    }

    public function add_to_favorite(Request $request)
    {
        if (Auth::user()) {
            
            if(Favorite_product::where('user_id', '=', Auth::user()->id)->where('product_id', '=', $request->product_id)->count() > 0){
                $editing_faworit = Favorite_product::where('user_id', '=', Auth::user()->id)->where('product_id', '=', $request->product_id)->first();

                $editing_faworit['user_id'] = Auth::user()->id;
                $editing_faworit['product_id'] = $request->product_id;
                
                $editing_faworit -> save();
            }
            else{
                $faworit = new Favorite_product();
            
                $faworit['user_id'] = Auth::user()->id;
                $faworit['product_id'] = $request->product_id;
                
                $faworit -> save();
            }
        }
        else{
            dd('Gaajvi');
        }
    }

    public function del_from_favorite(Request $request)
    {
        if (Auth::user()) {
            $product = Favorite_product::where('user_id', '=', Auth::user()->id)->where('product_id', '=', $request->product_id)->first();

            if ($product) {
                $product -> delete();
            }
            else {
                dd('err');
            }
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $request->user()->authorizeRoles(['user', 'manager', 'admin','ka_manager','us_manager','seller']);

        if (Auth::user()) {
            $old_cart_products = Cart::where('user_id', strip_tags(Auth::user()->id))->get();
            $old_cart_products_count = Cart::where('user_id', strip_tags(Auth::user()->id))->count();
            $is_update_cart_item = true;
            if ($old_cart_products_count > 0) {
                foreach ($old_cart_products as $old_cart_product) {
                    if ($old_cart_product->product_id == $request->id) {
                        $editing_cart_item = Cart::where('user_id', strip_tags(Auth::user()->id))->where('product_id', strip_tags($request->id))->first();
                        $quantity = $old_cart_product->quantity + 1;
                        $editing_cart_item['quantity'] = $quantity;
                        $editing_cart_item -> update();

                        $is_update_cart_item = false;
                    }
                }
                if($is_update_cart_item) {
                    $cart = new Cart();
        
                    $cart['product_id'] = $request->id;
                    $cart['user_id'] = Auth::user()->id;
                    $cart['quantity'] = 1;
        
                    $cart -> save();
                }
            }
            else {
                $cart = new Cart();
    
                $cart['product_id'] = $request->id;
                $cart['user_id'] = Auth::user()->id;
                $cart['quantity'] = 1;
    
                $cart -> save();
            }
        }
        else {
            return 'login';
        } 
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $cart_item = Cart::find($request->id);
        $cart_item->quantity = $request->quantity;
        $cart_item->update();
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
        $cart_item = Cart::where('user_id', '=', Auth::user()->id)->where('option_id', '=', $request->modification_id)->first();
        if($cart_item){
            $item_quantyty = $cart_item->quantity;
            $new_quantity;

            $option_item = Product_option::where('id', '=', $cart_item->option_id)->first();
            if($option_item -> quantity <= $item_quantyty + $request->quantity){
                $new_quantity = $option_item -> quantity;
            }
            else{
                $new_quantity = $item_quantyty + $request->quantity;
            }
         
            $cart_item['quantity'] = $new_quantity;

            $cart_item -> save();   
        }
        else{
            $cart = new Cart;
                
            $cart['option_id'] = $request->modification_id;
            $cart['quantity'] = $request->quantity;
            $cart['user_id'] = Auth::user()->id;
    
            $cart -> save();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        if ($request->isMethod('delete')) {
            $item = cart::where('id', '=', $id)->first();
            $item -> delete();
        }
    }
}
