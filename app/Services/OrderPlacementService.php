<?php

namespace App\Services;

use App\Models\PartnerOrganization\PartnerOrganizationMember;
use App\Models\Shop\CustomOrderAddress;
use App\Models\Shop\Order;
use App\Models\Shop\Order_products;
use App\Models\Shop\Product_option;
use App\Models\Shop\Sale_code;
use App\Models\Shop\Shiped_region;
use App\Models\User;
use App\Models\User\User_adreses;
use App\Models\User\Warehouse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * The ONE way an order is created — website checkout (OrderController::
 * create_order), manual / quick-sale orders (CustomOrderController::store)
 * and the legacy OrderController::add_custom_order all go through place(),
 * so every order gets the same validation, price snapshot, shipping rule,
 * stock reservation and buyer linking (docs/FUNCTIONS_DOCS/shop/order_placement.md).
 *
 * Reading an order back (totals, delivery, labels) is OrderService.
 *
 * place() runs in a single DB transaction: any failure throws
 * OrderPlacementException and nothing is written — no order, no lines, no
 * stock taken, no sale code used up. Notifications are the caller's job
 * (after commit), because who gets mailed depends on the flow.
 */
class OrderPlacementService
{
    const KIND_WEBSITE = 'website';
    const KIND_MANUAL  = 'manual';

    // Stored payment codes per flow (legacy spellings kept — existing rows and
    // the checkout UI use them). OrderService::payment_method() normalises them.
    const WEBSITE_PAYMENTS = ['deliverd payment', 'online payment'];
    const MANUAL_PAYMENTS  = ['deliverd_payment', 'mony_transfer', 'online_payment'];
    const DELIVERY_METHODS = ['delivery', 'self_delivery'];

    // users.phone_number with the usual formatting stripped, so "+995 599 12-34-56"
    // matches a typed "599123456".
    const PHONE_DIGITS_SQL = "REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(phone_number, ' ', ''), '-', ''), '+', ''), '(', ''), ')', ''), '.', '')";

    /**
     * Customer checkout from the cart.
     *
     * @param array $items [['product_id', 'product_option_id', 'quantity'], ...]
     */
    public static function place_website(User $buyer, int $address_id, array $items, string $payment, ?string $sale_code = null): Order
    {
        $address = User_adreses::where('id', $address_id)->where('user_id', $buyer->id)->first();
        if (!$address) {
            throw new OrderPlacementException('Invalid delivery address', 'invalid_address');
        }
        if (!in_array($payment, self::WEBSITE_PAYMENTS, true)) {
            throw new OrderPlacementException('Invalid payment method', 'invalid_payment');
        }

        $code = $sale_code ? self::valid_sale_code($sale_code) : null;
        // Discounts don't stack — whichever benefits the customer most.
        $discount = max((float) (self::partner_for_user($buyer->id)['discount'] ?? 0), (float) ($code->discount ?? 0));

        return self::place([
            'kind'            => self::KIND_WEBSITE,
            'user_id'         => $buyer->id,
            'adres_id'        => $address->id,
            'delivery_method' => 'delivery',
            'region'          => Shiped_region::find($address->region_id),
            'payment'         => $payment,
            'items'           => $items,
            'warehouse_id'    => Warehouse::where('general', 1)->value('id'),
            'made_to_order_without_stock' => true,
            'discount'        => $discount,
            'status'          => 'pending',
            'confirm'         => 0,
            'sale_code'       => $code,
        ]);
    }

