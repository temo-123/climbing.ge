<?php

namespace App\Http\Controllers\Api\User\Admin\Shop;

use App\Http\Controllers\Controller;
use App\Services\PermissionService;
use App\Services\OrderService;
use App\Services\OrderPlacementService;
use App\Services\OrderPlacementException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\Models\User;
use App\Models\Shop\Order;
use App\Models\Shop\Order_products;
use App\Models\Shop\CustomOrderAddress;
use App\Models\Shop\Product_option;
use App\Models\Shop\Product;
use App\Models\Shop\Shiped_region;
use App\Models\PartnerOrganization\PartnerOrganizationMember;
use App\Services\ProductService;

use Barryvdh\DomPDF\Facade\Pdf;

class CustomOrderController extends Controller
{
    public function store(Request $request)
    {
        if ($auth = PermissionService::authorizeAny([['order', 'add'], ['warehouse', 'sell_own']])) return $auth;

        // Pay Now orders skip address collection entirely - the buyer arranges
        // delivery separately with the seller once they've paid.
        $needsAddress = $request->delivery_type === 'delivery' && $request->payment_type !== 'online_payment';

        $request->validate([
            'name'               => 'required|string|max:100',
            'surname'            => 'required|string|max:100',
            'email'              => 'nullable|email|max:255',
            'phone'              => 'nullable|string|max:50',
            'delivery_type'      => ['required', Rule::in(OrderPlacementService::DELIVERY_METHODS)],
            'payment_type'       => ['required', Rule::in(OrderPlacementService::MANUAL_PAYMENTS)],
            'region_id'          => ['nullable', 'integer', 'exists:shiped_regions,id', Rule::requiredIf($needsAddress)],
            'delivery_price'     => 'nullable|numeric|min:0',
            'linked_user_id'     => 'nullable|integer|exists:users,id',
            'order_product_list' => 'required|array|min:1',
            'order_product_list.*.product_option_id' => 'required|integer|exists:product_options,id',
            'order_product_list.*.product_id'        => 'required|integer|exists:products,id',
            'order_product_list.*.quantity'          => 'required|integer|min:1',
        ]);

        $user = auth()->user();
        $warehouseId = ProductService::resolveEffectiveWarehouseId($user);
        if ($user->hasPermissionFor('warehouse', 'sell_own') && !$warehouseId) {
            return response()->json([
                'error' => 'No warehouse assigned to your account. Contact an administrator.'
            ], 422);
        }

        // Everything else — prices, region rule (minimum order / free
        // shipping), partner discount, stock from the seller's warehouse,
        // buyer address, account links — is OrderPlacementService, the same
        // code path as website checkout. The admin's typed delivery price is
        // only used when they actually changed the field (delivery_price_manual).
        try {
            $result = OrderPlacementService::place_manual([
                'name'      => $request->name,
                'surname'   => $request->surname,
                'email'     => $request->email,
                'phone'     => $request->phone,
                'address'   => $request->address,
                'city'      => $request->city,
                'country'   => $request->country,
                'map'       => $request->map,
                'region_id' => $request->region_id,
            ], [
                'delivery_method' => $request->delivery_type,
                'payment'         => $request->payment_type,
                'items'           => $request->order_product_list,
                'warehouse_id'    => $warehouseId,
                'skip_stock'      => (bool) $request->create_production_task,
                'delivery_price'  => $request->boolean('delivery_price_manual') ? $request->delivery_price : null,
                'linked_user_id'  => $request->linked_user_id,
            ]);
        } catch (OrderPlacementException $e) {
            return $e->response();
        }

        return response()->json([
            'message'          => 'Custom order created successfully',
            'order_id'         => $result['order']->id,
            'matched_users'    => $result['linked_users']->values(),
            'partner_discount' => $result['partner'],
        ], 201);
    }

