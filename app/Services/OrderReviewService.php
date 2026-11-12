<?php

namespace App\Services;

use App\Models\Shop\Order;
use App\Models\Shop\Order_review;
use App\Models\Shop\Product;
use App\Models\Shop\Product_feedback;
use App\Models\Shop\User_product_feedbacks;
use App\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * Review page sent with the "order complete" email (shop /order_review/{id}/{token}).
 * One page, two kinds of review:
 *  - order review (delivery / service) → order_reviews, shown to admins only (orders list);
 *  - product reviews → product_feedbacks (published, have_product = 1), shown on product pages.
 * Access is the HMAC token from the email — no login needed (custom orders have no account).
 * Docs: docs/FUNCTIONS_DOCS/shop/order_review.md
 */
class OrderReviewService
{
    public const COMPLETE_STATUS = 'Order Complete';

    public static function token(Order $order): string
    {
        return hash_hmac('sha256', 'order_review|' . $order->id . '|' . $order->created_at, config('app.key'));
    }

    public static function valid_token(Order $order, $token): bool
    {
        return is_string($token) && $token !== '' && hash_equals(self::token($order), $token);
    }

    public static function url(Order $order, ?string $lang = null): string
    {
        $base = rtrim(env('APP_SSH', 'https://') . env('SHOP_URL', 'shop.climbing.ge'), '/');
        return $base . ($lang === 'ka' ? '/ka' : '') . '/order_review/' . $order->id . '/' . self::token($order);
    }

    /** ['user' => ?User, 'email', 'name', 'surname', 'lang'] — custom orders keep the buyer in custom_order_addresses. */
    public static function buyer(Order $order): array
    {
        if ($order->is_custom) {
            $address = $order->buyerAddress()->first();
            $user = $address && $address->email ? User::where('email', $address->email)->first() : null;
            return [
                'user'    => $user,
                'email'   => $address->email ?? null,
                'name'    => $address->name ?? ($user->name ?? ''),
                'surname' => $address->surname ?? ($user->surname ?? ''),
                'lang'    => $user->lang ?? 'us',
            ];
        }

        $user = $order->user_id ? User::find($order->user_id) : null;
        return [
            'user'    => $user,
            'email'   => $user->email ?? null,
            'name'    => $user->name ?? '',
            'surname' => $user->surname ?? '',
            'lang'    => $user->lang ?? 'us',
        ];
    }

    /** Distinct products of the order with title / image / link and whether this buyer already reviewed them. */
    public static function products(Order $order, string $locale, ?string $email): array
    {
        $items = OrderService::line_items($order, $locale);
        $ids = collect($items)->pluck('product.id')->filter()->unique()->values();
        if ($ids->isEmpty()) return [];

        $products = Product::whereIn('id', $ids)->get()->keyBy('id');
        $reviews = $email
            ? Product_feedback::where('email', $email)->whereIn('product_id', $ids)->whereNull('deleted_reason')
                ->get(['id', 'product_id', 'stars', 'text'])->keyBy('product_id')
            : collect();

        $out = [];
        foreach ($items as $item) {
            $pid = $item['product']['id'] ?? null;
            if (!$pid || isset($out[$pid])) continue;
            $product = $products->get($pid);
            $review = $reviews->get($pid);
            $out[$pid] = [
                'product_id' => $pid,
                'url_title'  => $item['product']['url_title'] ?? null,
                'title'      => $item['title'],
                'options'    => [],
                'image'      => $product ? (collect($product->photo_urls(1))->first() ?: null) : null,
                'reviewed'   => (bool) $review,
                'review'     => $review ? ['stars' => (int) $review->stars, 'text' => $review->text] : null,
            ];
        }
        // option names, so "Rope 9.5 mm — 60 m" reads right
        foreach ($items as $item) {
            $pid = $item['product']['id'] ?? null;
            if ($pid && isset($out[$pid]) && $item['option_name'] && !in_array($item['option_name'], $out[$pid]['options'], true)) {
                $out[$pid]['options'][] = $item['option_name'];
            }
        }
        return array_values($out);
    }

    public static function page(Order $order, string $locale): array
    {
        $buyer = self::buyer($order);
        $review = $order->review()->first();

        return [
            'order' => [
                'id'         => $order->id,
                'created_at' => (string) $order->created_at,
                'status'     => $order->status,
                'complete'   => $order->status === self::COMPLETE_STATUS,
            ],
            'buyer'        => ['name' => $buyer['name']],
            'order_review' => $review ? $review->only(['stars', 'delivery_speed', 'packaging', 'text', 'created_at']) : null,
            'products'     => self::products($order, $locale, $buyer['email']),
        ];
    }

    /**
     * $input: order => {stars, delivery_speed?, packaging?, text?} (optional),
     *         products => [{product_id, stars, text}] (optional; only this order's products, once each).
     * Returns ['order_saved' => bool, 'products_saved' => int].
     */
    public static function save(Order $order, array $input): array
    {
        $buyer = self::buyer($order);
        $result = ['order_saved' => false, 'products_saved' => 0];

        DB::transaction(function () use ($order, $input, $buyer, &$result) {
            $o = $input['order'] ?? null;
            if (is_array($o) && !empty($o['stars']) && !$order->review()->exists()) {
                Order_review::create([
                    'order_id'       => $order->id,
                    'user_id'        => $buyer['user']->id ?? null,
                    'stars'          => (int) $o['stars'],
                    'delivery_speed' => !empty($o['delivery_speed']) ? (int) $o['delivery_speed'] : null,
                    'packaging'      => !empty($o['packaging']) ? (int) $o['packaging'] : null,
                    'text'           => isset($o['text']) && trim((string) $o['text']) !== '' ? trim(strip_tags((string) $o['text'])) : null,
                ]);
                $result['order_saved'] = true;
            }

            if (!$buyer['email']) return;   // product reviews are tied to the buyer's e-mail

            $allowed = collect(self::products($order, 'en', $buyer['email']))->keyBy('product_id');
            foreach (($input['products'] ?? []) as $p) {
                $pid = (int) ($p['product_id'] ?? 0);
                $row = $allowed->get($pid);
                if (!$row || $row['reviewed'] || empty($p['stars'])) continue;

                $text = trim(strip_tags((string) ($p['text'] ?? '')));
                $id = Product_feedback::insertGetId([
                    'name'         => mb_substr($buyer['name'] ?: 'Customer', 0, 25),
                    'surname'      => mb_substr($buyer['surname'] ?? '', 0, 55),
                    'email'        => $buyer['email'],
                    'text'         => $text,
                    'stars'        => (int) $p['stars'],
                    'published'    => 1,          // verified buyer (came from the order e-mail)
                    'have_product' => 1,
                    'admin_hidden' => 0,
                    'product_id'   => $pid,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]);
                if ($buyer['user']) {
                    $link = new User_product_feedbacks;
                    $link['feedback_id'] = $id;
                    $link['user_id'] = $buyer['user']->id;
                    $link->save();
                }
                $allowed->put($pid, array_merge($row, ['reviewed' => true]));
                $result['products_saved']++;
            }
        });

        return $result;
    }
}