    /**
     * Staff-created order (admin panel / sale point / quick sale).
     *
     * @param array $buyer   name, surname, email, phone, address, city, country, map, region_id
     * @param array $options delivery_method, payment, items, warehouse_id, skip_stock,
     *                       delivery_price (manual override or null), linked_user_id
     * @return array{order: Order, linked_users: \Illuminate\Support\Collection, partner: ?array}
     */
    public static function place_manual(array $buyer, array $options): array
    {
        $method  = $options['delivery_method'] ?? 'self_delivery';
        $payment = $options['payment'] ?? null;

        if (!in_array($method, self::DELIVERY_METHODS, true)) {
            throw new OrderPlacementException('Invalid delivery method', 'invalid_delivery_method');
        }
        if (!in_array($payment, self::MANUAL_PAYMENTS, true)) {
            throw new OrderPlacementException('Invalid payment method', 'invalid_payment');
        }
        // Pay Now is settled on the spot — always picked up / arranged directly.
        if ($payment === 'online_payment') {
            $method = 'self_delivery';
        }

        $region = null;
        if ($method === 'delivery') {
            $region = !empty($buyer['region_id']) ? Shiped_region::find($buyer['region_id']) : null;
            if (!$region) {
                throw new OrderPlacementException('Please select a shipping region for delivery orders.', 'region_required');
            }
        }

        $linked_user = null;
        if (!empty($options['linked_user_id'])) {
            $linked_user = User::find($options['linked_user_id']);
            if (!$linked_user) {
                throw new OrderPlacementException('Linked user account not found', 'linked_user_not_found');
            }
        }

        // Partner discount: from the buyer's contact details, or the linked
        // account's organization — the bigger one, never from the client.
        $partner = self::partner_for_contact($buyer['email'] ?? null, $buyer['name'] ?? null, $buyer['surname'] ?? null, $buyer['phone'] ?? null);
        if ($linked_user) {
            $by_user = self::partner_for_user($linked_user->id);
            if ($by_user && (float) $by_user['discount'] > (float) ($partner['discount'] ?? 0)) {
                $partner = $by_user;
            }
        }

        $order = self::place([
            'kind'            => self::KIND_MANUAL,
            'user_id'         => null,
            'buyer'           => $buyer,
            'delivery_method' => $method,
            'region'          => $region,
            'payment'         => $payment,
            'items'           => $options['items'] ?? [],
            'warehouse_id'    => $options['warehouse_id'] ?? null,
            'skip_stock'      => !empty($options['skip_stock']),
            'delivery_price'  => $options['delivery_price'] ?? null,
            'discount'        => (float) ($partner['discount'] ?? 0),
            'status'          => $payment === 'online_payment' ? 'Order Complete' : 'pending',
            'confirm'         => 1,
            'link_user_ids'   => self::accounts_to_link($buyer, $linked_user),
        ]);

        return [
            'order'        => $order,
            'linked_users' => $order->relatedUsers()->get(['users.id', 'name', 'surname', 'email']),
            'partner'      => $partner,
        ];
    }

