<?php

namespace App\Http\Controllers\Api\User\Admin\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Shop\Order;
use App\Services\QuickShipperService;
use App\Services\PermissionService;

class QuickShipperController extends Controller
{
    // Quote the delivery fee for an order's destination against QuickShipper's pickup point.
    public function get_rates(Request $request, QuickShipperService $quickShipper)
    {
        if ($auth = PermissionService::authorize('quick_shipper', 'show')) return $auth;

        $request->validate([
            'order_id' => 'required|integer|exists:orders,id',
        ]);

        $order = Order::with('userAdres')->findOrFail($request->order_id);
        $pickup = config('services.quickshipper.pickup', []);

        try {
            $fees = $quickShipper->getFees([
                'FromCityName' => $pickup['city'] ?? '',
                'FromStreetName' => $pickup['address'] ?? '',
                'ToCityName' => optional($order->userAdres)->city,
                'ToStreetName' => optional($order->userAdres)->strit,
            ]);
        } catch (\RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], 502);
        }

        return response()->json($fees);
    }

    // Manually place a QuickShipper order for a shop order (normally done automatically —
    // see OrderController::edit_order_status() — this is for retrying a failed auto-send).
    public function create_shipment(Request $request, QuickShipperService $quickShipper)
    {
        $auth = PermissionService::authorize('quick_shipper', 'add');
        if ($auth) return $auth;

        $request->validate([
            'order_id' => 'required|integer|exists:orders,id',
        ]);

        $order = Order::findOrFail($request->order_id);

        try {
            $shipment = $quickShipper->placeOrderAndPersist($order);
        } catch (\RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], 502);
        }

        return response()->json($shipment);
    }

    // Refresh an order's status from QuickShipper
    public function get_shipment_status(Request $request, QuickShipperService $quickShipper)
    {
        if ($auth = PermissionService::authorize('quick_shipper', 'show')) return $auth;

        $request->validate([
            'order_id' => 'required|integer|exists:orders,id',
        ]);

        $order = Order::findOrFail($request->order_id);

        if (!$order->quickshipper_shipment_id) {
            return response()->json(['message' => 'Order has no QuickShipper shipment'], 422);
        }

        try {
            $info = $quickShipper->getOrderInfo((int) $order->quickshipper_shipment_id);
        } catch (\RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], 502);
        }

        if (isset($info['order']['status'])) {
            $order->update(['quickshipper_status' => $info['order']['status']]);
        }

        return response()->json($info);
    }
}
