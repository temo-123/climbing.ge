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
use App\Services\CoefficientService;
use App\Services\OrderService;
use App\Services\OrderPlacementService;
use App\Services\OrderStatisticsService;
use App\Services\OrderPlacementException;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

use App\Notifications\order\OrderConfirm;
use App\Notifications\order\AdminOrderDeclorationNotification;
use App\Notifications\order\OrderStatusNotification;
use App\Notifications\order\OrderCompletedNotification;
use App\Services\OrderReviewService;

use App\Notifications\order\castam_prodaction\SelerCastamProdactionNotification;
use App\Notifications\order\castam_prodaction\BuyerCastamProdactionNotification;

class OrderController extends Controller
{
    public function get_all_orders()
    {
        if ($auth = PermissionService::authorize('order', 'show')) return $auth;

        return Order::with(['user:id,name,surname,email,phone_number', 'buyerAddress:id,name,surname,email,phone', 'orderProducts.option:id,price,discount,currency', 'review'])
            ->latest()
            ->get()
            ->map(function ($order) {
                // Flat buyer columns for the admin orders table: custom orders keep the
                // buyer in custom_order_addresses, cart / online orders in users.
                $buyer = $order->is_custom ? $order->buyerAddress->first() : $order->user;

                $order->buyer_name  = $buyer ? trim(($buyer->name ?? '') . ' ' . ($buyer->surname ?? '')) : null;
                $order->buyer_email = $buyer->email ?? null;
                $order->buyer_phone = $order->is_custom ? ($buyer->phone ?? null) : ($buyer->phone_number ?? null);

                // order_type / shipping_method / shipping_cost / payment_method / order_total
                foreach (OrderService::list_summary($order) as $k => $v) $order->{$k} = $v;

                // order (delivery) review from the "order complete" mail — admin only
                $review = $order->review;
                $order->review_stars = $review->stars ?? null;
                $order->review_label = $review
                    ? str_repeat('★', $review->stars) . str_repeat('☆', 5 - $review->stars)
                        . ($review->text ? ' · ' . \Illuminate\Support\Str::limit($review->text, 60) : '')
                    : ($order->review_requested_at ? '…' : '—');

                $order->unsetRelation('user');
                $order->unsetRelation('buyerAddress');
                $order->unsetRelation('orderProducts');
                $order->unsetRelation('review');
                return $order;
            });
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
            $summary = OrderService::list_summary($order);
            $data = $order->toArray();
            unset($data['order_products']);
            $data = array_merge($data, $summary);
            $data['delivery_days'] = CoefficientService::deliveryDays($has_produced);
            return $data;
        });
    }

    public function get_order_status($order_id)
    {
        if ($auth = PermissionService::authorize('order', 'show')) return $auth;
        return Order::where("id", "=", $order_id)->select('id', 'status', 'status_updating_data')->first();
    }

    public function castam_prodaction_message(Request $request) {
        // Self-checkout style action, like create_order() - a customer sending a
        // custom-order inquiry about their own product interest is not an admin
        // action and must never be gated behind the 'order'>'edit' permission
        // (the default 'user' role has zero permissions, so that gate 403'd this
        // endpoint for every real customer).
        $user = auth('sanctum')->user();
        if($user){
            $actyve_product = Product::where('id', '=', $request->product_id)->first();
            $actyve_product_user = $actyve_product->user->first();

            $actyve_local_product = ProductService::get_locale_product_in_page_use_locale($actyve_product, 'us')['locale_product'];

            // The variant the customer picked on the product page - previously
            // never reached the backend at all, so sellers had no idea which
            // option a custom-production request was actually about.
            $selected_option = Product_option::where('id', '=', $request->option_id)
                ->where('product_id', '=', $request->product_id)
                ->first();

            $info = [
                "product_name" => $actyve_local_product->title,
                "messaged_user_name" => $user->name . ' ' . $user->surname,
                "messaged_user_email" => $user->email,
                "product_id" => $request->product_id,
                "option_name" => $selected_option->name ?? null,
                "number" => $request->form_data['number'],
                "message" => $request->form_data['text']
            ];

            Notification::route('mail', $actyve_product_user->email)->notify(new SelerCastamProdactionNotification($info));
            Notification::route('mail', $user->email)->notify(new BuyerCastamProdactionNotification());

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

        // Validation, price snapshot, region shipping rule (minimum order /
        // free shipping), discount and atomic stock reservation all happen in
        // OrderPlacementService — the same code path as manual orders.
        try {
            $new_order = OrderPlacementService::place_website(
                $user,
                (int) $request->adres,
                $request->order_product_list,
                (string) $request->payment_tupe,
                $request->sale_code ?: null
            );
        } catch (OrderPlacementException $e) {
            return response()->json(['error' => $e->getMessage(), 'code' => $e->reason], 400);
        }

        (new static)->del_cart_items($user->id);

        // Email confirmation is only required for cash-on-delivery orders —
        // an online payment is itself the buyer's confirmation (TBC's
        // callback/status endpoints set orders.confirm = 1 on success and
        // notify the seller from there instead). The seller/site notification
        // is intentionally NOT sent here either way — order_is_confirm() /
        // the payment callback send it once the order is actually confirmed,
        // so a buyer who never confirms a COD order never triggers it.
        if ($request->payment_tupe === 'deliverd payment') {
            try {
                (new static)->send_order_confirm_mail_to_user($new_order->id);
            } catch (\Exception $e) {}
        }

        return response()->json(['message' => 'Order created successfully', 'order_id' => $new_order->id]);
    }

    // Legacy admin endpoint (POST set_order/add_custom_order) — the admin UI
    // uses custom_order/store now. Kept working through the same placement
    // service: a manual order with the admin as buyer, from the general /
    // the admin's own warehouse. It used to write orders.email/phone_number
    // (columns that don't exist), so every call failed before.
    // POST set_order/checkout_quote — what create_order() would charge for
    // this cart + address + sale code, without writing anything. The two
    // checkout pages show this instead of computing prices in the browser.
    public function checkout_quote(Request $request)
    {
        $user = Auth::user();
        if (!$user) return response()->json(['error' => 'Please login'], 401);

        return response()->json(OrderPlacementService::quote_website(
            $user,
            $request->adres ? (int) $request->adres : null,
            is_array($request->order_product_list) ? $request->order_product_list : [],
            $request->sale_code ?: null,
            $request->locale === 'ka' ? 'ka' : 'en'
        ));
    }

    public function add_custom_order(Request $request)
    {
        if ($auth = PermissionService::authorize('order', 'add')) return $auth;

        $admin = Auth::user();
        try {
            $result = OrderPlacementService::place_manual([
                'name'    => $admin->name,
                'surname' => $admin->surname,
                'email'   => $request->email ?: $admin->email,
                'phone'   => $request->phone_number ?: $admin->phone_number,
            ], [
                'delivery_method' => $request->delivery_type ?: 'self_delivery',
                'payment'         => $request->payment_type ?: 'deliverd_payment',
                'items'           => $request->order_product_list ?? [],
                'warehouse_id'    => ProductService::resolveEffectiveWarehouseId($admin),
                'skip_stock'      => (bool) $request->create_production_task,
            ]);
        } catch (OrderPlacementException $e) {
            return response()->json(['error' => $e->getMessage(), 'code' => $e->reason], 400);
        }

        return response()->json(['message' => 'Custom order added successfully', 'order_id' => $result['order']->id]);
    }

    public function del_cart_items($user_id)
    {
        $cart_items = Cart::where('user_id', '=', $user_id)->get();
        foreach ($cart_items as $item) {
            $item->delete();
        }
        return 'All items is deleted';
    }

    // Accepts an explicit $user so this can be called from contexts with no
    // authenticated session (e.g. the unauthenticated TBC payment callback) —
    // falls back to Auth::user() for the existing buyer-confirms-by-email path.
    public function send_order_mail_ot_admin($user = null)
    {
        $user = $user ?: Auth::user();
        $order_info = array(
            'name' => $user->name,
            'surname' => $user->surname,
            'user_id' => $user->id,
        );
        $site_data = Site::first();
        // dd($site_data);
        Notification::send($site_data, new AdminOrderDeclorationNotification($order_info));
    }

    public function check_sale_code(Request $request)
    {
        // Same validity rule create_order() applies (OrderPlacementService::valid_sale_code).
        $sale_code = OrderPlacementService::valid_sale_code($request->serching_code);

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
            ->with(['relatedUsers:id,name,surname,email', 'userAdres', 'user:id,name,surname,email,phone_number,country,city'])
            ->first();

        if (!$order) return response()->json(['error' => 'Order not found'], 404);

        $locale = request()->query('locale') === 'ka' ? 'ka' : 'en';
        $order_products = OrderService::line_items($order, $locale);

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

        // Account that placed a regular (cart / online) order — admin view only.
        $buyer = ($include_related_users && !$order->is_custom && $order->user)
            ? $order->user->only(['id', 'name', 'surname', 'email', 'phone_number', 'country', 'city'])
            : null;

        // Don't leak the eager-loaded user through the order payload itself.
        $order->unsetRelation('user');

        // order (delivery) review — admin view only; the buyer's own purchase view doesn't get it
        $review = $include_related_users ? $order->review()->first() : null;

        return [
            'order'          => $order,
            'order_type'     => OrderService::type($order),
            'payment_method' => OrderService::payment_method($order),
            'buyer'          => $buyer,
            'order_products' => $order_products,
            // subtotal / discount / shipping / total / paid_amount — use these, don't re-add on the client.
            'totals'         => OrderService::totals($order, $order_products),
            // method, cost, recipient, phone, street, details, city, region, zip, map, pickup_from
            'delivery'       => OrderService::delivery($order),
            'buyer_address'  => $buyer_address,
            'related_users'  => $related_users,
            'order_review'   => $review ? $review->only(['stars', 'delivery_speed', 'packaging', 'text', 'created_at']) : null,
        ];
    }

    // GET get_order/invoice_pdf/{order_id}?locale=ka|en — admin, any order.
    public function invoice_pdf(Request $request, $order_id)
    {
        if ($auth = PermissionService::authorize('order', 'show')) return $auth;

        $order = Order::find($order_id);
        if (!$order) return response()->json(['error' => 'Order not found'], 404);

        return $this->render_invoice($order, $request->query('locale', 'ka'));
    }

    // GET get_order/my_invoice_pdf/{order_id}?locale=ka|en — the buyer's own order only.
    public function my_invoice_pdf(Request $request, $order_id)
    {
        $user = Auth::user();
        if (!$user) return response()->json(['error' => 'Unauthorized'], 401);

        $order = Order::where('id', $order_id)->where('user_id', $user->id)->first();
        if (!$order) return response()->json(['error' => 'Order not found'], 404);

        return $this->render_invoice($order, $request->query('locale', 'ka'));
    }

    private function render_invoice(Order $order, string $locale)
    {
        $data = OrderService::invoice_data($order, $locale);

        return Pdf::loadView('pdf.custom_order_invoice', $data)
            ->download($data['invoice_number'] . '.pdf');
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

        try {
            (new static)->order_status_notification($request->status, date("Y-m-d H:i:s"), $order);
        } catch (\Exception $e) {
            \Log::warning('Order status mail failed', ['order_id' => $order->id, 'error' => $e->getMessage()]);
        }

        return response()->json(['message' => 'Status updated', 'status' => $order->status]);
    }

    // Mail goes to the order's buyer, never the admin changing the status:
    // custom orders (is_custom, user_id NULL) keep the buyer in custom_order_addresses,
    // cart / online orders use the ordering user.
    public function order_status_notification($status, $data_time, Order $order)
    {
        // "Order Complete": one mail only — thanks + review link — instead of the status mail,
        // and only the first time (review_requested_at). Docs: FUNCTIONS_DOCS/shop/order_review.md
        if ($status === OrderReviewService::COMPLETE_STATUS) {
            if ($order->review_requested_at) return;

            $buyer = OrderReviewService::buyer($order);
            if (!$buyer['email']) return;

            $products = collect(OrderReviewService::products($order, 'en', null))
                ->map(fn ($p) => ['title' => $p['title'], 'options' => $p['options']])->all();
            $mail = new OrderCompletedNotification(
                (int) $order->id,
                (string) $buyer['name'],
                $products,
                OrderReviewService::url($order, $buyer['lang'])
            );

            if ($buyer['user']) Notification::send($buyer['user'], $mail);
            else Notification::route('mail', $buyer['email'])->notify($mail);

            $order->forceFill(['review_requested_at' => now()])->save();
            return;
        }

        $order_status_info = array(
            'status' => $status,
            'data_time' => $data_time,
            'order_id' => $order->id,
        );

        if ($order->is_custom) {
            $buyer_email = optional($order->buyerAddress()->first())->email ?? $order->email;
            if ($buyer_email) {
                Notification::route('mail', $buyer_email)->notify(new OrderStatusNotification($order_status_info));
            }
            return;
        }

        $order_user = $order->user_id ? User::find($order->user_id) : null;
        if ($order_user && $order_user->email) {
            Notification::send($order_user, new OrderStatusNotification($order_status_info));
        }
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

    // Shop dashboard charts: zero-filled buckets, real order totals, only
    // confirmed / not failed / not cancelled orders — OrderStatisticsService.
    public function get_order_dashboard(Request $request)
    {
        if ($auth = PermissionService::authorize('order', 'show')) return $auth;

        $data = OrderStatisticsService::build((string) $request->route('period'));
        if (!$data) return response()->json(['error' => 'Invalid period'], 400);

        return response()->json($data);
    }

    // Older table-shaped endpoints ([['Date','Orders'], [key, n], …]) — kept
    // for any caller still using them, now from the same corrected numbers.
    public function get_order_statistics(Request $request)
    {
        return $this->legacy_series($request, 'orders', 'Orders');
    }

    public function get_order_finance_statistics(Request $request)
    {
        return $this->legacy_series($request, 'revenue', 'Revenue');
    }

    private function legacy_series(Request $request, string $field, string $label)
    {
        if ($auth = PermissionService::authorize('order', 'show')) return $auth;

        $data = OrderStatisticsService::build((string) $request->route('period'));
        if (!$data) return response()->json(['error' => 'Invalid period'], 400);

        $rows = [[$data['granularity'] === 'month' ? 'Month' : 'Date', $label]];
        foreach ($data['buckets'] as $b) $rows[] = [$b['key'], $b[$field]];

        return response()->json($rows);
    }

    // POST set_order/resend_order_confirm/{order_id} — the buyer asks for the
    // cash-on-delivery confirmation e-mail again (post-checkout page).
    // Own, unconfirmed COD orders only; at most once a minute per order.
    public function resend_order_confirm(Request $request, $order_id)
    {
        $user = Auth::user();
        if (!$user) return response()->json(['error' => 'Unauthorized'], 401);

        $order = Order::where('id', $order_id)->where('user_id', $user->id)->first();
        if (!$order) return response()->json(['error' => 'Order not found'], 404);

        if ((int) $order->confirm === 1) {
            return response()->json(['error' => 'Order already confirmed', 'code' => 'already_confirmed'], 400);
        }
        if (OrderService::payment_method($order) !== 'cash_on_delivery') {
            return response()->json(['error' => 'This order is confirmed by its payment', 'code' => 'not_cash_order'], 400);
        }

        $key = 'order_confirm_resend_' . $order->id;
        if (\Illuminate\Support\Facades\Cache::has($key)) {
            return response()->json(['error' => 'Please wait a minute before asking again', 'code' => 'too_soon'], 429);
        }
        \Illuminate\Support\Facades\Cache::put($key, true, 60);

        $this->send_order_confirm_mail_to_user($order->id);

        return response()->json(['message' => 'Confirmation e-mail sent', 'email' => $user->email]);
    }

}
