<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Order;
use Auth;

class OrdersController extends Controller
{
    public function user_orders()
    {
        $title = 'My Orders';
        $page_name = $title;
        $table_1_name = $title;
        $my_order_page_utl = 'index';   

        $data = [
            'title'=>$title,

            'table_1_my_order_url'=>$my_order_page_utl,
            'table_1_title'=>'my_order',
            'table_1_pablic' => '',
            'table_1_name'=> $table_1_name,
            
            'page_name' => $page_name,
            'active' => 'my_order',
            'page_route' => 'outdoor_page',
        ];
        return view('user.data_table', $data);
    }

    public function orders_list()
    {
        $data = [
            'title'=>'Orders',

            'table_2_my_order_url'=>'index',
            'table_2_title'=>'my_order',
            'table_2_pablic' => '',
            'table_2_name'=> 'My Orders',

            'table_2_get_route'=>'order/get_user_orders',

            'table_1_order_url'=>'index',
            'table_1_title'=>'Order',
            'table_1_pablic' => '',
            'table_1_name'=> 'Orders',

            'table_1_get_route'=>'order/get_all_orders',
            
            'page_name' => 'Orders',
            'active' => 'order',
            'page_route' => 'outdoor_page',
        ];
        return view('user.data_table', $data);
    }
    
    public function get_all_orders(Request $request)
    {
        return Order::get();
    }

    public function get_user_orders(Request $request)
    {
        return Order::where('user_id', '=', Auth::user()->id)->get();
    }

    public function get_orders_data(Request $request)
    {
        return Order::where('id', '=', $request->id)->first();
    }


    public function cont_shopping(Request $request)
    {
        $title = 'My Orders';
        $page_name = $title;
        $table_1_name = $title;
        $my_order_page_utl = 'index';   

        $data = [
            'title'=>$title,

            'table_1_my_order_url'=>$my_order_page_utl,
            'table_1_title'=>'my_order',
            'table_1_pablic' => '',
            'table_1_name'=> $table_1_name,
            
            'page_name' => $page_name,
            'active' => 'my_order',
            'page_route' => 'outdoor_page',
        ];
        return view('user.cont_shopping', $data);
    }

    public function arrange_order(Request $request)
    {
        # code...
    }
}