    /**
     * Shared core. $d keys: kind, user_id, adres_id, buyer, delivery_method,
     * region, payment, items, warehouse_id, skip_stock, made_to_order_without_stock,
     * delivery_price, discount, status, confirm, sale_code, link_user_ids.
     */
    public static function place(array $d): Order
    {
        $lines = self::normalise_items($d['items'] ?? []);

        return DB::transaction(function () use ($d, $lines) {
            // Options + prices from the DB, never from the client.
            $options = Product_option::with(['warehouse', 'product'])
                ->whereIn('id', array_column($lines, 'product_option_id'))
                ->get()->keyBy('id');

            $subtotal = 0.0;
            foreach ($lines as $i => $line) {
                $option = $options->get($line['product_option_id']);
                if (!$option) {
                    throw new OrderPlacementException('Product option ' . $line['product_option_id'] . ' not found', 'option_not_found');
                }
                if ((int) $option->product_id !== $line['product_id']) {
                    throw new OrderPlacementException('Option ' . $option->id . ' does not belong to product ' . $line['product_id'], 'option_product_mismatch');
                }
                // The option's own sale discount applies first (same price the
                // shop shows); order-level discounts (partner / code) then apply
                // to this subtotal.
                $price = ProductService::option_sale_price($option);
                $lines[$i]['item_price']    = $price;
                $lines[$i]['list_price']    = round((float) $option->price, 2);
                $lines[$i]['item_discount'] = ProductService::option_discount_percent($option);
                $lines[$i]['total_price']   = round($price * $line['quantity'], 2);
                $subtotal += $lines[$i]['total_price'];
            }

            $is_website = $d['kind'] === self::KIND_WEBSITE;

            // Shipping: region rule (minimum order / free above threshold).
            $shipping = 0.0;
            if ($d['delivery_method'] === 'delivery' && !empty($d['region'])) {
                $rule = OrderService::region_shipping($d['region'], $subtotal);
                if ($rule['below_min']) {
                    throw new OrderPlacementException(OrderService::below_min_message($rule), 'below_min_order');
                }
                $shipping = $rule['cost'];
                $override = $d['delivery_price'] ?? null;
                if (!$is_website && $override !== null && $override !== '') {
                    if (!is_numeric($override) || $override < 0) {
                        throw new OrderPlacementException('Invalid delivery price', 'invalid_delivery_price');
                    }
                    $shipping = round((float) $override, 2);
                }
            }

            // Stock: atomic conditional decrement per line; remember where it came from.
            foreach ($lines as $i => $line) {
                $lines[$i]['stock_warehouse_id'] = null;
                if (!empty($d['skip_stock'])) continue;

                $option = $options->get($line['product_option_id']);
                $warehouse_id = $d['warehouse_id'] ?? null;

                if (!empty($d['made_to_order_without_stock'])
                    && optional($option->product)->sale_type === 'produced_by_order'
                    && ProductService::get_option_stock_quantity_for_warehouse($option, $warehouse_id) <= 0) {
                    continue; // produced on demand — nothing to take
                }

                if (!$warehouse_id || !self::take_stock($warehouse_id, $option->id, $line['quantity'])) {
                    throw new OrderPlacementException('Not enough stock for: ' . ($option->name ?: $option->id), 'out_of_stock');
                }
                $lines[$i]['stock_warehouse_id'] = $warehouse_id;
            }

            $order = new Order;
            $order->forceFill([
                'is_custom'            => !$is_website,
                'user_id'              => $d['user_id'] ?? null,
                'adres_id'             => $d['adres_id'] ?? null,
                'warehouse_id'         => $is_website ? null : ($d['warehouse_id'] ?? null),
                // Website rows keep the cost in `shiping`, manual rows the method
                // (+ delivery_price) — see OrderService.
                'shiping'              => $is_website ? $shipping : $d['delivery_method'],
                'delivery_price'       => $is_website ? null : ($d['delivery_method'] === 'delivery' ? $shipping : null),
                'payment'              => $d['payment'],
                'discount'             => $d['discount'] ?? 0,
                'status'               => $d['status'] ?? 'pending',
                'confirm'              => $d['confirm'] ?? 0,
                'status_updating_data' => now(),
            ])->save();

            foreach ($lines as $line) {
                Order_products::create([
                    'order_id'           => $order->id,
                    'product_id'         => $line['product_id'],
                    'product_option_id'  => $line['product_option_id'],
                    'quantity'           => $line['quantity'],
                    'item_price'         => $line['item_price'],
                    'list_price'         => $line['list_price'],
                    'item_discount'      => $line['item_discount'],
                    'total_price'        => $line['total_price'],
                    'stock_warehouse_id' => $line['stock_warehouse_id'],
                ]);
            }

            if (!$is_website) {
                $b = $d['buyer'] ?? [];
                $address = CustomOrderAddress::create([
                    'name'      => $b['name'] ?? null,
                    'surname'   => $b['surname'] ?? null,
                    'email'     => $b['email'] ?? null,
                    'phone'     => $b['phone'] ?? null,
                    'address'   => $d['delivery_method'] === 'delivery' ? ($b['address'] ?? null) : null,
                    'city'      => $b['city'] ?? null,
                    'country'   => $b['country'] ?? null,
                    'map'       => $b['map'] ?? null,
                    'region_id' => $d['delivery_method'] === 'delivery' ? ($d['region']->id ?? null) : null,
                ]);
                $order->buyerAddress()->attach($address->id);
            }

            if (!empty($d['link_user_ids'])) {
                $order->relatedUsers()->sync(array_values(array_unique($d['link_user_ids'])));
            }

            if (!empty($d['sale_code']) && $d['sale_code']->one_time_code) {
                $d['sale_code']->used_at = now();
                $d['sale_code']->save();
            }

            return $order;
        });
    }

