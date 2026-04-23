<?php

namespace App\Http\Controllers\Api\User\Admin\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Shop\Order;
use App\Models\Shop\Order_products;
use App\Models\Shop\CustomOrderAddress;
use App\Models\Shop\Product_option;
use App\Services\ProductService;

class CustomOrderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name'               => 'required|string|max:100',
            'surname'            => 'required|string|max:100',
            'email'              => 'nullable|email|max:255',
            'phone'              => 'nullable|string|max:50',
            'delivery_type'      => 'required|string',
            'payment_type'       => 'required|string',
            'order_product_list' => 'required|array|min:1',
            'order_product_list.*.product_option_id' => 'required|integer|exists:product_options,id',
            'order_product_list.*.product_id'        => 'required|integer|exists:products,id',
            'order_product_list.*.quantity'          => 'required|integer|min:1',
        ]);

        // Stock check unless production task override
        if (!$request->create_production_task) {
            foreach ($request->order_product_list as $item) {
                $option = Product_option::with('warehouse')->find($item['product_option_id']);
                $stock  = ProductService::get_option_stock_quantity($option);
                if ($stock < $item['quantity']) {
                    return response()->json([
                        'error' => 'Not enough stock for option ID ' . $item['product_option_id']
                    ], 400);
                }
            }
        }

        // Create the order
        $order = Order::create([
            'is_custom'            => true,
            'shiping'              => $request->delivery_type,
            'payment'              => $request->payment_type,
            'confirm'              => 1,
            'status'               => 'pending',
            'status_updating_data' => now(),
        ]);

        // Store buyer contact info
        $address = CustomOrderAddress::create([
            'name'    => $request->name,
            'surname' => $request->surname,
            'email'   => $request->email,
            'phone'   => $request->phone,
            'address' => $request->address,
            'map'     => $request->map,
            'city'    => $request->city,
            'country' => $request->country,
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
                $this->subtractStock($item['product_option_id'], $item['quantity']);
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
            'message'       => 'Custom order created successfully',
            'order_id'      => $order->id,
            'matched_users' => $matchedUsers->values(),
        ], 201);
    }

    public function index()
    {
        $orders = Order::where('is_custom', true)
            ->with(['buyerAddress', 'relatedUsers', 'orderProducts.option'])
            ->latest()
            ->get()
            ->map(function ($order) {
                $buyer = $order->buyerAddress->first();
                return [
                    'id'            => $order->id,
                    'status'        => $order->status,
                    'payment'       => $order->payment,
                    'shiping'       => $order->shiping,
                    'confirm'       => $order->confirm,
                    'created_at'    => $order->created_at,
                    'buyer_name'    => $buyer?->name,
                    'buyer_surname' => $buyer?->surname,
                    'buyer_email'   => $buyer?->email,
                    'buyer_phone'   => $buyer?->phone,
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
        $order = Order::where('id', $order_id)
            ->where('is_custom', true)
            ->with(['buyerAddress', 'relatedUsers', 'orderProducts'])
            ->firstOrFail();

        $buyer = $order->buyerAddress->first();

        return response()->json([
            'id'            => $order->id,
            'status'        => $order->status,
            'payment'       => $order->payment,
            'shiping'       => $order->shiping,
            'confirm'       => $order->confirm,
            'created_at'    => $order->created_at,
            'buyer_name'    => $buyer?->name,
            'buyer_surname' => $buyer?->surname,
            'buyer_email'   => $buyer?->email,
            'buyer_phone'   => $buyer?->phone,
            'buyer_address' => $buyer?->address,
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

    private function subtractStock(int $optionId, int $quantity): void
    {
        $option    = Product_option::find($optionId);
        $warehouse = $option?->warehouse->where('general', 1)->first();
        if ($warehouse) {
            $warehouse->pivot->quantity = max(0, $warehouse->pivot->quantity - $quantity);
            $warehouse->pivot->save();
        }
    }
}
