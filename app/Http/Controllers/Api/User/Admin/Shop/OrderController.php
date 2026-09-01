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

use App\Models\Shop\Product_option;
use App\Models\Shop\Product;
use App\Models\Shop\Cart;
use App\Models\Shop\Sale_code;
use App\Models\Shop\Shiped_region;

use App\Models\PartnerOrganization\PartnerOrganizationMember;

use App\Services\ProductService;
use App\Services\PermissionService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

use App\Notifications\order\OrderConfirm;
use App\Notifications\order\AdminOrderDeclorationNotification;
use App\Notifications\order\OrderStatusNotification;

use App\Notifications\order\castam_prodaction\SelerCastamProdactionNotification;
use App\Notifications\order\castam_prodaction\BuyerCastamProdactionNotification;

class OrderController extends Controller
{
    public function get_all_orders()
    {
        if ($auth = PermissionService::authorize('order', 'show')) return $auth;
        return Order::latest()->get();
    }

    public function get_user_orders()
    {
        if ($auth = PermissionService::authorize('order', 'show')) return $auth;
        $user_products = Auth::user()->products->first();
        
        if($user_products->count() > 0){
            return $user_products->orders;
        }
        return [];
    }

    public function get_user_purchules() {
        $user = Auth::user();
        if (!$user) return [];

        $orders = Order::where('user_id', $user->id)
            ->with(['orderProducts.option.product'])
            ->latest()
            ->get();

        return $orders->map(function ($order) {
            $has_produced = $order->orderProducts->contains(
                fn($op) => $op->option?->product?->sale_type === 'produced_by_order'
            );
            $data = $order->toArray();
            $data['delivery_days'] = $has_produced ? '5-9' : '2-4';
            return $data;
        });
    }

    public function get_order_status($order_id)
    {
        if ($auth = PermissionService::authorize('order', 'show')) return $auth;
        return Order::where("id", "=", $order_id)->select('id', 'status', 'status_updating_data')->first();
    }

    public function castam_prodaction_message(Request $request) {
        if ($auth = PermissionService::authorize('order', 'edit')) return $auth;
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
        // Self-checkout: a customer placing their own order is not an admin
        // action and must never be gated behind the 'order'>'add' permission
        // (the default 'user' role — every registered customer — has no
        // permissions at all, so that gate would block all storefront
        // checkouts). Compare add_custom_order(), which IS an admin action.
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Please login'], 401);
        }

        if (empty($request->order_product_list) || !is_array($request->order_product_list)) {
            return response()->json(['error' => 'Cart is empty'], 400);
        }

        // The delivery address must belong to the buyer — otherwise a user
        // could pass another user's address id.
        $address = User_adreses::where('id', $request->adres)->where('user_id', $user->id)->first();
        if (!$address) {
            return response()->json(['error' => 'Invalid delivery address'], 400);
        }

        // Shipping cost is recalculated server-side from the address' region —
        // the client-sent value is display-only and must never be trusted for money.
        $shipping_cost = (new static)->resolve_shipping_cost($address, $request->order_product_list);

        $partner_discount = (float) ((new static)->resolve_partner_discount($user->id) ?? 0);

        $sale_code = null;
        if (!empty($request->sale_code)) {
            $sale_code = (new static)->resolve_valid_sale_code($request->sale_code);
        }
        $sale_code_discount = $sale_code ? (float) $sale_code->discount : 0;
        // Discounts don't stack — take whichever benefits the customer most.
        $discount = max($partner_discount, $sale_code_discount);

