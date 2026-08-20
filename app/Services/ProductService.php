<?php

namespace App\Services;

use App\Models\Shop\Locale_product;
use App\Models\Shop\Product;
use App\Models\Shop\Option_image;
use App\Models\Shop\Product_image;
use App\Models\Shop\Product_option;
use App\Models\Shop\ProductOptionCombination;
use App\Models\Shop\CombinateProductOptionImage;

use App\Services\Abstract\LocaleContentService;

use Carbon\Carbon;
use App\Models\User\Warehouse;

class ProductService extends LocaleContentService
{
    /**
     * sale_types that aren't sold out of premade warehouse stock, so a
     * general-warehouse quantity of 0 doesn't mean "out of stock" for them —
     * produced_by_order ships on a made-to-order timeline, custom_production
     * is a fully bespoke request handled outside the cart entirely.
     */
    const NEVER_OUT_OF_STOCK_SALE_TYPES = ['produced_by_order', 'custom_production'];

    public static function get_locale_product_use_locale($global_product, $locale = 'en')
    {
        $reponce = array();

        $products = (new static)->get_locale_content_use_locale($global_product, Locale_product::class, '_product_id', $locale);

        foreach ($products as $product) {
            if($product['global_data']->product_options->count() > 0){

                // Eager load warehouse relationship for options to get stock quantities
                $product['global_data']->load(['product_options.warehouse']);

                $options = $product['global_data']->product_options;

                $discounted_prices = [];
                $original_prices = [];

                foreach ($options as $option) {
                    $price = $option->price;
                    $original_prices[] = $price;
                    if ($option->discount > 0) {
                        $discounted_price = $price - ($price * $option->discount / 100);
                        $discounted_prices[] = $discounted_price;
                    } else {
                        $discounted_prices[] = $price;
                    }
                }

                $old_min_price = min($original_prices);
                $old_max_price = max($original_prices);
                $new_min_price = min($discounted_prices);
                $new_max_price = max($discounted_prices);

                $has_discount = $options->contains('discount', '>', 0);
                $max_discount = $has_discount ? $options->max('discount') : 0;
                
                // Build product options array
                $product_options = [];
                $sale_type = $product['global_data']->sale_type;

                foreach($options as $option){
                    $product_image = [];
                    $product_images = Option_image::where('option_id', '=', $option->id)->get();
                    array_push($product_options, [
                        'option' => $option,
                        'images' => $product_images,
                        'stock_quantity' => self::get_option_stock_quantity($option),
                        'is_out_of_stock' => self::is_option_out_of_stock($option, $sale_type)
                    ]);
                }

                // Calculate if ALL options are out of stock
                $out_of_stock = self::is_all_options_out_of_stock($product['global_data']);

                if($has_discount){
                    array_push($reponce, [
                        "global_product"=>$product['global_data'],
                        "locale_product"=>$product['locale_data'],
                        // "category"=>$product['global_data']->product_category,
                        "max_price"=>$old_max_price,
                        "min_price"=>$old_min_price,

                        "reviews"=>ReitingService::colculate_stars($product['global_data']->feedbacks->where('published', 1)),

                        "new_max_price"=>$new_max_price,
                        "new_min_price"=>$new_min_price,

                        "product_images"=>(new static)->get_product_images($product['global_data']),
                        "has_discount" => $has_discount,
                        "max_discount" => $max_discount,
                        "product_option" => $product_options,
                        "out_of_stock" => $out_of_stock,
                    ]);
                }
                else{
                    array_push($reponce, [
                        "global_product"=>$product['global_data'],
                        "locale_product"=>$product['locale_data'],
                        // "category"=>$product['global_data']->product_category,
                        "max_price"=>(new static)->get_product_price($product['global_data'], 'max'),
                        "min_price"=>(new static)->get_product_price($product['global_data'], 'min'),
                        "product_images"=>(new static)->get_product_images($product['global_data']),
                        "has_discount" => $has_discount,
                        "product_option" => $product_options,
                        "out_of_stock" => $out_of_stock,
                    ]);
                }
            }
        }

        // Sort: in-stock products first, out-of-stock products last
        $reponce = collect($reponce)->sortBy(function($product) {
            return $product['out_of_stock'] ? 1 : 0;
        })->values()->all();

        return $reponce;
    }

