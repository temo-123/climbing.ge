<?php

namespace App\Http\Controllers\Api\User\Admin\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;
use Notification;

use App\Models\User;
use App\Models\Site;

use App\Models\User\User_adreses;
use App\Models\Shop\Order;
use App\Models\Shop\Order_products;
use App\Models\Shop\Order_status;

use App\Models\Shop\Product_option;
use App\Models\Shop\Product;
use App\Models\Shop\Cart;
use App\Models\Shop\Sale_code;

use App\Services\ProductService;
use App\Services\PermissionService;

use App\Notifications\order\OrderConfirm;
use App\Notifications\order\AdminOrderDeclorationNotification;
use App\Notifications\order\OrderStatusNotification;

use App\Notifications\order\castam_prodaction\SelerCastamProdactionNotification;
use App\Notifications\order\castam_prodaction\BuyerCastamProdactionNotification;

class OrderController extends Controller
{
    public function get_all_orders()
    {
        $auth = PermissionService::authorize('order', 'edit_order_status');
        // if ($auth) return $auth;
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
        $user = Auth::user();
        if (!$user) {
            return [];
        }
        
        // Get user's product IDs directly from the pivot table to avoid ambiguous column issue
        $userProductIds = \DB::table('user_products')
            ->where('user_id', $user->id)
            ->pluck('product_id');
        
        // Get all orders through user's products using the order_products pivot table
        $orders = Order::whereHas('products', function ($query) use ($userProductIds) {
            $query->whereIn('product_id', $userProductIds);
        })->get();
        
        return $orders;
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

    public function create_order(Request $request)
    {
        if (Auth::user()) {
            // Validate stock before creating order
            foreach ($request->order_product_list as $product) {
                $option = Product_option::find($product['option']['id']);
                if (!$option || $option->quantity < $product['quantity']) {
                    return response()->json(['error' => 'Not enough stock for ' . $product['option']['name']], 400);
                }
            }

            $new_order = new Order;

            $new_order['user_id'] = Auth::user()->id;
            // $new_order['adres_id'] = $request->adres;
            $new_order['shiping'] = $request->shiping;

            $new_order['payment'] = $request->payment_tupe;

            $data['confirm'] = null;
            $data['status'] = null;
            $data['status_updating_data'] = null;

            $saved = $new_order -> save();

            if(!$saved){
                App::abort(500, 'Error');
            }
            else{
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
            }
            (new static)->del_cart_items(Auth::user()->id);

            (new static)->send_order_confirm_mail_to_user($new_order->id);
        }
        else{
            return 'Plees login';
        }
    }

    public function add_custom_order(Request $request)
    {
        // Validate stock before creating custom order, unless production task is enabled
        if (!$request->create_production_task) {
            foreach ($request->order_product_list as $product) {
                $option = Product_option::find($product['product_option_id']);
                $warehouse_quantity = $option->warehouse->where('general', '=', 1)->first()->pivot->quantity ?? 0;
                if (!$option || $warehouse_quantity < $product['quantity']) {
                    return response()->json(['error' => 'Not enough stock for option ID ' . $product['product_option_id']], 400);
                }
            }
        }

        // Admin adds custom order for themselves (active user)
        $new_order = new Order;

        $new_order['user_id'] = auth()->user()->id;
        // $new_order['adres_id'] = $request->adres_id ?? null; // Optional address
        $new_order['shiping'] = $request->delivery_type;
        $new_order['payment'] = $request->payment_type;
        // Optional fields
        $new_order['phone_number'] = $request->phone_number ?? null;
        $new_order['email'] = $request->email ?? null;

        $new_order['confirm'] = 1; // Auto confirm for admin added orders
        $new_order['status'] = 'pending';
        $new_order['status_updating_data'] = date("Y-m-d H:I:s");

        $saved = $new_order->save();

        if(!$saved){
            return response()->json(['error' => 'Error saving order'], 500);
        }
        else{
            foreach ($request->order_product_list as $product) {
                $new_order_product_item = new Order_products;
                $new_order_product_item['order_id'] = $new_order->id;
                $new_order_product_item['product_id'] = $product['product_id'];
                $new_order_product_item['product_option_id'] = $product['product_option_id'];
                $new_order_product_item['quantity'] = $product['quantity'];

                $new_order_product_item->save();

                //subtraction number of products from warehouse only if not production task
                if (!$request->create_production_task) {
                    (new static)->subtraction_products_from_warehouse($product['product_option_id'], $product['quantity']);
                }
            }

            // Send notification to user
            (new static)->send_order_confirm_mail_to_user($new_order->id);
        }

        // If production task is enabled, create production tasks for the options
        if ($request->create_production_task) {
            foreach ($request->order_product_list as $product) {
                // Logic to create production task (placeholder)
                // You can add code here to create a production task for the option
                // For example, insert into a production_tasks table or notify relevant parties
            }
        }

        return response()->json(['message' => 'Custom order added successfully', 'order_id' => $new_order->id]);
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

    public function subtraction_products_from_warehouse($option_id, $qut)
    {
        $option_item = Product_option::where('id', '=', $option_id)->first();
        $warehouse = $option_item->warehouse->where('general', '=', 1)->first();
        if ($warehouse) {
            $current_quantity = $warehouse->pivot->quantity;
            $new_quantity = max(0, $current_quantity - $qut);
            $warehouse->pivot->quantity = $new_quantity;
            $warehouse->pivot->save();
        }
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
        // $order_status = Order_status::where("order_id", "=", $order->id)->first();
        $order_products = (new static)->get_order_products($order->id);

        $order_detals = [
            'order' => $order,
            // 'order_status' => $order_status,
            'order_products' => $order_products
        ];

        return $order_detals;
    }

    public function order_is_confirm(Request $request)
    {
        $auth = PermissionService::authorize('order', 'edit_order_status');
        // if ($auth) return $auth;

        $order = Order::where("id", "=", $request->order_id)->first();
        $order['confirm'] = 1;
        $order['status'] = 'treatment';
        $order['status_updating_data'] = date("Y-m-d H:I:s");
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
        $auth = PermissionService::authorize('order', 'edit_order_status');
        // if ($auth) return $auth;

        $editing_order_status = Order::where("id", "=", $request->order_id)->first();
        $editing_order_status['status'] = $request->status;
        $editing_order_status['status_updating_data'] = date("Y-m-d H:I:s");
        $editing_order_status->update();

        (new static)->order_status_notification($request->status, date("Y-m-d H:I:s"), $order->user_id, $editing_order_status->order_id);
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

    // function randomString($length = 10) {
    //     $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    //     $charactersLength = strlen($characters);
    //     $randomString = '';
    //     for ($i = 0; $i < $length; $i++) {
    //         $randomString .= $characters[rand(0, $charactersLength - 1)];
    //     }
    //     return $randomString;
    // }

    public function get_order_statistics(Request $request)
    {
        $period = $request->route('period');

        $startDate = null;
        switch($period){
            case '30days':
                $startDate = now()->subDays(30);
                break;
            case '1month':
                $startDate = now()->subMonth();
                break;
            case '3months':
                $startDate = now()->subMonths(3);
                break;
            case '6months':
                $startDate = now()->subMonths(6);
                break;
            case '1year':
                $startDate = now()->subYear();
                break;
            case '2years':
                $startDate = now()->subYears(2);
                break;
            case '3years':
                $startDate = now()->subYears(3);
                break;
            case 'all':
                // no start date
                break;
            default:
                return response()->json(['error' => 'Invalid period'], 400);
        }

        $query = \App\Models\Shop\Order::query();
        if($startDate){
            $query->where('created_at', '>=', $startDate);
        }

        if(in_array($period, ['30days', '1month'])){
            $orders = $query->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            $data = [['Day', 'Orders']];
            $i = 1;
            foreach($orders as $order){
                $data[] = [$i, (int)$order->count];
                $i++;
            }
        } else {
            $orders = $query->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
                ->groupByRaw('YEAR(created_at), MONTH(created_at)')
                ->orderByRaw('YEAR(created_at), MONTH(created_at)')
                ->get();

            $data = [['Month', 'Orders']];
            $i = 1;
            foreach($orders as $order){
                $data[] = [$i, (int)$order->count];
                $i++;
            }
        }

        return response()->json($data);
    }

}