    /**
     * Give back the stock place() took for this order (failed / abandoned online
     * payment). Only lines with a recorded stock_warehouse_id, and each only
     * once — the column is cleared as it's returned.
     */
    public static function release_stock(Order $order): int
    {
        return DB::transaction(function () use ($order) {
            $released = 0;
            $lines = Order_products::where('order_id', $order->id)->whereNotNull('stock_warehouse_id')->lockForUpdate()->get();
            foreach ($lines as $line) {
                DB::table('warehouses_product_options')
                    ->where('warehouse_id', $line->stock_warehouse_id)
                    ->where('product_option_id', $line->product_option_id)
                    ->increment('quantity', $line->quantity);
                $line->stock_warehouse_id = null;
                $line->save();
                $released += $line->quantity;
            }
            return $released;
        });
    }

    /**
     * Read-only preview of what place_website() would charge — the checkout
     * pages show this instead of re-doing the maths in the browser
     * (POST set_order/checkout_quote). Same rules: DB prices, region rule,
     * bigger of partner / sale-code discount, general-warehouse stock.
     * Nothing is written; problems come back in `issues` instead of throwing.
     */
    public static function quote_website(User $buyer, ?int $address_id, array $items, ?string $sale_code = null, string $locale = 'en'): array
    {
        $issues = [];
        try {
            $lines = $items ? self::normalise_items($items) : [];
        } catch (OrderPlacementException $e) {
            $lines = [];
            $issues[] = ['code' => $e->reason, 'message' => $e->getMessage()];
        }
        if (!$lines && !$issues) $issues[] = ['code' => 'empty'];

        $general_wh = Warehouse::where('general', 1)->value('id');
        $options = Product_option::with(['warehouse', 'product.us_product:id,title', 'product.ka_product:id,title'])
            ->whereIn('id', array_column($lines, 'product_option_id'))->get()->keyBy('id');
        $images = DB::table('option_images')->whereIn('option_id', $options->keys())->orderBy('id')->get(['option_id', 'image'])
            ->groupBy('option_id')->map(fn($g) => $g->first()->image);

        $out_lines = [];
        $subtotal = 0.0;
        foreach ($lines as $line) {
            $option = $options->get($line['product_option_id']);
            if (!$option || (int) $option->product_id !== $line['product_id']) {
                $issues[] = ['code' => 'option_not_found', 'option_id' => $line['product_option_id']];
                continue;
            }
            $product   = $option->product;
            $price     = ProductService::option_sale_price($option);
            $total     = round($price * $line['quantity'], 2);
            $stock     = ProductService::get_option_stock_quantity_for_warehouse($option, $general_wh);
            $made      = optional($product)->sale_type === 'produced_by_order';
            $on_demand = $made && $stock <= 0;
            $short     = !$on_demand && $stock < $line['quantity'];
            if ($short) $issues[] = ['code' => 'out_of_stock', 'option_id' => $option->id, 'available' => max(0, $stock)];

            $title = $locale === 'ka'
                ? ($product?->ka_product?->title ?: $product?->us_product?->title)
                : ($product?->us_product?->title ?: $product?->ka_product?->title);

            $out_lines[] = [
                'product_id'     => $line['product_id'],
                'option_id'      => $option->id,
                'title'          => $title ?: ($product->url_title ?? '—'),
                'url_title'      => $product->url_title ?? null,
                'option_name'    => $option->name,
                'image'          => $images[$option->id] ?? null,
                'quantity'       => $line['quantity'],
                'unit_price'     => $price,
                'list_price'     => round((float) $option->price, 2),
                'item_discount'  => ProductService::option_discount_percent($option),
                'line_total'     => $total,
                'made_to_order'  => $made,
                'available'      => $on_demand ? null : max(0, $stock),
                'stock_ok'       => !$short,
            ];
            $subtotal += $total;
        }
        $subtotal = round($subtotal, 2);

        // Discount: the bigger of partner / sale code (they don't stack).
        $partner = self::partner_for_user($buyer->id);
        $code    = $sale_code ? self::valid_sale_code($sale_code) : null;
        $partner_pct = (float) ($partner['discount'] ?? 0);
        $code_pct    = (float) ($code->discount ?? 0);
        $discount_pct = max($partner_pct, $code_pct);
        $source = $discount_pct <= 0 ? null : ($code_pct > $partner_pct ? 'code' : 'partner');
        $code_status = !$sale_code ? null : (!$code ? 'invalid' : ($code_pct > $partner_pct ? 'applied' : 'not_better'));
        $discount_amount = $discount_pct > 0 ? round($subtotal * $discount_pct / 100, 2) : 0.0;

        // Shipping: region rule of the chosen address.
        $address = $address_id ? User_adreses::where('id', $address_id)->where('user_id', $buyer->id)->first() : null;
        $shipping = null; $region = null;
        if ($address_id && !$address) $issues[] = ['code' => 'invalid_address'];
        if (!$address_id) $issues[] = ['code' => 'no_address'];
        if ($address) {
            $r = Shiped_region::find($address->region_id);
            $rule = OrderService::region_shipping($r, $subtotal);
            $shipping = $rule['cost'];
            $region = [
                'id'             => $r->id ?? null,
                'name'           => $rule['region'],
                'price'          => $r ? (float) $r->shiping_price : 0.0,
                'min_order'      => $rule['min_order'],
                'free_after'     => $rule['free_after'],
                'is_free'        => $rule['is_free'],
                'below_min'      => $rule['below_min'],
                'missing_for_min'  => $rule['below_min'] ? round($rule['min_order'] - $subtotal, 2) : 0.0,
                'missing_for_free' => ($rule['free_after'] && !$rule['is_free'] && $rule['cost'] > 0) ? round($rule['free_after'] - $subtotal, 2) : 0.0,
            ];
            if ($rule['below_min']) $issues[] = ['code' => 'below_min_order', 'missing' => $region['missing_for_min']];
        }

        return [
            'lines'    => $out_lines,
            'items_count' => array_sum(array_column($out_lines, 'quantity')),
            'currency' => '₾',
            'subtotal' => $subtotal,
            // What the options' own sale discounts already took off (shown as "you save").
            'sale_savings' => round(array_sum(array_map(fn($l) => ($l['list_price'] - $l['unit_price']) * $l['quantity'], $out_lines)), 2),
            'discount' => [
                'percent'      => $discount_pct,
                'amount'       => $discount_amount,
                'source'       => $source,
                'partner'      => $partner,
                'code'         => $sale_code ? ['code' => $sale_code, 'status' => $code_status, 'percent' => $code_pct] : null,
            ],
            'shipping' => $shipping,
            'region'   => $region,
            'total'    => round($subtotal - $discount_amount + (float) $shipping, 2),
            'made_to_order' => (bool) array_filter(array_column($out_lines, 'made_to_order')),
            'issues'   => $issues,
            'can_place' => !$issues,
        ];
    }

