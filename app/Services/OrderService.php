<?php

namespace App\Services;

use App\Models\Shop\Order;
use App\Models\Shop\Order_products;
use App\Models\Shop\Product;
use App\Models\Shop\Product_option;
use App\Models\Shop\Shiped_region;
use App\Models\User\Warehouse;

/**
 * One place that reads an order's money, delivery and type, for the order
 * detail modals, the admin orders tables and the order invoice PDF
 * (docs/FUNCTIONS_DOCS/shop/order_details_invoice.md).
 *
 * `orders.shiping` means different things per order kind:
 *  - website (cart) order: the shipping COST, always delivered to `adres_id`
 *  - manual (admin / sale-point) order: the METHOD 'delivery' | 'self_delivery',
 *    with the cost in `delivery_price` and the buyer in custom_order_addresses
 * Always go through shipping_method() / shipping_cost() instead of reading it.
 *
 * Totals: products subtotal − discount % (on products) + shipping — the same
 * formula the checkout, the custom-order modal and the customer modal show.
 * `paid_amount` is what TBC actually charged (orders.payment_amount), shown
 * separately when present.
 */
class OrderService
{
    const TYPE_WEBSITE = 'website';
    const TYPE_MANUAL  = 'manual';

    public static function type(Order $order): string
    {
        return $order->is_custom ? self::TYPE_MANUAL : self::TYPE_WEBSITE;
    }

    /** 'delivery' | 'self_delivery' */
    public static function shipping_method(Order $order): string
    {
        if ($order->is_custom) {
            return $order->shiping === 'delivery' ? 'delivery' : 'self_delivery';
        }
        return 'delivery';
    }

    public static function shipping_cost(Order $order): float
    {
        if ($order->is_custom) {
            return self::shipping_method($order) === 'delivery' ? round((float) $order->delivery_price, 2) : 0.0;
        }
        return is_numeric($order->shiping) ? round((float) $order->shiping, 2) : 0.0;
    }

    /**
     * The shipping-region rule, used by OrderPlacementService::place() for
     * website checkout and manual orders alike:
     *  - products subtotal (before discount) below `ship_min_price` → can't deliver
     *  - subtotal at/above `free_shiping_price_after` → free
     *  - otherwise `shiping_price` (0 when the region has none)
     */
    public static function region_shipping(?Shiped_region $region, float $subtotal): array
    {
        if (!$region) {
            return ['cost' => 0.0, 'is_free' => false, 'below_min' => false, 'min_order' => null, 'free_after' => null, 'region' => null];
        }

        $min        = $region->ship_min_price ? (float) $region->ship_min_price : null;
        $free_after = $region->free_shiping_price_after ? (float) $region->free_shiping_price_after : null;
        $price      = (float) ($region->shiping_price ?? 0);
        $is_free    = $price > 0 && $free_after !== null && $subtotal >= $free_after;

        return [
            'cost'       => $is_free ? 0.0 : round($price, 2),
            'is_free'    => $is_free,
            'below_min'  => $min !== null && $subtotal < $min,
            'min_order'  => $min,
            'free_after' => $free_after,
            'region'     => $region->region,
        ];
    }

    public static function below_min_message(array $rule): string
    {
        return 'Minimum order amount for delivery to ' . $rule['region'] . ' is ' . $rule['min_order'] . ' ₾';
    }

    /**
     * Normalised payment code: 'cash_on_delivery' | 'money_transfer' | 'online' | raw value.
     * Old rows mix "deliverd payment" / "deliverd_payment" / "online payment".
     */
    public static function payment_method(Order $order): ?string
    {
        $map = [
            'deliverd payment' => 'cash_on_delivery',
            'deliverd_payment' => 'cash_on_delivery',
            'mony_transfer'    => 'money_transfer',
            'online payment'   => 'online',
            'online_payment'   => 'online',
        ];
        return $map[$order->payment] ?? $order->payment;
    }