    /**
     * Buyer search for the order-creation modal (GET custom_order/search_buyers).
     * Matches users by any of name / surname / e-mail / phone the staff member
     * has typed so far; the modal shows them and the picked one is linked to
     * the order (linked_user_id → orders_user). Public-safe columns only.
     */
    public function search_buyers(Request $request)
    {
        if ($auth = PermissionService::authorizeAny([['order', 'add'], ['warehouse', 'sell_own']])) return $auth;

        return response()->json(OrderPlacementService::search_buyers($request->only(['name', 'surname', 'email', 'phone'])));
    }

    /**
     * Live lookup for the order-creation modal: does this buyer (by email, or
     * by name+surname when no email is typed yet) belong to a partner
     * organization, and - since the admin only ever types one of email vs.
     * name+surname first - do we already know their other contact details
     * from a matching User account or partner-member record? Preview only -
     * store() and exportInvoicePdf() re-resolve the discount themselves from
     * the buyer info they actually persist/print, never from a value this
     * endpoint or the client returned. Autofill is harmless either way since
     * the admin can freely overwrite whatever gets filled in.
     */
    public function check_partner_discount(Request $request)
    {
        if ($auth = PermissionService::authorizeAny([['order', 'add'], ['warehouse', 'sell_own']])) return $auth;

        $email = $request->email;
        $name = $request->name;
        $surname = $request->surname;
        $phone = $request->phone;

        $user = null;
        if ($email) {
            $user = User::where('email', $email)->first();
        }
        if (!$user && $phone) {
            $user = User::where('phone_number', $phone)->first();
        }
        if (!$user && $name && $surname) {
            $user = User::where('name', $name)->where('surname', $surname)->first();
        }

        $member = $this->find_partner_member($email, $name, $surname, $phone);
        $organization = $member?->organization;

        return response()->json([
            'discount'          => $organization ? (float) $organization->discount : 0,
            'organization_name' => $organization?->name,
            'name'              => $user->name ?? $member?->name,
            'surname'           => $user->surname ?? $member?->surname,
            'email'             => $user->email ?? $member?->email,
            'phone'             => $user->phone_number ?? $member?->phone_number,
        ]);
    }

    // Partner lookup by buyer contact — shared with order placement.
    private function find_partner_member(?string $email, ?string $name, ?string $surname, ?string $phone = null): ?PartnerOrganizationMember
    {
        return OrderPlacementService::partner_member_by_contact($email, $name, $surname, $phone);
    }

    private function resolve_partner_discount(?string $email, ?string $name, ?string $surname, ?string $phone = null): ?array
    {
        return OrderPlacementService::partner_for_contact($email, $name, $surname, $phone);
    }

    public function index()
    {
        if ($auth = PermissionService::authorize('order', 'show')) return $auth;

        $orders = Order::where('is_custom', true)
            ->with(['buyerAddress.region', 'relatedUsers', 'orderProducts.option'])
            ->latest()
            ->get()
            ->map(function ($order) {
                $buyer = $order->buyerAddress->first();
                return [
                    'id'            => $order->id,
                    'status'        => $order->status,
                    'payment'       => $order->payment,
                    'shiping'       => $order->shiping,
                    'delivery_price' => $order->delivery_price,
                    'confirm'       => $order->confirm,
                    'created_at'    => $order->created_at,
                    'buyer_name'    => $buyer?->name,
                    'buyer_surname' => $buyer?->surname,
                    'buyer_email'   => $buyer?->email,
                    'buyer_phone'   => $buyer?->phone,
                    'region_name'   => $buyer?->region?->region,
                    'related_users' => $order->relatedUsers->map(fn($u) => [
                        'id'      => $u->id,
                        'name'    => $u->name,
                        'surname' => $u->surname,
                        'email'   => $u->email,
                    ]),
                    'products'      => $order->orderProducts->map(fn($op) => [
                        'product_id'        => $op->product_id,
                        'product_option_id' => $op->product_option_id,
                        'quantity'          => $op->quantity,
                    ]),
                ];
            });

        return response()->json($orders);
    }

