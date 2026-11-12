<?php

namespace App\Http\Controllers\Api\Shop;

use App\Http\Controllers\Controller;
use App\Models\Shop\Order;
use App\Services\OrderReviewService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Public review page from the "order complete" email (shop /order_review/{id}/{token}).
 * The token (OrderReviewService::token) is the only access check — no login.
 */
class OrderReviewController extends Controller
{
    // GET order_review/get/{order_id}?token=…&locale=ka|en
    public function show(Request $request, $order_id)
    {
        $order = Order::find((int) $order_id);
        if (!$order || !OrderReviewService::valid_token($order, $request->query('token'))) {
            return response()->json(['message' => 'This review link is not valid.'], 404);
        }

        $locale = $request->query('locale') === 'ka' ? 'ka' : 'en';
        return response()->json(OrderReviewService::page($order, $locale));
    }

    // POST order_review/save/{order_id}  {token, order:{stars,delivery_speed,packaging,text}, products:[{product_id,stars,text}]}
    public function save(Request $request, $order_id)
    {
        $order = Order::find((int) $order_id);
        if (!$order || !OrderReviewService::valid_token($order, $request->input('token'))) {
            return response()->json(['message' => 'This review link is not valid.'], 404);
        }
        if ($order->status !== OrderReviewService::COMPLETE_STATUS) {
            return response()->json(['message' => 'You can review the order once it is complete.'], 422);
        }

        $validator = Validator::make($request->all(), [
            'order'                  => 'nullable|array',
            'order.stars'            => 'nullable|integer|between:1,5',
            'order.delivery_speed'   => 'nullable|integer|between:1,5',
            'order.packaging'        => 'nullable|integer|between:1,5',
            'order.text'             => 'nullable|string|max:1000',
            'products'               => 'nullable|array|max:50',
            'products.*.product_id'  => 'required|integer',
            'products.*.stars'       => 'required|integer|between:1,5',
            'products.*.text'        => 'nullable|string|max:500',
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $result = OrderReviewService::save($order, $validator->validated());
        $locale = $request->input('locale') === 'ka' ? 'ka' : 'en';

        return response()->json(array_merge($result, OrderReviewService::page($order, $locale)));
    }
}