    // ───────────── helpers (also used by the controllers' preview endpoints) ─────────────

    /**
     * Accepts both payload shapes: checkout's [{product:{id}, option:{id}, quantity}]
     * and manual [{product_id, product_option_id, quantity}].
     * Same option twice is merged.
     */
    public static function normalise_items(array $items): array
    {
        $merged = [];
        foreach ($items as $item) {
            $option_id  = (int) ($item['product_option_id'] ?? ($item['option']['id'] ?? 0));
            $product_id = (int) ($item['product_id'] ?? ($item['product']['id'] ?? 0));
            $quantity   = $item['quantity'] ?? 0;

            if (!$option_id || !$product_id) {
                throw new OrderPlacementException('Every item needs a product and an option', 'invalid_item');
            }
            if (!is_numeric($quantity) || (int) $quantity != $quantity || (int) $quantity < 1) {
                throw new OrderPlacementException('Quantity must be a whole number of at least 1', 'invalid_quantity');
            }

            if (isset($merged[$option_id])) {
                $merged[$option_id]['quantity'] += (int) $quantity;
            } else {
                $merged[$option_id] = ['product_id' => $product_id, 'product_option_id' => $option_id, 'quantity' => (int) $quantity];
            }
        }
        if (!$merged) {
            throw new OrderPlacementException('Cart is empty', 'empty');
        }
        return array_values($merged);
    }