    /**
     * Order lines with localized product title, snapshotted unit price and line total.
     * Keeps the legacy `product` / `option` / `quantity` / `item_price` / `total_price`
     * keys the modals already read.
     */
    public static function line_items(Order $order, string $locale = 'en'): array
    {
        $rows = Order_products::where('order_id', $order->id)->get();
        $options  = Product_option::whereIn('id', $rows->pluck('product_option_id'))->get()->keyBy('id');
        $products = Product::with(['us_product:id,title', 'ka_product:id,title'])
            ->whereIn('id', $rows->pluck('product_id')->merge($options->pluck('product_id'))->unique())
            ->get()->keyBy('id');

        $items = [];
        foreach ($rows as $row) {
            $option  = $options->get($row->product_option_id);
            $product = $products->get($row->product_id) ?? ($option ? $products->get($option->product_id) : null);
            if (!$option && !$product) continue;

            $quantity   = (int) $row->quantity;
            // Price snapshotted at order creation; the option's current price
            // is only a fallback for orders placed before snapshots existed.
            $unit_price = $row->item_price !== null ? (float) $row->item_price : ProductService::option_sale_price($option);
            $line_total = $row->total_price !== null ? (float) $row->total_price : round($unit_price * $quantity, 2);

            $title = $locale === 'ka'
                ? ($product?->ka_product?->title ?: $product?->us_product?->title)
                : ($product?->us_product?->title ?: $product?->ka_product?->title);

            $items[] = [
                'id'          => $row->id,
                'product'     => $product ? $product->only(['id', 'url_title', 'sale_type']) : null,
                'option'      => $option ? $option->only(['id', 'name', 'price', 'currency']) : null,
                'title'       => $title ?: ($product->url_title ?? '—'),
                'option_name' => $option->name ?? null,
                'quantity'    => $quantity,
                'item_price'  => round($unit_price, 2),
                // Regular price + the option's sale discount at order time (NULL on
                // lines placed before 2026-11-09 — then no "was" price is shown).
                'list_price'    => $row->list_price !== null ? (float) $row->list_price : null,
                'item_discount' => $row->item_discount !== null ? (float) $row->item_discount : 0.0,
                'total_price' => round($line_total, 2),
                'currency'    => ($option->currency ?? null) ?: '₾',
            ];
        }
        return $items;
    }

    public static function totals(Order $order, ?array $items = null): array
    {
        $items = $items ?? self::line_items($order);

        $subtotal         = round(array_sum(array_column($items, 'total_price')), 2);
        $discount_percent = (float) ($order->discount ?? 0);
        $discount_amount  = $discount_percent > 0 ? round($subtotal * $discount_percent / 100, 2) : 0.0;
        $shipping         = self::shipping_cost($order);

        return [
            'items_count'      => array_sum(array_column($items, 'quantity')),
            'subtotal'         => $subtotal,
            'discount_percent' => $discount_percent,
            'discount_amount'  => $discount_amount,
            'shipping'         => $shipping,
            'total'            => round($subtotal - $discount_amount + $shipping, 2),
            'paid_amount'      => $order->payment_amount !== null ? round((float) $order->payment_amount, 2) : null,
            'currency'         => $items[0]['currency'] ?? '₾',
        ];
    }

    /**
     * Who receives the order and where, as ready-to-show fields.
     * Website orders: users + user_adreses (+ region). Manual orders:
     * custom_order_addresses (+ region); pickup orders name the warehouse.
     */
    public static function delivery(Order $order): array
    {
        $method = self::shipping_method($order);

        $out = [
            'method'      => $method,
            'cost'        => self::shipping_cost($order),
            'recipient'   => null,
            'phone'       => null,
            'email'       => null,
            'street'      => null,  // street + number
            'details'     => [],    // [['key' => 'entrance'|'floor'|'flat', 'value' => …]]
            'city'        => null,
            'region'      => null,
            'country'     => null,
            'zip'         => null,
            'map'         => null,
            'label'       => null,  // the address' own name ("Home", "Office")
            'pickup_from' => null,  // warehouse name for self_delivery
        ];

        if ($order->is_custom) {
            $a = $order->buyerAddress()->with('region:id,region')->first();
            if ($a) {
                $out['recipient'] = trim(($a->name ?? '') . ' ' . ($a->surname ?? '')) ?: null;
                $out['phone']     = $a->phone;
                $out['email']     = $a->email;
                $out['street']    = $a->address;
                $out['city']      = $a->city;
                $out['country']   = $a->country;
                $out['zip']       = $a->zip;
                $out['map']       = $a->map;
                $out['region']    = $a->region->region ?? null;
            }
            if ($method === 'self_delivery' && $order->warehouse_id) {
                $out['pickup_from'] = Warehouse::where('id', $order->warehouse_id)->value('name');
            }
            return $out;
        }

        $user = $order->user()->select('id', 'name', 'surname', 'email', 'phone_number')->first();
        $out['recipient'] = $user ? trim($user->name . ' ' . $user->surname) : null;
        $out['phone']     = $user->phone_number ?? null;
        $out['email']     = $user->email ?? null;

        $a = $order->userAdres;
        if ($a) {
            $out['label']  = $a->name;
            $out['street'] = trim(($a->strit ?? '') . ' ' . ($a->number ?? '')) ?: null;
            $out['city']   = $a->city;
            $out['zip']    = $a->zip_code;
            $out['map']    = $a->map;
            $out['region'] = $a->region_id ? Shiped_region::where('id', $a->region_id)->value('region') : null;
            foreach (['entrance', 'floor', 'flat'] as $k) {
                if (!empty($a->{$k})) $out['details'][] = ['key' => $k, 'value' => $a->{$k}];
            }
        }
        return $out;
    }