    public function show($order_id)
    {
        if ($auth = PermissionService::authorize('order', 'show')) return $auth;

        $order = Order::where('id', $order_id)
            ->where('is_custom', true)
            ->with(['buyerAddress.region', 'relatedUsers', 'orderProducts'])
            ->firstOrFail();

        $buyer = $order->buyerAddress->first();

        return response()->json([
            'id'            => $order->id,
            'status'        => $order->status,
            'payment'       => $order->payment,
            'shiping'       => $order->shiping,
            'delivery_price' => $order->delivery_price,
            'confirm'       => $order->confirm,
            'created_at'    => $order->created_at,
            'buyer_name'    => $buyer?->name,
            'buyer_surname' => $buyer?->surname,
            'buyer_email'   => $buyer?->email,
            'buyer_phone'   => $buyer?->phone,
            'buyer_address' => $buyer?->address,
            'region_name'   => $buyer?->region?->region,
            'related_users' => $order->relatedUsers->map(fn($u) => [
                'id'      => $u->id,
                'name'    => $u->name,
                'surname' => $u->surname,
                'email'   => $u->email,
            ]),
            'products' => $order->orderProducts->map(fn($op) => [
                'product_id'        => $op->product_id,
                'product_option_id' => $op->product_option_id,
                'quantity'          => $op->quantity,
            ]),
        ]);
    }

    public function exportInvoicePdf(Request $request)
    {
        if ($auth = PermissionService::authorizeAny([['order', 'add'], ['warehouse', 'sell_own']])) return $auth;

        $request->validate([
            'name'               => 'required|string|max:100',
            'surname'            => 'required|string|max:100',
            'email'              => 'nullable|email|max:255',
            'phone'              => 'nullable|string|max:50',
            'address'            => 'nullable|string',
            'city'               => 'nullable|string',
            'locale'             => 'nullable|string|in:ka,en',
            'order_product_list' => 'required|array|min:1',
            'order_product_list.*.product_option_id' => 'required|integer|exists:product_options,id',
            'order_product_list.*.product_id'        => 'required|integer|exists:products,id',
            'order_product_list.*.quantity'          => 'required|integer|min:1',
        ]);

        $locale = $request->input('locale', 'ka');

        $line_items = [];
        $total = 0;

        foreach ($request->order_product_list as $item) {
            $option = Product_option::find($item['product_option_id']);
            if (!$option) continue;

            $product = Product::with(['us_product', 'ka_product'])->find($item['product_id']);
            $title = $locale === 'ka'
                ? ($product?->ka_product?->title ?: $product?->us_product?->title)
                : ($product?->us_product?->title ?: $product?->ka_product?->title);
            $title = $title ?: $product?->url_title ?: '—';

            // Same sale price the order will be charged (option's own discount applied).
            $unit_price = ProductService::option_sale_price($option);
            $quantity   = (int) $item['quantity'];
            $line_total = $unit_price * $quantity;
            $total += $line_total;

            $line_items[] = [
                'title'      => $title,
                'option'     => $option->name,
                'quantity'   => $quantity,
                'unit_price' => $unit_price,
                'list_price' => round((float) $option->price, 2),
                'item_discount' => ProductService::option_discount_percent($option),
                'line_total' => $line_total,
                'currency'   => $option->currency ?: '₾',
            ];
        }

        if (empty($line_items)) {
            return response()->json(['error' => 'No valid items in product list'], 400);
        }

        $subtotal = $total;
        $partner = $this->resolve_partner_discount($request->email, $request->name, $request->surname, $request->phone);
        $discount_percent = $partner['discount'] ?? 0;
        $discount_amount = $discount_percent > 0 ? round($subtotal * $discount_percent / 100, 2) : 0;
        $total = $subtotal - $discount_amount;

        $invoiceNumber = 'INV-' . now()->format('Ymd-His');

        $company = OrderService::company();

        $labels = $this->invoiceLabels($locale);

        $pdf = Pdf::loadView('pdf.custom_order_invoice', [
            'buyer' => [
                'name'    => $request->name,
                'surname' => $request->surname,
                'email'   => $request->email,
                'phone'   => $request->phone,
                'address' => $request->address,
                'city'    => $request->city,
            ],
            'company'          => $company,
            'labels'           => $labels,
            'locale'           => $locale,
            'invoice_number'   => $invoiceNumber,
            'line_items'       => $line_items,
            'subtotal'         => $subtotal,
            'discount_percent' => $discount_percent,
            'discount_amount'  => $discount_amount,
            'partner_name'     => $partner['organization_name'] ?? null,
            'total'            => $total,
            'invoice_date'     => now()->format('Y-m-d'),
        ]);

        return $pdf->download($invoiceNumber . '.pdf');
    }

