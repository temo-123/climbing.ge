<?php

namespace App\Http\Controllers\Api\User\Admin\Shop;

use App\Http\Controllers\Controller;
use App\Services\PermissionService;
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
            'delivery_type'      => 'required|string',
            'payment_type'       => 'required|string',
            'region_id'          => ['nullable', 'integer', 'exists:shiped_regions,id', Rule::requiredIf($needsAddress)],
            'delivery_price'     => 'nullable|numeric|min:0',
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

        // Stock check unless production task override
        if (!$request->create_production_task) {
            foreach ($request->order_product_list as $item) {
                $option = Product_option::with('warehouse')->find($item['product_option_id']);
                $stock  = ProductService::get_option_stock_quantity_for_warehouse($option, $warehouseId);
                if ($stock < $item['quantity']) {
                    return response()->json([
                        'error' => 'Not enough stock for option ID ' . $item['product_option_id']
                    ], 400);
                }
            }
        }

        // For a delivery order, default the price to the region's own shipping
        // price when the admin didn't type an override amount.
        $delivery_price = null;
        if ($needsAddress) {
            $delivery_price = $request->filled('delivery_price')
                ? (float) $request->delivery_price
                : (float) (Shiped_region::find($request->region_id)->shiping_price ?? 0);
        }

        // Resolved from the buyer's own email/name+surname, never from a
        // client-sent value - same reasoning as create_order()'s shipping cost:
        // never trust the client for money.
        $partner = $this->resolve_partner_discount($request->email, $request->name, $request->surname, $request->phone);
        $discount = $partner['discount'] ?? 0;

        // Pay Now orders are settled at creation time - no pending fulfillment
        // step to track, so they go straight to "Order Complete" instead of
        // sitting in "pending" like every other payment method.
        $status = $request->payment_type === 'online_payment' ? 'Order Complete' : 'pending';

        // Create the order
        $order = Order::create([
            'is_custom'            => true,
            'warehouse_id'         => $warehouseId,
            'shiping'              => $request->delivery_type,
            'delivery_price'       => $delivery_price,
            'payment'              => $request->payment_type,
            'discount'             => $discount,
            'confirm'              => 1,
            'status'               => $status,
            'status_updating_data' => now(),
        ]);

        // Store buyer contact info
        $address = CustomOrderAddress::create([
            'name'      => $request->name,
            'surname'   => $request->surname,
            'email'     => $request->email,
            'phone'     => $request->phone,
            'address'   => $request->address,
            'map'       => $request->map,
            'city'      => $request->city,
            'country'   => $request->country,
            'region_id' => $needsAddress ? $request->region_id : null,
        ]);

        $order->buyerAddress()->attach($address->id);

        // Create order products and subtract stock
        foreach ($request->order_product_list as $item) {
            Order_products::create([
                'order_id'          => $order->id,
                'product_id'        => $item['product_id'],
                'product_option_id' => $item['product_option_id'],
                'quantity'          => $item['quantity'],
            ]);

            if (!$request->create_production_task) {
                $this->subtractStock($item['product_option_id'], $item['quantity'], $warehouseId);
            }
        }

        // Match existing users by email or name+surname → link via orders_user
        $matchedUserIds = collect();

        if ($request->email) {
            $byEmail = User::where('email', $request->email)->pluck('id');
            $matchedUserIds = $matchedUserIds->merge($byEmail);
        }

        $byName = User::where('name', $request->name)
            ->where('surname', $request->surname)
            ->pluck('id');
        $matchedUserIds = $matchedUserIds->merge($byName)->unique();

        $matchedUsers = collect();
        if ($matchedUserIds->isNotEmpty()) {
            $order->relatedUsers()->sync($matchedUserIds->toArray());
            $matchedUsers = User::whereIn('id', $matchedUserIds)
                ->get(['id', 'name', 'surname', 'email']);
        }

        return response()->json([
            'message'          => 'Custom order created successfully',
            'order_id'         => $order->id,
            'matched_users'    => $matchedUsers->values(),
            'partner_discount' => $partner,
        ], 201);
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

    /**
     * Matches a partner-organization member by email first (most reliable),
     * then phone, falling back to name+surname - mirrors the matched_users
     * lookup in store(), since a partner member here isn't necessarily a
     * registered User account (partner_organization_members has its own
     * name/surname/email/phone_number columns, independent of user_id).
     */
    private function find_partner_member(?string $email, ?string $name, ?string $surname, ?string $phone = null): ?PartnerOrganizationMember
    {
        if ($email) {
            $member = PartnerOrganizationMember::with('organization')->where('email', $email)->first();
            if ($member) return $member;
        }

        if ($phone) {
            $member = PartnerOrganizationMember::with('organization')->where('phone_number', $phone)->first();
            if ($member) return $member;
        }

        if ($name && $surname) {
            return PartnerOrganizationMember::with('organization')
                ->where('name', $name)
                ->where('surname', $surname)
                ->first();
        }

        return null;
    }

    private function resolve_partner_discount(?string $email, ?string $name, ?string $surname, ?string $phone = null): ?array
    {
        $member = $this->find_partner_member($email, $name, $surname, $phone);

        if (!$member || !$member->organization) {
            return null;
        }

        return [
            'discount'           => (float) $member->organization->discount,
            'organization_name'  => $member->organization->name,
        ];
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

            $unit_price = (float) $option->price;
            $quantity   = (int) $item['quantity'];
            $line_total = $unit_price * $quantity;
            $total += $line_total;

            $line_items[] = [
                'title'      => $title,
                'option'     => $option->name,
                'quantity'   => $quantity,
                'unit_price' => $unit_price,
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

        $company = [
            'name'         => env('COMPANY_LEGAL_NAME', 'climbing.ge'),
            'tax_id'       => env('COMPANY_TAX_ID', ''),
            'address'      => env('COMPANY_ADDRESS', ''),
            'phone'        => env('COMPANY_PHONE', ''),
            'email'        => env('COMPANY_EMAIL', ''),
            'bank_name'    => env('COMPANY_BANK_NAME', ''),
            'bank_iban'    => env('COMPANY_BANK_IBAN', ''),
            'bank_account_name' => env('COMPANY_BANK_ACCOUNT_NAME', ''),
            'bank_code'    => env('COMPANY_BANK_CODE', ''),
        ];

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
        if ($locale === 'ka') {
            return [
                'invoice'         => 'ინვოისი',
                'invoice_number'  => 'ინვოისის ნომერი',
                'date'            => 'თარიღი',
                'seller'          => 'გამყიდველი',
                'billed_to'       => 'მყიდველი',
                'tax_id'          => 'საიდ. კოდი',
                'phone'           => 'ტელეფონი',
                'email'           => 'ელ. ფოსტა',
                'product'         => 'პროდუქტი',
                'option'          => 'ვარიანტი',
                'qty'             => 'რაოდ.',
                'unit_price'      => 'ერთეულის ფასი',
                'line_total'      => 'ჯამი',
                'subtotal'        => 'ჯამი (ფასდაკლების გარეშე)',
                'discount'        => 'ფასდაკლება',
                'total'           => 'გადასახდელი სულ',
                'payment_details' => 'გადახდის დეტალები',
                'bank'            => 'ბანკი',
                'account_holder'  => 'ანგარიშის მფლობელი',
                'iban'            => 'ანგარიშის ნომერი (IBAN)',
                'bank_code'       => 'ბანკის კოდი',
                'payment_note'    => 'გადახდისას გთხოვთ მიუთითოთ ინვოისის ნომერი დანიშნულებაში.',
                'thank_you'       => 'გმადლობთ შეკვეთისთვის!',
                'generated_by'    => 'დოკუმენტი გენერირებულია climbing.ge ადმინ პანელიდან',
            ];
        }

        return [
            'invoice'         => 'Invoice',
            'invoice_number'  => 'Invoice number',
            'date'            => 'Date',
            'seller'          => 'Seller',
            'billed_to'       => 'Billed to',
            'tax_id'          => 'Tax ID',
            'phone'           => 'Phone',
            'email'           => 'Email',
            'product'         => 'Product',
            'option'          => 'Option',
            'qty'             => 'Qty',
            'unit_price'      => 'Unit Price',
            'line_total'      => 'Total',
            'subtotal'        => 'Subtotal',
            'discount'        => 'Discount',
            'total'           => 'Total Due',
            'payment_details' => 'Payment Details',
            'bank'            => 'Bank',
            'account_holder'  => 'Account Holder',
            'iban'            => 'Account Number (IBAN)',
            'bank_code'       => 'Bank Code',
            'payment_note'    => 'Please reference the invoice number in the payment description.',
            'thank_you'       => 'Thank you for your order!',
            'generated_by'    => 'Generated by the climbing.ge admin panel',
        ];
    }

    private function subtractStock(int $optionId, int $quantity, ?int $warehouseId): void
    {
        if (!$warehouseId) return;
        $option    = Product_option::find($optionId);
        $warehouse = $option?->warehouse->where('id', $warehouseId)->first();
        if ($warehouse) {
            $warehouse->pivot->quantity = max(0, $warehouse->pivot->quantity - $quantity);
            $warehouse->pivot->save();
        }
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
                        'price'  => $option->price,
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
            'price' => $option->price,
            'quantity' => ProductService::get_option_stock_quantity_for_warehouse($option, $warehouseId),
            'image' => $option->images->first()->image ?? null,
        ]));
    }
}