    /**
     * Flat, display-ready columns for the orders tables. Expects
     * `orderProducts.option` eager-loaded (no per-order queries).
     */
    public static function list_summary(Order $order): array
    {
        $items = $order->orderProducts->map(function ($row) {
            $unit = $row->item_price !== null ? (float) $row->item_price : ProductService::option_sale_price($row->option);
            return [
                'quantity'    => (int) $row->quantity,
                'total_price' => $row->total_price !== null ? (float) $row->total_price : round($unit * (int) $row->quantity, 2),
                'currency'    => ($row->option->currency ?? null) ?: '₾',
            ];
        })->all();
        $totals = self::totals($order, $items);
        return [
            'order_type'      => self::type($order),
            'shipping_method' => self::shipping_method($order),
            'shipping_cost'   => $totals['shipping'],
            'payment_method'  => self::payment_method($order),
            'items_count'     => $totals['items_count'],
            'order_total'     => $totals['total'],
        ];
    }

    // ───────────── Invoice PDF ─────────────

    public static function company(): array
    {
        return [
            'name'              => env('COMPANY_LEGAL_NAME', 'climbing.ge'),
            'tax_id'            => env('COMPANY_TAX_ID', ''),
            'address'           => env('COMPANY_ADDRESS', ''),
            'phone'             => env('COMPANY_PHONE', ''),
            'email'             => env('COMPANY_EMAIL', ''),
            'bank_name'         => env('COMPANY_BANK_NAME', ''),
            'bank_iban'         => env('COMPANY_BANK_IBAN', ''),
            'bank_account_name' => env('COMPANY_BANK_ACCOUNT_NAME', ''),
            'bank_code'         => env('COMPANY_BANK_CODE', ''),
        ];
    }

    /** View data for resources/views/pdf/custom_order_invoice.blade.php, built from a saved order. */
    public static function invoice_data(Order $order, string $locale = 'ka'): array
    {
        $locale   = in_array($locale, ['en', 'ka'], true) ? $locale : 'ka';
        $items    = self::line_items($order, $locale);
        $totals   = self::totals($order, $items);
        $delivery = self::delivery($order);
        $labels   = self::invoice_labels($locale);

        $address_line = implode(', ', array_filter([
            $delivery['street'],
            implode(', ', array_map(fn($d) => $labels[$d['key']] . ' ' . $d['value'], $delivery['details'])),
            $delivery['city'],
            $delivery['region'] && $delivery['region'] !== $delivery['city'] ? $delivery['region'] : null,
            $delivery['zip'],
        ]));

        $payment = self::payment_method($order);

        return [
            'buyer' => [
                'name'    => $delivery['recipient'],
                'surname' => '',
                'email'   => $delivery['email'],
                'phone'   => $delivery['phone'],
                'address' => $address_line ?: null,
                'city'    => null,
            ],
            'company'          => self::company(),
            'labels'           => $labels,
            'locale'           => $locale,
            'invoice_number'   => 'INV-' . str_pad($order->id, 6, '0', STR_PAD_LEFT),
            'order_number'     => $order->id,
            'line_items'       => array_map(fn($i) => [
                'title'      => $i['title'],
                'option'     => $i['option_name'],
                'quantity'   => $i['quantity'],
                'unit_price' => $i['item_price'],
                'list_price' => $i['list_price'],
                'item_discount' => $i['item_discount'],
                'line_total' => $i['total_price'],
                'currency'   => $i['currency'],
            ], $items),
            'subtotal'         => $totals['subtotal'],
            'discount_percent' => $totals['discount_percent'],
            'discount_amount'  => $totals['discount_amount'],
            'partner_name'     => null,
            'shipping'         => $totals['shipping'],
            'show_shipping'    => true,
            'total'            => $totals['total'],
            'paid_amount'      => $totals['paid_amount'],
            'delivery_method'  => $labels['method_' . $delivery['method']],
            'pickup_from'      => $delivery['pickup_from'],
            'payment_method'   => $labels['pay_' . $payment] ?? $payment,
            'invoice_date'     => optional($order->created_at)->format('Y-m-d') ?? now()->format('Y-m-d'),
        ];
    }

