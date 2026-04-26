<?php

namespace App\Http\Controllers\Api\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Shop\Cart;
use App\Models\Shop\Product;
use App\Models\Shop\Option_image;
use App\Models\Shop\Product_option;
use App\Models\Shop\Favorite_product;
use App\Models\User\User_adreses;
use App\Services\ProductService;
use Auth;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()) {
            $user = Auth::user();
            $cart_items = Cart::where('user_id', '=', $user->id)->get();
            $products = array();
            $product_image = '';
            foreach ($cart_items as $cart_item) {

                $option = Product_option::where('id', strip_tags($cart_item->option_id))->get();

                foreach ($option as $opt) {
                    $product = Product::where('id', strip_tags($opt->product_id))->get();
                    // dd($product);
                
                    $images = Option_image::where('option_id', strip_tags($opt->id))->get();
                    $image_count = Option_image::where('option_id', strip_tags($opt->id))->count();
    
                    foreach($images as $image){
                        if ($image_count == 1) {
                            $product_image = $image->image;
                        }
                        if ($image_count == 1 && $image->general_image == NULL) {
                            # code...
                        } 
                        else {
                            # code...
                        }
                    }

                    array_push($products, [
                        "id"=>$cart_item->id,
                        "user_id"=>$cart_item->user_id,
                        "product"=>$product[0],
                        "option"=>$option[0],
                        "quantity"=>$cart_item->quantity,
                        "product_image" => $product_image,
                    ]);
                
                    // dd($products);
                    // echo $product_image;
                }
            }
            
            return $products;
        }
    }

    public function update_quantity(Request $request)
    {
        $cart_item = Cart::where('id', '=', $request->item_id)->first();
        
        $cart_item['quantity'] = $request->quantity;
        
        $cart_item -> save();
    }

    public function add_to_favorite(Request $request, $product_id)
    {
        $existing = Favorite_product::where('user_id', Auth::id())
            ->where('product_id', $product_id)
            ->first();

        if (!$existing) {
            $faworit = new Favorite_product();
            $faworit['user_id'] = Auth::id();
            $faworit['product_id'] = $product_id;
            $faworit->save();
        }

        return response()->json(['status' => 'ok']);
    }

    public function del_from_favorite(Request $request, $product_id)
    {
        $product = Favorite_product::where('user_id', Auth::id())
            ->where('product_id', $product_id)
            ->first();

        if ($product) {
            $product->delete();
            return response()->json(['status' => 'ok']);
        }

        return response()->json(['status' => 'not_found'], 404);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $request->user()->authorizeRoles(['user', 'manager', 'admin','ka_manager','us_manager','seller']);

        if (Auth::user()) {
            // Assume $request->id is option_id for stock check
            $option = Product_option::find($request->id);
            if (!$option || $option->quantity < 1) {
                return response()->json(['error' => 'Out of stock'], 400);
            }

            $old_cart_products = Cart::where('user_id', strip_tags(Auth::user()->id))->get();
            $old_cart_products_count = Cart::where('user_id', strip_tags(Auth::user()->id))->count();
            $is_update_cart_item = true;
            if ($old_cart_products_count > 0) {
                foreach ($old_cart_products as $old_cart_product) {
                    if ($old_cart_product->option_id == $request->id) {  // Changed to option_id
                        $editing_cart_item = Cart::where('user_id', strip_tags(Auth::user()->id))->where('option_id', strip_tags($request->id))->first();
                        $new_quantity = $old_cart_product->quantity + 1;

                        // Check if new quantity exceeds stock
                        if ($new_quantity > $option->quantity) {
                            return response()->json(['error' => 'Not enough stock available'], 400);
                        }

                        $editing_cart_item['quantity'] = $new_quantity;
                        $editing_cart_item -> update();

                        $is_update_cart_item = false;
                    }
                }
                if($is_update_cart_item) {
                    $cart = new Cart();

                    $cart['option_id'] = $request->id;  // Changed to option_id
                    $cart['user_id'] = Auth::user()->id;
                    $cart['quantity'] = 1;

                    $cart -> save();
                }
            }
            else {
                $cart = new Cart();

                $cart['option_id'] = $request->id;  // Changed to option_id
                $cart['user_id'] = Auth::user()->id;
                $cart['quantity'] = 1;

                $cart -> save();
            }
        }
        else {
            return 'login';
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

        $cart_item = Cart::where('user_id', '=', Auth::user()->id)->where('option_id', '=', $request->modification_id)->first();
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
            $cart['user_id'] = Auth::user()->id;
            $cart->save();
        }
    }

    public function destroy($id, Request $request)
    {
        if ($request->isMethod('delete')) {
            $item = cart::where('id', '=', $id)->first();
            $item -> delete();
        }
    }
}