        try {
            $new_order = DB::transaction(function () use ($request, $user, $address, $shipping_cost, $discount, $sale_code) {
                foreach ($request->order_product_list as $product) {
                    $option = Product_option::with(['warehouse', 'product'])->find($product['option']['id']);
                    if (!$option) {
                        throw new \RuntimeException('Not enough stock for: ' . ($product['option']['name'] ?? $product['option']['id']));
                    }

                    $stock = ProductService::get_option_stock_quantity($option);

                    // Made-to-order products with no general-warehouse stock are produced
                    // on demand, so they're never blocked by the stock check. If the
                    // warehouse DOES carry quantity, they're bought like a normal
                    // online_order option and the usual stock check still applies.
                    $is_unlimited_made_to_order = $stock <= 0
                        && optional($option->product)->sale_type === 'produced_by_order';

                    if (!$is_unlimited_made_to_order) {
                        // Atomically checks and decrements stock in one statement so two
                        // concurrent checkouts for the last unit can't both succeed.
                        if (!(new static)->reserve_stock($option, (int) $product['quantity'])) {
                            throw new \RuntimeException('Not enough stock for: ' . ($product['option']['name'] ?? $product['option']['id']));
                        }
                    }
                }

                $new_order = new Order;
                $new_order['user_id'] = $user->id;
                $new_order['adres_id'] = $address->id;
                $new_order['shiping'] = $shipping_cost;
                $new_order['payment'] = $request->payment_tupe;
                $new_order['status'] = 'pending';
                $new_order['status_updating_data'] = now();
                $new_order['discount'] = $discount;

                if (!$new_order->save()) {
                    throw new \RuntimeException('Failed to create order');
                }

                foreach ($request->order_product_list as $product) {
                    $option = Product_option::find($product['option']['id']);
                    $price = $option ? floatval($option->price) : null;

                    $item = new Order_products;
                    $item['order_id'] = $new_order->id;
                    $item['product_id'] = $product['product']['id'];
                    $item['product_option_id'] = $product['option']['id'];
                    $item['quantity'] = $product['quantity'];
                    // Snapshot the price paid — order_products.price must never be
                    // recomputed later from the (possibly since-changed) product_options.price.
                    $item['item_price'] = $price;
                    $item['total_price'] = $price !== null ? round($price * $product['quantity'], 2) : null;
                    $item->save();
                }

                if ($sale_code && $sale_code->one_time_code) {
                    $sale_code->used_at = now();
                    $sale_code->save();
                }

                return $new_order;
            });
        } catch (\RuntimeException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

        (new static)->del_cart_items($user->id);

        try {
            (new static)->send_order_confirm_mail_to_user($new_order->id);
        } catch (\Exception $e) {}

        try {
            (new static)->send_order_mail_ot_admin();
        } catch (\Exception $e) {}

        return response()->json(['message' => 'Order created successfully', 'order_id' => $new_order->id]);
    }

    // Atomically checks-and-decrements the general-warehouse stock for an
    // option in a single conditional UPDATE, so two concurrent checkouts
    // racing for the last unit can't both pass a separate read-then-write check.
    private function reserve_stock(Product_option $option, int $quantity): bool
    {
        $warehouse = $option->warehouse->where('general', '=', 1)->first();
        if (!$warehouse) {
            return $quantity <= 0;
        }

        $affected = DB::table('warehouses_product_options')
            ->where('warehouse_id', $warehouse->id)
            ->where('product_option_id', $option->id)
            ->where('quantity', '>=', $quantity)
            ->decrement('quantity', $quantity);

        return $affected > 0;
    }

    // Mirrors the free-shipping-threshold logic the checkout UI shows the
    // customer, but computed server-side from the buyer's own address so the
    // client-submitted shipping figure is never trusted for the charged amount.
    private function resolve_shipping_cost(User_adreses $address, array $order_product_list): float
    {
        $region = Shiped_region::find($address->region_id);
        if (!$region || !$region->shiping_price) {
            return 0;
        }

        $subtotal = 0;
        foreach ($order_product_list as $product) {
            $option = Product_option::find($product['option']['id'] ?? null);
            if ($option) {
                $subtotal += floatval($option->price) * (int) $product['quantity'];
            }
        }

        if ($region->free_shiping_price_after && $subtotal >= floatval($region->free_shiping_price_after)) {
            return 0;
        }

        return floatval($region->shiping_price);
    }

    // A sale code is valid to apply only while it isn't expired (action_data,
    // for non one-time codes) and — for a one-time code — hasn't already been
    // redeemed (used_at). Shared by check_sale_code() (preview) and
    // create_order() (the authoritative check at the moment of purchase).
    private function resolve_valid_sale_code($code)
    {
        $sale_code = Sale_code::where('code', $code)->first();
        if (!$sale_code) {
            return null;
        }

        if ($sale_code->one_time_code && $sale_code->used_at) {
            return null;
        }

        if (!$sale_code->one_time_code && $sale_code->action_data && Carbon::parse($sale_code->action_data)->isPast()) {
            return null;
        }

        return $sale_code;
    }

