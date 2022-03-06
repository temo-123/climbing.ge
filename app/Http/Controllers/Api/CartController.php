<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Cart;
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
        // $request->user()->authorizeRoles(['user', 'manager', 'admin','ka_manager','ru_manager','us_manager','seller']);
dd(Auth::guest());
        if (Auth::user()) {
            $user = Auth::user();
            $cart_items = Cart::where('user_id', '=', $user->id)->get();
            $products = array();
            $product_image = '';
            foreach ($cart_items as $cart_item) {

                $prod = Product::where('id', strip_tags($cart_item->product_id))->first();
                // echo $prod;
                $images = Product_image::where('product_id', strip_tags($prod->id))->get();
                $image_count = Product_image::where('product_id', strip_tags($prod->id))->count();

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
                
                // echo $product_image;

                array_push($products, [
                    "id"=>$cart_item->id,
                    "quantity"=>$cart_item->quantity,
                    "product_image" => $product_image,

                    Product::where('id', '=', $cart_item->product_id)->get()
                ]);
            }
            return $products;
        }
        // else {
        //     return 'ples login';
        // }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $request->user()->authorizeRoles(['user', 'manager', 'admin','ka_manager','ru_manager','us_manager','seller']);

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
        dd(Auth::user());
        $cart = new Cart;
            
        $cart['option_id'] = $request->modification_id;
        $cart['quantity'] = $request->quantity;
        $cart['user_id'] = Auth::user()->id;
        // $cart['user_id'] = 1;

        $cart -> save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ($request->isMethod('delete') || $request->isMethod('post')) {
            $item = cart::where('id',strip_tags($request->id))->first();
            $item -> delete();
        }
    }
}