    public static function get_locale_product_in_page_use_locale($product, $locale)
    {
        $product_data = [];
        $product_option = [];

        $product = (new static)->get_locale_content_in_page($product, Locale_product::class, '_product_id', $locale);

        $gallery_images = Product_image::where('product_id', '=', $product['global_data']->id)->get();

        $options = product_option::where('product_id', '=', $product['global_data']->id)->with('warehouse')->get();

        $sale_type = $product['global_data']->sale_type;

        foreach($options as $option){
            $product_images = Option_image::where('option_id', '=', $option->id)->get();
            array_push($product_option, [
                'option' => $option,
                'images' => $product_images,
                'stock_quantity' => self::get_option_stock_quantity($option),
                'is_out_of_stock' => self::is_option_out_of_stock($option, $sale_type)
            ]);
        }

        // Append combinations as additional selectable variants
        foreach (self::get_combination_options($product['global_data']->id) as $combo_entry) {
            array_push($product_option, $combo_entry);
        }

        $options = $product['global_data']->product_options;

        // Calculate if ALL options are out of stock
        $out_of_stock = self::is_all_options_out_of_stock($product['global_data']);

        $discounted_prices = [];
        $original_prices = [];

        foreach ($options as $option) {
            $price = $option->price;
            $original_prices[] = $price;
            if ($option->discount > 0) {
                $discounted_price = $price - ($price * $option->discount / 100);
                $discounted_prices[] = $discounted_price;
            } else {
                $discounted_prices[] = $price;
            }
        }

        $old_min_price = min($original_prices);
        $old_max_price = max($original_prices);
        $new_min_price = min($discounted_prices);
        $new_max_price = max($discounted_prices);

        $has_discount = $options->contains('discount', '>', 0);
        $max_discount = $has_discount ? $options->max('discount') : 0;

        if($has_discount){
            array_push($product_data, [
                "global_product"=>$product['global_data'],
                "locale_product"=>$product['locale_data'],
                "max_price"=>$old_max_price,
                "min_price"=>$old_min_price,

                "reviews"=>ReitingService::colculate_stars($product['global_data']->feedbacks->where('published', 1)),

                "new_max_price"=>$new_max_price,
                "new_min_price"=>$new_min_price,

                'product_option' => $product_option,
                'gallery_images' => $gallery_images,
                'has_discount' => $has_discount,
                'max_discount' => $max_discount,
                'out_of_stock' => $out_of_stock,
            ]);
        }
        else{
            array_push($product_data, [
                "global_product"=>$product['global_data'],
                "locale_product"=>$product['locale_data'],
                "max_price"=>(new static)->get_product_price($product['global_data'], 'max'),
                "min_price"=>(new static)->get_product_price($product['global_data'], 'min'),

                "reviews"=>ReitingService::colculate_stars($product['global_data']->feedbacks->where('published', 1)),

                'product_option' => $product_option,
                'gallery_images' => $gallery_images,
                'has_discount' => $has_discount,
                'out_of_stock' => $out_of_stock,
            ]);
        }

        return $product_data[0];
    }


    public static function get_new_product_pin($global_product)
    {
        $time_array = array();

        foreach ($global_product as $product) {
            if ($product[0][0]->created_at->lt(Carbon::now()->subDays(30))){
                $time = 0;
                array_push($time_array, ['id'=>$product[0][0]->id, 'name'=>$product['url_title'], 'time'=>$time]);
            } else {
                $time = 1;
                array_push($time_array, ['id'=>$product[0][0]->id, 'name'=>$product['url_title'], 'time'=>$time]);
            }
        }
        
        return $time_array;
    }

    public static function colculate_product_discount($old_price, $discount)
    {
        $price_x_sale = $discount * $old_price;
        $var_1 = $price_x_sale/100;
        return $old_price - $var_1;
    }

    public static function get_product_price($product, $interval)
    {
        // dd($product->product_options->count());
        $options_count = $product->product_options->count();

        if($options_count){
            $options = $product->product_options;
            $prices = [];

            foreach ($options as $option) {
                array_push($prices, $option->price);
            }

            if ($interval == 'max') {
                return max($prices);
            }
            else if ($interval == 'min') {
                return min($prices);
            }
            else {
                return 'error';
            }
        }
    }

    public static function get_product_images($product)
    {
        $product_images = [];

        $options = $product->product_options;

        foreach ($options as $option) {
            $images = Option_image::where('option_id', '=', $option -> id)->get();
            foreach ($images as $image) {
                array_push($product_images, $image->image);
            }

        }
        return $product_images;
    }
    

    public static function get_product_options_on_page($product_id)
    {
        $product_options = [];
        $product_images = [];

        $options = Product_option::where('product_id', '=', $product_id)->get();

        foreach ($options as $option) {
            $product_images = Option_image::where('option_id', '=', $option->id)->get();

            array_push($product_options, [
                'product_option' => $option,
                'product_images' => $product_images,
            ]);
        }

        return $product_options;
    }

