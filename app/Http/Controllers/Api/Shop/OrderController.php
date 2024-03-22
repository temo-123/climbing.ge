<?php

namespace App\Http\Controllers\Api\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;
use Notification;

use App\Models\User;
use App\Models\User\User_adreses;
use App\Models\Shop\Order;
use App\Models\Shop\Order_products;
use App\Models\Shop\Order_status;

use App\Models\Shop\Product_option;
use App\Models\Shop\Product;
use App\Models\Shop\Cart;
use App\Models\Shop\Site;
use App\Models\Shop\Sale_code;

use App\Services\ProductService;

use App\Notifications\order\OrderConfirm;
use App\Notifications\order\AdminOrderDeclorationNotification;
use App\Notifications\order\OrderStatusNotification;

use App\Notifications\order\castam_prodaction\SelerCastamProdactionNotification;
use App\Notifications\order\castam_prodaction\BuyerCastamProdactionNotification;

class OrderController extends Controller
{
    public function index()
    {
        return Order::get();
    }

    public function get_user_orders()
    {
        $user_products = Auth::user()->products->first();
        
        if($user_products->count() > 0){
            return $user_products->orders;
        }
        return [];
    }

    public function get_user_purchules() {
        return Auth::user()->purchases;
    }

    public function get_order_status($order_id)
    {
        return Order_status::where("order_id", "=", $order_id)->first();
    }

    public function castam_prodaction_message(Request $request) {
        if(Auth::user()){
            $actyve_product = Product::where('id', '=', $request->product_id)->first();
            $actyve_product_user = $actyve_product->user->first();

            $actyve_local_product = ProductService::get_locale_product_in_page_use_locale($actyve_product, 'us')['locale_product'];

            $info = [
                "product_name" => $actyve_local_product->title,
                "messaged_user_name" => Auth::user()->name . ' ' . Auth::user()->surname,
                "messaged_user_email" => Auth::user()->email,
                "product_id" => $request->product_id,
                "number" => $request->form_data['number'],
                "message" => $request->form_data['text']
            ];

            Notification::route('mail', $actyve_product_user->email)->notify(new SelerCastamProdactionNotification($info));
            Notification::route('mail', Auth::user()->email)->notify(new BuyerCastamProdactionNotification());

            return 'Thank you for message!';
        }
        return 'Plees login';
    }

    public function store(Request $request)
    {
        // dd($request->shiping);
        if (Auth::user()) {
            $new_order = new Order;
            
            $new_order['user_id'] = Auth::user()->id;
            $new_order['confirm'] = null;
            $new_order['adres_id'] = $request->adres;
            $new_order['shiping'] = $request->shiping;

            $new_order['payment'] = $request->payment_tupe;
            $new_order -> save();

            foreach ($request->order_product_list as $product) {
                $new_order_product_item = new Order_products;
                $new_order_product_item['order_id'] = $new_order->id;
                $new_order_product_item['product_id'] = $product['product']['id'];
                $new_order_product_item['product_option_id'] = $product['option']['id'];
                $new_order_product_item['quantity'] = $product['quantity'];

                $new_order_product_item -> save();

                //subtraction number of products
                (new static)->subtraction_products($product['option']['id'], $product['quantity']);
            }

            (new static)->create_order_status($new_order->id);

            //del cart items
            (new static)->del_cart_items(Auth::user()->id);
            // dd($new_order->id);
            // $order_id = ;
            //send mail to orderd user
            (new static)->send_order_confirm_mail_to_user($new_order->id);
        }
        else{
            return 'Plees login';
        }
    }

    public function del_cart_items($user_id)
    {
        $cart_items = Cart::where('user_id', '=', $user_id)->get();
        foreach ($cart_items as $item) {
            $cart_item = Cart::where('id', '=', $item->id)->first();
            $cart_item ->delete();
        }
        return 'All items is deleted';
    }

    public function subtraction_products($option_id, $qut)
    {
        $option_item = Product_option::where('id', '=', $option_id)->first();
        $new_qut;

        if($option_item['quantity'] >= $qut){
            $new_qut = $option_item['quantity'] - $qut;
        }
        else{
            $new_qut = 0;
        }

        $option_item['quantity'] = $new_qut;
        $option_item -> save();
    }

