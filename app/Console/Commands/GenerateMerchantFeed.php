<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Shop\Product;
use App\Models\Shop\Product_option;
use App\Services\ProductService;

/**
 * Generates a Google Merchant Center product feed (public/google-merchant-feed.xml)
 * so published products can appear in free Google Shopping listings — a channel
 * this shop wasn't in at all before, on top of (not instead of) normal organic
 * search results. No UI/UX surface: this is a machine-readable feed only, meant
 * to be registered once in Merchant Center against this URL.
 *
 * Spec: https://support.google.com/merchants/answer/7052112
 */
class GenerateMerchantFeed extends Command
{
    protected $signature   = 'app:generate-merchant-feed';
    protected $description = 'Generate the Google Merchant Center product feed for shop.climbing.ge';

    public function handle(): void
    {
        // APP_SSH already includes the scheme's trailing "://" (e.g. "https://"),
        // so it concatenates directly with the bare domain — same pattern as
        // GenerateSitemap.php. Do NOT rtrim('/') this before joining: that
        // collapses "https://" down to "https:" and produces a malformed
        // single-slash URL once the domain is appended back on.
        $ssh     = env('APP_SSH', 'https://');
        $shopUrl = trim(env('SHOP_URL', 'shop.climbing.ge'), '/');
        $base    = $ssh . $shopUrl;

        $items = '';
        $count = 0;

        Product::where('published', true)
            ->with(['us_product', 'brand.us_brand'])
            ->get()
            ->each(function (Product $product) use (&$items, &$count, $base) {
                $content = $product->us_product;
                $title   = $content?->title;
                if (! $title) {
                    // No usable title to list — skip rather than submit a
                    // blank/garbage entry Merchant Center would reject anyway.
                    return;
                }

                $option = Product_option::where('product_id', $product->id)->orderBy('price')->first();
                if (! $option || ! $option->price) {
                    // No priced option means nothing purchasable to list.
                    return;
                }

                $finalPrice = $option->discount > 0
                    ? round($option->price - ($option->discount * $option->price / 100), 2)
                    : (float) $option->price;
                $currency = match ($option->currency) {
                    '₾', 'GEL' => 'GEL',
                    '$', 'USD' => 'USD',
                    '€', 'EUR' => 'EUR',
                    default    => 'GEL',
                };

                $inStock     = ProductService::get_option_stock_quantity($option) > 0;
                $isOnRequest = in_array($product->sale_type, ProductService::NEVER_OUT_OF_STOCK_SALE_TYPES, true);
                $availability = $inStock ? 'in stock' : ($isOnRequest ? 'backorder' : 'out of stock');

                // Real product photos (on the options). A generic site image gets an item
                // disapproved, so a product without any photo is left out of the feed.
                $photos = $product->photo_urls(6);
                if (! $photos) {
                    return;
                }
                $image = array_shift($photos);

                $brand = $product->brand?->us_brand?->title ?: 'climbing.ge';
                // same encoding as the page's canonical (SeoService::canonicalUrl)
                $link  = $base . '/product/' . rawurlencode($product->url_title);
                $desc  = $this->description($content, $title);

                $items .= "<item>\n"
                    . '<g:id>' . e($product->id) . "</g:id>\n"
                    . '<g:title>' . e($title) . "</g:title>\n"
                    . '<g:description>' . e($desc) . "</g:description>\n"
                    . '<g:link>' . e($link) . "</g:link>\n"
                    . '<g:image_link>' . e($image) . "</g:image_link>\n"
                    . implode('', array_map(fn ($url) => '<g:additional_image_link>' . e($url) . "</g:additional_image_link>\n", $photos))
                    . '<g:availability>' . $availability . "</g:availability>\n"
                    . '<g:price>' . number_format($finalPrice, 2, '.', '') . ' ' . $currency . "</g:price>\n"
                    . '<g:brand>' . e($brand) . "</g:brand>\n"
                    . "<g:condition>new</g:condition>\n"
                    // These are handmade/small-batch/imported climbing goods without
                    // manufacturer GTINs or MPNs on file — telling Google that up
                    // front (rather than omitting the fields) avoids the feed being
                    // silently disapproved for "missing required identifiers".
                    . "<g:identifier_exists>no</g:identifier_exists>\n"
                    . "<g:google_product_category>Sporting Goods &gt; Outdoor Recreation &gt; Climbing</g:google_product_category>\n"
                    . "</item>\n";

                $count++;
            });

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n"
            . '<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">' . "\n"
            . "<channel>\n"
            . "<title>climbing.ge shop</title>\n"
            . '<link>' . e($base) . "</link>\n"
            . "<description>Climbing gear, services and tours in Georgia</description>\n"
            . $items
            . "</channel>\n"
            . "</rss>\n";

        file_put_contents(public_path('google-merchant-feed.xml'), $xml);

        $this->info("Merchant feed generated with {$count} product(s).");
    }

    /**
     * Item description: the short description, or — when that is empty / a "." placeholder —
     * the product text, then the title. Plain text, ≤ 5000 chars (Merchant Center limit).
     */
    private function description($content, string $title): string
    {
        $plain = function (?string $html): string {
            $html = preg_replace('#<(br|/?p|/?div|/?li|/?h[1-6])\b[^>]*>#i', ' ', (string) $html);
            $text = html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5, 'UTF-8');
            return trim(preg_replace('/[\s\x{00A0}]+/u', ' ', $text));
        };
        $meaningful = fn (string $t) => preg_match_all('/\p{L}{2,}/u', $t) >= 2;

        foreach ([$content?->short_description, $content?->text] as $candidate) {
            $text = $plain($candidate);
            if ($meaningful($text)) {
                return mb_substr($text, 0, 5000);
            }
        }
        return $title;
    }
}