    public static function invoice_labels(string $locale): array
    {
        if ($locale === 'ka') {
            return [
                'invoice'          => 'ინვოისი',
                'invoice_number'   => 'ინვოისის ნომერი',
                'order_number'     => 'შეკვეთის ნომერი',
                'date'             => 'თარიღი',
                'seller'           => 'გამყიდველი',
                'billed_to'        => 'მყიდველი',
                'tax_id'           => 'საიდ. კოდი',
                'phone'            => 'ტელეფონი',
                'email'            => 'ელ. ფოსტა',
                'product'          => 'პროდუქტი',
                'option'           => 'ვარიანტი',
                'qty'              => 'რაოდ.',
                'unit_price'       => 'ერთეულის ფასი',
                'line_total'       => 'ჯამი',
                'subtotal'         => 'პროდუქტების ჯამი',
                'discount'         => 'ფასდაკლება',
                'shipping'         => 'მიწოდება',
                'free'             => 'უფასო',
                'total'            => 'გადასახდელი სულ',
                'paid'             => 'გადახდილია ონლაინ',
                'delivery_method'  => 'მიწოდების მეთოდი',
                'payment_method'   => 'გადახდის მეთოდი',
                'pickup_from'      => 'გატანა',
                'method_delivery'  => 'მიწოდება მისამართზე',
                'method_self_delivery' => 'თვითგატანა',
                'pay_cash_on_delivery' => 'გადახდა მიღებისას',
                'pay_money_transfer'   => 'საბანკო გადარიცხვა',
                'pay_online'           => 'ონლაინ გადახდა',
                'entrance'         => 'სადარბაზო',
                'floor'            => 'სართული',
                'flat'             => 'ბინა',
                'payment_details'  => 'გადახდის დეტალები',
                'bank'             => 'ბანკი',
                'account_holder'   => 'ანგარიშის მფლობელი',
                'iban'             => 'ანგარიშის ნომერი (IBAN)',
                'bank_code'        => 'ბანკის კოდი',
                'payment_note'     => 'გადახდისას გთხოვთ მიუთითოთ ინვოისის ნომერი დანიშნულებაში.',
                'thank_you'        => 'გმადლობთ შეკვეთისთვის!',
                'generated_by'     => 'დოკუმენტი გენერირებულია climbing.ge ადმინ პანელიდან',
            ];
        }

        return [
            'invoice'          => 'Invoice',
            'invoice_number'   => 'Invoice number',
            'order_number'     => 'Order number',
            'date'             => 'Date',
            'seller'           => 'Seller',
            'billed_to'        => 'Billed to',
            'tax_id'           => 'Tax ID',
            'phone'            => 'Phone',
            'email'            => 'Email',
            'product'          => 'Product',
            'option'           => 'Option',
            'qty'              => 'Qty',
            'unit_price'       => 'Unit Price',
            'line_total'       => 'Total',
            'subtotal'         => 'Products subtotal',
            'discount'         => 'Discount',
            'shipping'         => 'Shipping',
            'free'             => 'Free',
            'total'            => 'Total Due',
            'paid'             => 'Paid online',
            'delivery_method'  => 'Delivery method',
            'payment_method'   => 'Payment method',
            'pickup_from'      => 'Pickup from',
            'method_delivery'  => 'Delivery to address',
            'method_self_delivery' => 'Self pickup',
            'pay_cash_on_delivery' => 'Cash on delivery',
            'pay_money_transfer'   => 'Bank transfer',
            'pay_online'           => 'Online payment',
            'entrance'         => 'Entrance',
            'floor'            => 'Floor',
            'flat'             => 'Apt.',
            'payment_details'  => 'Payment Details',
            'bank'             => 'Bank',
            'account_holder'   => 'Account Holder',
            'iban'             => 'Account Number (IBAN)',
            'bank_code'        => 'Bank Code',
            'payment_note'     => 'Please reference the invoice number in the payment description.',
            'thank_you'        => 'Thank you for your order!',
            'generated_by'     => 'Generated by the climbing.ge admin panel',
        ];
    }
}