    public function add_custom_order(Request $request)
    {
        if ($auth = PermissionService::authorize('order', 'add')) return $auth;

        $create_production_task = (bool) $request->create_production_task;

        try {
            $new_order = DB::transaction(function () use ($request, $create_production_task) {
                // Validate + reserve stock before creating the custom order, unless
                // a production task is being created instead (nothing to reserve then).
                if (!$create_production_task) {
                    foreach ($request->order_product_list as $product) {
                        $option = Product_option::with('warehouse')->find($product['product_option_id']);
                        if (!$option || !(new static)->reserve_stock($option, (int) $product['quantity'])) {
                            throw new \RuntimeException('Not enough stock for option ID ' . $product['product_option_id']);
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
                $new_order['status_updating_data'] = date("Y-m-d H:i:s");

                if (!$new_order->save()) {
                    throw new \RuntimeException('Error saving order');
                }

                foreach ($request->order_product_list as $product) {
                    $option = Product_option::find($product['product_option_id']);
                    $price = $option ? floatval($option->price) : null;

                    $new_order_product_item = new Order_products;
                    $new_order_product_item['order_id'] = $new_order->id;
                    $new_order_product_item['product_id'] = $product['product_id'];
                    $new_order_product_item['product_option_id'] = $product['product_option_id'];
                    $new_order_product_item['quantity'] = $product['quantity'];
                    $new_order_product_item['item_price'] = $price;
                    $new_order_product_item['total_price'] = $price !== null ? round($price * $product['quantity'], 2) : null;

                    $new_order_product_item->save();
                }

                return $new_order;
            });
        } catch (\RuntimeException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

        // Send notification to user
        try {
            (new static)->send_order_confirm_mail_to_user($new_order->id);
        } catch (\Exception $e) {}

        // If production task is enabled, create production tasks for the options
        if ($create_production_task) {
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
            $item->delete();
        }
        return 'All items is deleted';
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
        $sale_code = (new static)->resolve_valid_sale_code($request->serching_code);

        return $sale_code ?? "Sale code dint fined";
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
        if ($auth = PermissionService::authorize('order', 'show')) return $auth;
        return $this->get_order_products_raw($order_id);
    }

    public function get_order_detals(Request $request)
    {
        if ($auth = PermissionService::authorize('order', 'show')) return $auth;
        return $this->build_order_detail($request->order_id);
    }

    public function get_my_purchase_detail(Request $request)
    {
        $user = Auth::user();
        if (!$user) return response()->json(['error' => 'Unauthorized'], 401);

        $order = Order::where('id', $request->order_id)
            ->where('user_id', $user->id)
            ->first();

        if (!$order) return response()->json(['error' => 'Order not found'], 404);

        return $this->build_order_detail($request->order_id, false);
    }

    private function build_order_detail($order_id, $include_related_users = true)
    {
        $order = Order::where("id", "=", $order_id)
            ->with(['relatedUsers:id,name,surname,email', 'userAdres'])
            ->first();

        if (!$order) return response()->json(['error' => 'Order not found'], 404);

        $order_products = (new static)->get_order_products_raw($order->id);

        $buyer_address = $order->is_custom
            ? $order->buyerAddress()->first()
            : $order->userAdres;

        $related_users = ($include_related_users && $order->is_custom)
            ? $order->relatedUsers->map(fn($u) => [
                'id'      => $u->id,
                'name'    => $u->name,
                'surname' => $u->surname,
                'email'   => $u->email,
            ])->values()
            : [];

        return [
            'order'          => $order,
            'order_products' => $order_products,
            'buyer_address'  => $buyer_address,
            'related_users'  => $related_users,
        ];
    }

    // Buyers linked (by email) to a partner organization member get that
    // organization's discount % stamped onto the order at creation time, so
    // later changes to the org's rate don't retroactively alter past orders.
    private function resolve_partner_discount($user_id)
    {
        $member = PartnerOrganizationMember::with('organization')->where('user_id', $user_id)->first();

        return optional(optional($member)->organization)->discount;
    }

    private function get_order_products_raw($order_id)
    {
        $product_items = Order_products::where("order_id", "=", $order_id)->get();
        $products = [];
        foreach ($product_items as $item) {
            $option = Product_option::where('id', strip_tags($item->product_option_id))->first();
            if (!$option) continue;
            $product = Product::where('id', strip_tags($option->product_id))->first();
            $products[] = [
                'product'  => $product,
                'option'   => $option,
                'quantity' => $item->quantity,
                // Price actually paid, snapshotted at order creation — falls back to
                // the option's current price only for orders placed before this existed.
                'item_price'  => $item->item_price ?? $option->price,
                'total_price' => $item->total_price ?? round(floatval($option->price) * $item->quantity, 2),
            ];
        }
        return $products;
    }

    public function order_is_confirm(Request $request)
    {
        $user = Auth::user();
        $orderId = $request->route('order_id') ?? $request->order_id;
        $order = Order::where("id", "=", $orderId)->first();

        if (!$order) return response()->json(['error' => 'Order not found'], 404);

        if ($order->user_id !== $user->id) {
            $auth = PermissionService::authorize('order', 'edit');
            if ($auth) return $auth;
        }

        $expectedToken = hash_hmac('sha256', $order->id . '|' . $order->user_id, config('app.key'));
        if (!hash_equals($expectedToken, (string)($request->token ?? ''))) {
            return response()->json(['error' => 'Invalid verification link'], 400);
        }

        if ($order->confirm == 1) {
            return response()->json(['error' => 'Order already confirmed'], 400);
        }

        $order['confirm'] = 1;
        $order['status'] = 'treatment';
        $order['status_updating_data'] = date("Y-m-d H:i:s");
        $order->update();

        (new static)->send_order_mail_ot_admin();

        return response()->json(['message' => 'Order confirmed successfully']);
    }

    public function is_order_confirm(Request $request)
    {
        $user = Auth::user();
        $orderId = $request->route('order_id') ?? $request->order_id;
        $order = Order::where("id", "=", $orderId)->first();

        if (!$order) return response()->json(['error' => 'Order not found'], 404);

        if ($order->user_id !== $user->id) {
            $auth = PermissionService::authorize('order', 'show');
            if ($auth) return $auth;
        }

        return $order->confirm != 1;
    }

    public function edit_order_status(Request $request)
    {
        $auth = PermissionService::authorize('order', 'edit');
        if ($auth) return $auth;

        $order = Order::where("id", "=", $request->order_id)->first();
        if (!$order) return response()->json(['error' => 'Order not found'], 404);

        $order['status'] = $request->status;
        $order['status_updating_data'] = date("Y-m-d H:i:s");
        $order->update();

        if ($order->user_id) {
            try {
                (new static)->order_status_notification($request->status, date("Y-m-d H:i:s"), $order->user_id, $order->id);
            } catch (\Exception $e) {}
        }

        return response()->json(['message' => 'Status updated', 'status' => $order->status]);
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
        if ($auth = PermissionService::authorize('order', 'show')) return $auth;
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
        if ($auth = PermissionService::authorize('order', 'show')) return $auth;
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

            $data = [['Date', 'Orders']];
            foreach($orders as $order){
                $data[] = [$order->date, (int)$order->count];
            }
        } else {
            $orders = $query->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
                ->groupByRaw('YEAR(created_at), MONTH(created_at)')
                ->orderByRaw('YEAR(created_at), MONTH(created_at)')
                ->get();

            $data = [['Month', 'Orders']];
            foreach($orders as $order){
                $data[] = [sprintf('%04d-%02d', $order->year, $order->month), (int)$order->count];
            }
        }

        return response()->json($data);
    }

    public function get_order_finance_statistics(Request $request)
    {
        if ($auth = PermissionService::authorize('order', 'show')) return $auth;
        $period = $request->route('period');

        $startDate = null;
        switch ($period) {
            case '30days': $startDate = now()->subDays(30); break;
            case '1month': $startDate = now()->subMonth(); break;
            case '3months': $startDate = now()->subMonths(3); break;
            case '6months': $startDate = now()->subMonths(6); break;
            case '1year': $startDate = now()->subYear(); break;
            case '2years': $startDate = now()->subYears(2); break;
            case '3years': $startDate = now()->subYears(3); break;
            case 'all': break;
            default: return response()->json(['error' => 'Invalid period'], 400);
        }

        $query = Order_products::join('orders', 'order_products.order_id', '=', 'orders.id')
            ->leftJoin('product_options', 'order_products.product_option_id', '=', 'product_options.id');

        if ($startDate) {
            $query->where('orders.created_at', '>=', $startDate);
        }

        $revenueExpr = 'SUM(COALESCE(order_products.total_price, CAST(product_options.price AS DECIMAL(10,2)) * order_products.quantity))';

        if (in_array($period, ['30days', '1month'])) {
            $rows = $query->selectRaw("DATE(orders.created_at) as date, {$revenueExpr} as revenue")
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            $data = [['Date', 'Revenue']];
            foreach ($rows as $row) {
                $data[] = [$row->date, (float) $row->revenue];
            }
        } else {
            $rows = $query->selectRaw("YEAR(orders.created_at) as year, MONTH(orders.created_at) as month, {$revenueExpr} as revenue")
                ->groupByRaw('YEAR(orders.created_at), MONTH(orders.created_at)')
                ->orderByRaw('YEAR(orders.created_at), MONTH(orders.created_at)')
                ->get();

            $data = [['Month', 'Revenue']];
            foreach ($rows as $row) {
                $data[] = [sprintf('%04d-%02d', $row->year, $row->month), (float) $row->revenue];
            }
        }

        return response()->json($data);
    }

}
