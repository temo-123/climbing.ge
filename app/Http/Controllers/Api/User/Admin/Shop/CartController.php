<?php

namespace App\Http\Controllers\Api\User\Admin\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Shop\Cart;
use App\Models\Shop\Product;
use App\Models\Shop\Option_image;
use App\Models\Shop\Product_option;
use App\Models\Shop\Favorite_product;
use App\Models\User\User_adreses;
use App\Services\PermissionService;
use App\Services\ProductService;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user) return [];
        $cart_items = Cart::where('user_id', $user->id)->get();
        $products = [];

        foreach ($cart_items as $cart_item) {
            $option = Product_option::with('warehouse')->find($cart_item->option_id);
            if (!$option) continue;

            $product = Product::find($option->product_id);

            $product_image = Option_image::where('option_id', $option->id)->value('image') ?? '';

            $products[] = [
                "id"            => $cart_item->id,
                "user_id"       => $cart_item->user_id,
                "product"       => $product,
                "option"        => $option,
                "stock_quantity"=> ProductService::get_option_stock_quantity($option),
                "is_out_of_stock" => ProductService::is_option_out_of_stock($option, $product->sale_type ?? null),
                "quantity"      => $cart_item->quantity,
                "product_image" => $product_image,
            ];
        }

        return $products;
    }

    public function update_quantity(Request $request, $item_id)
    {
        $cart_item = Cart::where('id', $item_id)->where('user_id', $request->user()->id)->first();
        if (!$cart_item) {
            return response()->json(['error' => 'Cart item not found'], 404);
        }

        $requested_quantity = (int) $request->quantity;
        if ($requested_quantity < 1) {
            return response()->json(['error' => 'Invalid quantity'], 400);
        }

        $option = Product_option::with(['warehouse', 'product'])->find($cart_item->option_id);
        if ($option) {
            $stock = ProductService::get_option_stock_quantity($option);
            $is_unlimited_made_to_order = $stock <= 0
                && optional($option->product)->sale_type === 'produced_by_order';

            if (!$is_unlimited_made_to_order && $requested_quantity > $stock) {
                return response()->json([
                    'error' => 'Not enough stock available',
                    'available' => $stock,
                ], 400);
            }
        }

        $cart_item->quantity = $requested_quantity;
        $cart_item->save();

        return response()->json(['status' => 'ok']);
    }

    public function add_to_favorite(Request $request, $product_id)
    {
        $userId = $request->user()->id;

        if (!Favorite_product::where('user_id', $userId)->where('product_id', $product_id)->exists()) {
            $faworit = new Favorite_product();
            $faworit['user_id'] = $userId;
            $faworit['product_id'] = $product_id;
            $faworit->save();
        }
    }

    public function del_from_favorite(Request $request, $product_id)
    {
        $product = Favorite_product::where('user_id', $request->user()->id)
            ->where('product_id', $product_id)
            ->first();

        if ($product) {
            $product->delete();
        }
    }
    
    public function update(Request $request, $id)
    {
        $option_item = Product_option::with(['warehouse', 'product'])->where('id', '=', $request->modification_id)->first();
        if (!$option_item) {
            return response()->json(['error' => 'Product option not found'], 404);
        }

        $stock = ProductService::get_option_stock_quantity($option_item);
        $requested_quantity = (int) $request->quantity;

        // Made-to-order products with no general-warehouse stock are produced
        // on demand, so the stock cap doesn't apply — if the warehouse DOES
        // carry quantity, they're bought like a normal online_order option
        // and the usual stock cap below still applies.
        $is_unlimited_made_to_order = $stock <= 0
            && optional($option_item->product)->sale_type === 'produced_by_order';

        $cart_item = Cart::where('user_id', '=', $request->user()->id)->where('option_id', '=', $request->modification_id)->first();
        if ($cart_item) {
            $new_total = $cart_item->quantity + $requested_quantity;
            if (!$is_unlimited_made_to_order && $new_total > $stock) {
                return response()->json([
                    'error' => 'Not enough stock available',
                    'available' => $stock,
                ], 400);
            }
            $cart_item->quantity = $new_total;
            $cart_item->save();
        } else {
            if (!$is_unlimited_made_to_order && $requested_quantity > $stock) {
                return response()->json([
                    'error' => 'Not enough stock available',
                    'available' => $stock,
                ], 400);
            }
            $cart = new Cart;
            $cart['option_id'] = $request->modification_id;
            $cart['quantity'] = $requested_quantity;
            $cart['user_id'] = $request->user()->id;
            $cart->save();
        }
    }

    public function destroy($id, Request $request)
    {
        $item = Cart::where('id', '=', $id)->where('user_id', '=', $request->user()->id)->first();
        if ($item) {
            $item->delete();
        }
    }
}