    /**
     * Build combination entries in the same shape as product_option items so the
     * frontend can render them identically in the variant selector.
     */
    public static function get_combination_options($product_id): array
    {
        $combinations = ProductOptionCombination::where('product_id', $product_id)
            ->with(['options.warehouse', 'images'])
            ->get();

        $result = [];
        foreach ($combinations as $combo) {
            $stock = self::get_combination_stock_quantity($combo);
            $result[] = [
                'option'          => $combo,
                'images'          => $combo->images,
                'stock_quantity'  => $stock,
                'is_out_of_stock' => $stock <= 0 && !in_array(optional($combo->product)->sale_type, self::NEVER_OUT_OF_STOCK_SALE_TYPES, true),
                'is_combination'  => true,
                'combination_option_ids' => $combo->options->pluck('id')->toArray(),
            ];
        }
        return $result;
    }

    public static function get_combination_stock_quantity(ProductOptionCombination $combo): int
    {
        $options = $combo->options;
        if ($options->isEmpty()) return 0;

        $min = PHP_INT_MAX;
        foreach ($options as $option) {
            $general = $option->warehouse->where('general', 1)->first();
            $qty = $general ? (int)($general->pivot->quantity ?? 0) : 0;
            if ($qty < $min) $min = $qty;
        }
        return $min === PHP_INT_MAX ? 0 : $min;
    }

    /**
     * Get stock quantity for a product option from the general warehouse
     *
     * @param Product_option $option
     * @return int
     */
    public static function get_option_stock_quantity($option)
    {
        if (!$option) {
            return 0;
        }

        // Try to get from warehouse pivot table (general warehouse)
        $generalWarehouse = $option->warehouse->where('general', '=', 1)->first();
        if ($generalWarehouse && isset($generalWarehouse->pivot->quantity)) {
            return (int) $generalWarehouse->pivot->quantity;
        }

        // Fallback: check if option has direct quantity field
        if (isset($option->quantity)) {
            return (int) $option->quantity;
        }

        return 0;
    }

    /**
     * Get stock quantity for a product option from a SPECIFIC warehouse
     * (as opposed to get_option_stock_quantity(), which always reads the
     * general warehouse) — used by the warehouse-scoped custom-order flow.
     *
     * @param Product_option $option
     * @param int|null $warehouseId
     * @return int
     */
    public static function get_option_stock_quantity_for_warehouse($option, $warehouseId)
    {
        if (!$option || !$warehouseId) {
            return 0;
        }

        $warehouse = $option->warehouse->where('id', $warehouseId)->first();
        return $warehouse && isset($warehouse->pivot->quantity) ? (int) $warehouse->pivot->quantity : 0;
    }

    /**
     * Resolves which warehouse the CURRENTLY AUTHENTICATED user's sale should
     * be scoped to. A user carrying the 'warehouse'/'sell_own' permission is
     * restricted to their own assigned warehouse (or null if not yet
     * assigned — callers must handle that explicitly rather than silently
     * falling back to the general warehouse). Everyone else keeps today's
     * existing behavior: always the general warehouse.
     *
     * @param \App\Models\User $user
     * @return int|null
     */
    public static function resolveEffectiveWarehouseId($user)
    {
        if ($user && $user->hasPermissionFor('warehouse', 'sell_own')) {
            return optional($user->warehouseAssignment)->warehouse_id;
        }

        return Warehouse::where('general', 1)->value('id');
    }

    /**
     * Check if ALL options for a product are out of stock
     *
     * @param Product $product
     * @return bool
     */
    public static function is_all_options_out_of_stock($product)
    {
        $options = $product->product_options;

        if ($options->count() === 0) {
            // No options means product is not out of stock
            return false;
        }

        $sale_type = $product->sale_type;

        foreach ($options as $option) {
            if (!self::is_option_out_of_stock($option, $sale_type)) {
                // Found at least one option that is available, product is in stock
                return false;
            }
        }

        // All options have 0 or less quantity (and aren't made-to-order)
        return true;
    }

    /**
     * Whether a single option should be reported as out of stock.
     *
     * "produced_by_order" (made to order) and "custom_production" products are
     * never out of stock — zero general-warehouse quantity just means a
     * produced_by_order item ships on the made-to-order timeline instead of
     * immediately, and custom_production is a fully bespoke request that was
     * never backed by warehouse stock to begin with. If the general warehouse
     * DOES carry quantity for a produced_by_order option, it's available right
     * away just like a normal online_order option (the quantity check above
     * already covers that case, since a positive quantity is never "out of
     * stock").
     *
     * @param Product_option $option
     * @param string|null $sale_type
     * @return bool
     */
    public static function is_option_out_of_stock($option, $sale_type = null)
    {
        if (!$option) {
            return true;
        }

        if (self::get_option_stock_quantity($option) > 0) {
            return false;
        }

        $sale_type = $sale_type ?? optional($option->product)->sale_type;

        return !in_array($sale_type, self::NEVER_OUT_OF_STOCK_SALE_TYPES, true);
    }
}