    private static function take_stock(int $warehouse_id, int $option_id, int $quantity): bool
    {
        return DB::table('warehouses_product_options')
            ->where('warehouse_id', $warehouse_id)
            ->where('product_option_id', $option_id)
            ->where('quantity', '>=', $quantity)
            ->decrement('quantity', $quantity) > 0;
    }

    /**
     * Accounts a manual order is linked to (orders_user): the one the staff
     * member picked, plus an account whose e-mail or phone matches exactly
     * (unique). Name + surname alone is NOT enough — namesakes would get
     * linked — those only show up as suggestions in the buyer search.
     */
    public static function accounts_to_link(array $buyer, ?User $picked = null): array
    {
        $ids = [];
        if ($picked) $ids[] = $picked->id;

        if (!empty($buyer['email'])) {
            $id = User::where('email', trim($buyer['email']))->value('id');
            if ($id) $ids[] = $id;
        }
        $phone = self::digits($buyer['phone'] ?? null);
        if ($phone && strlen($phone) >= 6) {
            $matches = User::whereNotNull('phone_number')->whereRaw(self::PHONE_DIGITS_SQL . ' like ?', ['%' . substr($phone, -9)])->limit(2)->pluck('phone_number', 'id')
                ->filter(fn($p) => substr(self::digits($p), -9) === substr($phone, -9));
            if ($matches->count() === 1) $ids[] = $matches->keys()->first();
        }
        return array_values(array_unique($ids));
    }

    public static function digits(?string $v): string
    {
        return preg_replace('/\D+/', '', (string) $v);
    }

    public static function valid_sale_code(?string $code): ?Sale_code
    {
        if (!$code) return null;
        $sale_code = Sale_code::where('code', $code)->first();
        if (!$sale_code) return null;
        if ($sale_code->one_time_code && $sale_code->used_at) return null;
        if (!$sale_code->one_time_code && $sale_code->action_data && Carbon::parse($sale_code->action_data)->isPast()) return null;
        return $sale_code;
    }

    /** ['discount', 'organization_name'] for the user's partner organization, or null. */
    public static function partner_for_user(?int $user_id): ?array
    {
        if (!$user_id) return null;
        $member = PartnerOrganizationMember::with('organization')->where('user_id', $user_id)->first();
        return $member && $member->organization
            ? ['discount' => (float) $member->organization->discount, 'organization_name' => $member->organization->name]
            : null;
    }

    /** Partner organization by buyer contact: e-mail, then phone, then name + surname. */
    public static function partner_member_by_contact(?string $email, ?string $name, ?string $surname, ?string $phone = null): ?PartnerOrganizationMember
    {
        $q = fn() => PartnerOrganizationMember::with('organization');
        if ($email && ($m = $q()->where('email', $email)->first())) return $m;
        if ($phone && ($m = $q()->where('phone_number', $phone)->first())) return $m;
        if ($name && $surname) return $q()->where('name', $name)->where('surname', $surname)->first();
        return null;
    }

    public static function partner_for_contact(?string $email, ?string $name, ?string $surname, ?string $phone = null): ?array
    {
        $member = self::partner_member_by_contact($email, $name, $surname, $phone);
        return $member && $member->organization
            ? ['discount' => (float) $member->organization->discount, 'organization_name' => $member->organization->name]
            : null;
    }

