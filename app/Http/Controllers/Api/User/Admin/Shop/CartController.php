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
                "quantity"      => $cart_item->quantity,
                "product_image" => $product_image,
            ];
        }

        return $products;
    }

    public function update_quantity(Request $request)
    {
        $cart_item = Cart::where('id', '=', $request->item_id)->first();
        
        $cart_item['quantity'] = $request->quantity;
        
        $cart_item -> save();
    }

    public function add_to_favorite(Request $request)
    {
        $userId = $request->user()->id;

        if (Favorite_product::where('user_id', $userId)->where('product_id', $request->product_id)->count() > 0) {
            $editing_faworit = Favorite_product::where('user_id', $userId)->where('product_id', $request->product_id)->first();
            $editing_faworit['user_id'] = $userId;
            $editing_faworit['product_id'] = $request->product_id;
            $editing_faworit->save();
        } else {
            $faworit = new Favorite_product();
            $faworit['user_id'] = $userId;
            $faworit['product_id'] = $request->product_id;
            $faworit->save();
        }
    }

    public function del_from_favorite(Request $request)
    {
        $product = Favorite_product::where('user_id', $request->user()->id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($product) {
            $product->delete();
        }
    }
    
    public function create()
    {
        $request->user()->authorizeRoles(['user', 'manager', 'admin','ka_manager','us_manager','seller']);

        $userId = $request->user()->id;

        $option = Product_option::find($request->id);
        if (!$option || $option->quantity < 1) {
            return response()->json(['error' => 'Out of stock'], 400);
        }

        $old_cart_products = Cart::where('user_id', strip_tags($userId))->get();
        $old_cart_products_count = $old_cart_products->count();
        $is_update_cart_item = true;

        if ($old_cart_products_count > 0) {
            foreach ($old_cart_products as $old_cart_product) {
                if ($old_cart_product->option_id == $request->id) {
                    $editing_cart_item = Cart::where('user_id', strip_tags($userId))->where('option_id', strip_tags($request->id))->first();
                    $new_quantity = $old_cart_product->quantity + 1;

                    if ($new_quantity > $option->quantity) {
                        return response()->json(['error' => 'Not enough stock available'], 400);
                    }

                    $editing_cart_item['quantity'] = $new_quantity;
                    $editing_cart_item->update();
                    $is_update_cart_item = false;
                }
            }
            if ($is_update_cart_item) {
                $cart = new Cart();
                $cart['option_id'] = $request->id;
                $cart['user_id'] = $userId;
                $cart['quantity'] = 1;
                $cart->save();
            }
        } else {
            $cart = new Cart();
            $cart['option_id'] = $request->id;
            $cart['user_id'] = $userId;
            $cart['quantity'] = 1;
            $cart->save();
        }
    }

    public function edit($id)
    {        
        $cart_item = Cart::find($request->id);
        $cart_item->quantity = $request->quantity;
        $cart_item->update();
    }

    public function update(Request $request, $id)
    {
        $option_item = Product_option::with('warehouse')->where('id', '=', $request->modification_id)->first();
        if (!$option_item) {
            return response()->json(['error' => 'Product option not found'], 404);
        }

        $stock = ProductService::get_option_stock_quantity($option_item);
        $requested_quantity = (int) $request->quantity;

        $cart_item = Cart::where('user_id', '=', $request->user()->id)->where('option_id', '=', $request->modification_id)->first();
        if ($cart_item) {
            $new_total = $cart_item->quantity + $requested_quantity;
            if ($new_total > $stock) {
                return response()->json([
                    'error' => 'Not enough stock available',
                    'available' => $stock,
                ], 400);
            }
            $cart_item->quantity = $new_total;
            $cart_item->save();
        } else {
            if ($requested_quantity > $stock) {
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
