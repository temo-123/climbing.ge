<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Product_image;

class CartController extends Controller
{
    public function index()
    {
        $carts = Cart::get();
        $title = 'Cart';
        $page_name = $title;
        $table_1_name = $title;
        $carts_add_url = 'cart_add';
        $cart_add_category = 'event';
        $carts_edit_url = 'cart_edit';
        $cart_page_utl = 'index';   

        $data = [
            'title'=>$title,
            'table_1'=>$carts,

            'table_1_add_url'=>$carts_add_url,
            'table_1_add_category'=>$cart_add_category,
            'table_1_edit_url'=>$carts_edit_url,
            'table_1_cart_url'=>$cart_page_utl,
            'table_1_title'=>'Cart',
            'table_1_pablic' => '',
            'table_1_name'=> $table_1_name,
            
            'page_name' => $page_name,
            'active' => 'Cart',
            'page_route' => 'outdoor_page',
        ];
        return view('user.cart', $data);
    }

    public function update_quantity(Request $request)
    {
        // $cart_item = Cart::where('id',strip_tags($request->id))->get();
        
    }

    public function del_item(Request $request)
    {
        
    }

    function get_products_cart(Request $request)
    {
        
    }

    public function add_to_cart(Request $request)
    {
         
    }
}