    private function invoiceLabels(string $locale): array
    {
        return OrderService::invoice_labels($locale);
    }

    // Warehouse-scoped product list for the custom-order modal — mirrors
    // Api\Shop\ProductController::get_current_products()'s response shape
    // exactly, but sourced from ProductService::resolveEffectiveWarehouseId()
    // instead of always returning every published product. A restricted
    // 'warehouse'/'sell_own' user only sees products actually stocked in
    // their own warehouse; everyone else sees the same general-warehouse
    // list as before (just through this endpoint instead of the public one).
    public function get_products_for_order()
    {
        if ($auth = PermissionService::authorizeAny([['order', 'add'], ['warehouse', 'sell_own']])) return $auth;

        $warehouseId = ProductService::resolveEffectiveWarehouseId(auth()->user());
        if (!$warehouseId) return response()->json([]);

        $products = Product::where('published', 1)
            ->whereHas('product_options.warehouse', fn($q) => $q->where('warehouses.id', $warehouseId))
            ->with(['product_options.warehouse' => fn($q) => $q->where('warehouses.id', $warehouseId), 'product_options.images'])
            ->get();

        return response()->json($products->map(function ($product) use ($warehouseId) {
            $locale_product = ProductService::get_locale_product_in_page_use_locale($product, 'en');
            return [
                'id'    => $product->id,
                'title' => $locale_product['locale_product']->title ?? 'No title',
                'options' => $product->product_options
                    ->filter(fn($option) => $option->warehouse->isNotEmpty())
                    ->values()
                    ->map(fn($option) => [
                        'id'     => $option->id,
                        'name'   => $option->name ?? 'Option ' . $option->id,
                        // `price` is what the order charges (sale price); list_price + discount for display.
                        'price'      => ProductService::option_sale_price($option),
                        'list_price' => round((float) $option->price, 2),
                        'discount'   => ProductService::option_discount_percent($option),
                        'quantity' => ProductService::get_option_stock_quantity_for_warehouse($option, $warehouseId),
                        'images' => $option->images->map(fn($image) => [
                            'id'    => $image->id,
                            'image' => $image->image,
                        ]),
                    ]),
            ];
        })->values());
    }

    // Warehouse-scoped option list for a single product — mirrors
    // Api\Shop\ProductController::get_product_options()'s response shape.
    public function get_options_for_order($product_id)
    {
        if ($auth = PermissionService::authorizeAny([['order', 'add'], ['warehouse', 'sell_own']])) return $auth;

        $warehouseId = ProductService::resolveEffectiveWarehouseId(auth()->user());
        $product = Product::find($product_id);
        if (!$product || !$warehouseId) return response()->json([]);

        $options = $product->product_options;
        return response()->json($options->map(fn($option) => [
            'id'    => $option->id,
            'name'  => $option->name ?? 'Option ' . $option->id,
            'price'      => ProductService::option_sale_price($option),
            'list_price' => round((float) $option->price, 2),
            'discount'   => ProductService::option_discount_percent($option),
            'quantity' => ProductService::get_option_stock_quantity_for_warehouse($option, $warehouseId),
            'image' => $option->images->first()->image ?? null,
        ]));
    }
}