    /**
     * Buyer search for the manual-order form: users matching any typed
     * field. Public-safe columns only, plus partner org and order count.
     */
    public static function search_buyers(array $q, int $limit = 8): array
    {
        $name    = trim((string) ($q['name'] ?? ''));
        $surname = trim((string) ($q['surname'] ?? ''));
        $email   = trim((string) ($q['email'] ?? ''));
        $phone   = self::digits($q['phone'] ?? null);

        if (mb_strlen($name) < 2 && mb_strlen($surname) < 2 && mb_strlen($email) < 3 && strlen($phone) < 4) {
            return [];
        }

        $users = User::query()
            ->where(function ($w) use ($name, $surname, $email, $phone) {
                if (mb_strlen($email) >= 3)   $w->orWhere('email', 'like', '%' . addcslashes($email, '%_') . '%');
                if (strlen($phone) >= 4)      $w->orWhereRaw(self::PHONE_DIGITS_SQL . ' like ?', ['%' . substr($phone, -9) . '%']);
                if (mb_strlen($name) >= 2 && mb_strlen($surname) >= 2) {
                    $w->orWhere(fn($n) => $n->where('name', 'like', addcslashes($name, '%_') . '%')->where('surname', 'like', addcslashes($surname, '%_') . '%'));
                } elseif (mb_strlen($name) >= 2) {
                    $w->orWhere('name', 'like', addcslashes($name, '%_') . '%');
                } elseif (mb_strlen($surname) >= 2) {
                    $w->orWhere('surname', 'like', addcslashes($surname, '%_') . '%');
                }
            })
            ->with('partner_organization_member.organization:id,name,discount')
            ->limit(30)
            ->get(['id', 'name', 'surname', 'email', 'phone_number', 'image', 'city']);

        // Rank: exact e-mail / phone first, then full-name match, then partial.
        $ranked = $users->map(function ($u) use ($name, $surname, $email, $phone) {
            $score = 0; $reasons = [];
            if ($email && strcasecmp($u->email, $email) === 0) { $score += 100; $reasons[] = 'email'; }
            elseif ($email && stripos((string) $u->email, $email) !== false) { $score += 20; $reasons[] = 'email'; }
            $up = self::digits($u->phone_number);
            if ($phone && $up && substr($up, -9) === substr($phone, -9)) { $score += 90; $reasons[] = 'phone'; }
            elseif ($phone && $up && str_contains($up, substr($phone, -9))) { $score += 15; $reasons[] = 'phone'; }
            if ($name && mb_strtolower($u->name) === mb_strtolower($name)) { $score += 30; $reasons[] = 'name'; }
            if ($surname && mb_strtolower($u->surname) === mb_strtolower($surname)) { $score += 30; $reasons[] = 'surname'; }
            if (!$reasons) { $score += 5; $reasons[] = 'partial'; }

            $org = optional($u->partner_organization_member)->organization;
            return [
                'id'           => $u->id,
                'name'         => $u->name,
                'surname'      => $u->surname,
                'email'        => $u->email,
                'phone_number' => $u->phone_number,
                'city'         => $u->city,
                'image'        => $u->image,
                'organization' => $org ? ['name' => $org->name, 'discount' => (float) $org->discount] : null,
                'matched_by'   => array_values(array_unique($reasons)),
                'exact'        => $score >= 90,
                'score'        => $score,
            ];
        })->sortByDesc('score')->take($limit)->values();

        $counts = DB::table('orders')->whereIn('user_id', $ranked->pluck('id'))->selectRaw('user_id, count(*) n')->groupBy('user_id')->pluck('n', 'user_id');
        $linked = DB::table('orders_user')->whereIn('user_id', $ranked->pluck('id'))->selectRaw('user_id, count(*) n')->groupBy('user_id')->pluck('n', 'user_id');

        return $ranked->map(fn($u) => $u + ['orders_count' => (int) ($counts[$u['id']] ?? 0) + (int) ($linked[$u['id']] ?? 0)])->all();
    }
}
