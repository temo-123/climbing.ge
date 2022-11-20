<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

use App\Models\User;
use App\Models\Order;
use App\Models\Order_products;

use App\Models\Product_option;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Site;
use App\Models\User_adreses;
use App\Models\Sale_code;

// use Mail;
// use Illuminate\Notifications\Notifiable;
use Notification;
use App\Notifications\order\OrderConfirm;
use App\Notifications\order\AdminOrderDeclorationNotification;
use App\Notifications\order\OrderStatusNotification;

class OrderController extends Controller
{
    // use Notifiable;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Order::get();
    }

    public function get_my_orders()
    {
        $user_id = auth()->user()->id;
        return Order::where("user_id", "=", $user_id)->get();
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
        // dd($request->shiping);
        if (Auth::user()) {
            $new_order = new Order;
            
            $new_order['user_id'] = Auth::user()->id;
            $new_order['confirm'] = null;
            $new_order['adres_id'] = $request->adres;
            $new_order['shiping'] = $request->shiping;

            $new_order['treatment'] = 1;
            $new_order['preparation_for_shipment'] = null;
            $new_order['ready_to_ship'] = null;
            $new_order['order_has_been_sent'] = null;
            $new_order['transferred_to_the_delivery_service'] = null;
            $new_order['delivered'] = null;

            $new_order['treatment_data'] = date("Y-m-d H:I:s");
            $new_order['preparation_for_shipment_data'] = null;
            $new_order['ready_to_ship_data'] = null;
            $new_order['order_has_been_sent_data'] = null;
            $new_order['transferred_to_the_delivery_service_data'] = null;
            $new_order['delivered_data'] = null;

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
        //
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

    public function get_order_products(Request $request)
    {
        // return Order_products::where("order_id", "=", $request->order_id)->get();
        if (Auth::user()) {
            $product_items = Order_products::where("order_id", "=", $request->order_id)->get();
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
        return Order::where("id", "=", $request->order_id)->first();
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

    public function edit_order_status(Request $request)
    {
        // dd(date("Y-m-d H:I:s"));
        $editing_order = Order::where("id", "=", $request->order_id)->first();
        
        if($request->status == 'Treatment'){
            $editing_order['treatment'] = 1;
            $editing_order['treatment_data'] = date("Y-m-d H:I:s");

            $editing_order['preparation_for_shipment'] = null;
            $editing_order['preparation_for_shipment_data'] = null;

            $editing_order['ready_to_ship'] = null;
            $editing_order['ready_to_ship_data'] = null;
            
            $editing_order['order_has_been_sent'] = null;
            $editing_order['order_has_been_sent_data'] = null;
            
            $editing_order['transferred_to_the_delivery_service'] = null;
            $editing_order['transferred_to_the_delivery_service_data'] = null;

            $editing_order['delivered'] = null;
            $editing_order['delivered_data'] = null;
        }
        else if($request->status == 'Preparation for shipment'){
            $editing_order['preparation_for_shipment'] = 1;
            $editing_order['preparation_for_shipment_data'] = date("Y-m-d H:I:s");

            $editing_order['ready_to_ship'] = null;
            $editing_order['ready_to_ship_data'] = null;
            
            $editing_order['order_has_been_sent'] = null;
            $editing_order['order_has_been_sent_data'] = null;
            
            $editing_order['transferred_to_the_delivery_service'] = null;
            $editing_order['transferred_to_the_delivery_service_data'] = null;

            $editing_order['delivered'] = null;
            $editing_order['delivered_data'] = null;
        }
        else if($request->status == 'Ready to ship'){
            if(!$editing_order['preparation_for_shipment']){
                $editing_order['preparation_for_shipment'] = 1;
                $editing_order['preparation_for_shipment_data'] = date("Y-m-d H:I:s");
            }
            $editing_order['ready_to_ship'] = 1;
            $editing_order['ready_to_ship_data'] = date("Y-m-d H:I:s");
            
            $editing_order['order_has_been_sent'] = null;
            $editing_order['order_has_been_sent_data'] = null;
            
            $editing_order['transferred_to_the_delivery_service'] = null;
            $editing_order['transferred_to_the_delivery_service_data'] = null;

            $editing_order['delivered'] = null;
            $editing_order['delivered_data'] = null;
        }
        else if($request->status == 'Order has been sent'){
            if(!$editing_order['preparation_for_shipment']){
                $editing_order['preparation_for_shipment'] = 1;
                $editing_order['preparation_for_shipment_data'] = date("Y-m-d H:I:s");
            }
            if(!$editing_order['ready_to_ship']){
                $editing_order['ready_to_ship'] = 1;
                $editing_order['ready_to_ship_data'] = date("Y-m-d H:I:s");
            }
            $editing_order['order_has_been_sent'] = 1;
            $editing_order['order_has_been_sent_data'] = date("Y-m-d H:I:s");
            
            $editing_order['transferred_to_the_delivery_service'] = null;
            $editing_order['transferred_to_the_delivery_service_data'] = null;

            $editing_order['delivered'] = null;
            $editing_order['delivered_data'] = null;
        }
        else if($request->status == 'Transferred to the delivery service'){
            if(!$editing_order['preparation_for_shipment']){
                $editing_order['preparation_for_shipment'] = 1;
                $editing_order['preparation_for_shipment_data'] = date("Y-m-d H:I:s");
            }
            if(!$editing_order['ready_to_ship']){
                $editing_order['ready_to_ship'] = 1;
                $editing_order['ready_to_ship_data'] = date("Y-m-d H:I:s");
            }
            if(!$editing_order['order_has_been_sent']){
                $editing_order['order_has_been_sent'] = 1;
                $editing_order['order_has_been_sent_data'] = date("Y-m-d H:I:s");
            }
            $editing_order['transferred_to_the_delivery_service'] = 1;
            $editing_order['transferred_to_the_delivery_service_data'] = date("Y-m-d H:I:s");

            $editing_order['delivered'] = null;
            $editing_order['delivered_data'] = null;
        }
        else if($request->status == 'Delivered'){
            if(!$editing_order['preparation_for_shipment']){
                $editing_order['preparation_for_shipment'] = 1;
                $editing_order['preparation_for_shipment_data'] = date("Y-m-d H:I:s");
            }
            if(!$editing_order['ready_to_ship']){
                $editing_order['ready_to_ship'] = 1;
                $editing_order['ready_to_ship_data'] = date("Y-m-d H:I:s");
            }
            if(!$editing_order['order_has_been_sent']){
                $editing_order['order_has_been_sent'] = 1;
                $editing_order['order_has_been_sent_data'] = date("Y-m-d H:I:s");
            }
            if(!$editing_order['transferred_to_the_delivery_service']){
                $editing_order['transferred_to_the_delivery_service'] = 1;
                $editing_order['transferred_to_the_delivery_service_data'] = date("Y-m-d H:I:s");
            }
            $editing_order['delivered'] = 1;
            $editing_order['delivered_data'] = date("Y-m-d H:I:s");
        }

        $editing_order->update();

        (new static)->order_status_notification($request->status, date("Y-m-d H:I:s"), $editing_order->user_id, $editing_order->id);
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