    public function send_order_mail_ot_admin()
    {   
        $order_info = array(  
            'name' => Auth::user()->name,
            'surname' => Auth::user()->surname,
            'user_id' => Auth::user()->id,
        );
        $site_data = Site::first();
        // dd($site_data);
        Notification::send($site_data, new AdminOrderDeclorationNotification($order_info));
    }

    public function check_sale_code(Request $request)
    {
        // dd($request);
        $codes_count = Sale_code::where('code', '=', $request->serching_code)->count();
        if($codes_count >= 1){
            return Sale_code::where('code', '=', $request->serching_code)->first();
        }
        else{
            return "Sale code dint fined";
        }
    }

    public function send_order_confirm_mail_to_user($order_id)
    {
        // dd($order_id);
        $user_order_info = array(
            'name' => Auth::user()->name,
            'surname' => Auth::user()->surname,
            'user_id' => Auth::user()->id,

            'order_id' => $order_id,
        );
        
        Notification::send(Auth::user(), new OrderConfirm($user_order_info));
    }

    public function get_order_products($order_id)
    {
        // return Order_products::where("order_id", "=", $request->order_id)->get();
        if (Auth::user()) {
            $product_items = Order_products::where("order_id", "=", $order_id)->get();
            $products = array();
            foreach ($product_items as $item) {

                $option = Product_option::where('id', strip_tags($item->product_option_id))->get();

                foreach ($option as $opt) {
                    $product = Product::where('id', strip_tags($opt->product_id))->get();

                    array_push($products, [
                        "product"=>$product[0],
                        "option"=>$option[0],
                        "quantity"=>$item->quantity,
                    ]);
                }

                // array_push($products, [$option]);
            }
            // dd($products);
            return $products;
        }
    }

    public function get_order_detals(Request $request)
    {
        $order = Order::where("id", "=", $request->order_id)->first();
        $order_status = Order_status::where("order_id", "=", $order->id)->first();
        $order_products = (new static)->get_order_products($order->id);

        $order_detals = [
            'order' => $order,
            'order_status' => $order_status,
            'order_products' => $order_products
        ];

        return $order_detals;
    }

    public function order_is_confirm(Request $request)
    {
        $order = Order::where("id", "=", $request->order_id)->first();
        $order['confirm'] = 1;
        $order->update();

        //send mail to admin
        (new static)->send_order_mail_ot_admin();
    }
    public function is_order_confirm(Request $request)
    {
        $order = Order::where("id", "=", $request->order_id)->first();
        // dd($order->confirm);
        $status;
        if($order->confirm == 1){
            $status = false;
        }
        else{
            $status = true;
        }

        return $status;
    }

    public function create_order_status($order_id)
    {
        // dd($order_id);
        $add_order_status = new Order_status;
        
        $add_order_status['order_id'] = $order_id;
        $add_order_status['status'] = 'treatment';
        $add_order_status['status_updating_data'] = date("Y-m-d H:I:s");
        
        $add_order_status->save();
    }

    public function edit_order_status(Request $request)
    {
        $editing_order_status = Order_status::where("order_id", "=", $request->order_id)->first();
        
        if ($editing_order_status) {
            $editing_order_status['status'] = $request->status;
            $editing_order_status['status_updating_data'] = date("Y-m-d H:I:s");

            $editing_order_status->update();

            $order = Order::where("id", "=", $editing_order_status->order_id)->first();

            (new static)->order_status_notification($request->status, date("Y-m-d H:I:s"), $order->user_id, $editing_order_status->order_id);
        }
        else{
            (new static)->create_order_status($request->order_id);
        }
    }


    public function order_status_notification($status, $data_time, $user_id, $order_id)
    {
        $action_user = User::where("id", "=", $user_id)->first();
        $order_status_info = array(  
            'status' => $status,
            'data_time' => $data_time,
            'order_id' => $order_id,
        );
        Notification::send($action_user, new OrderStatusNotification($order_status_info));
    }

    public function get_activ_order(Request $request)
    {
        return Order::where("id", "=", $request->order_id)->first();
    }

    function randomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

}
