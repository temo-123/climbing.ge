<?php

namespace App\Http\Controllers\Api\User\Admin\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Shop\Order;
use App\Services\QuickShipperService;
use App\Services\PermissionService;

class QuickShipperController extends Controller
{
    // Quote rates for an order's address + products against QuickShipper. Payload shape is a placeholder.
    public function get_rates(Request $request, QuickShipperService $quickShipper)
    {
        if ($auth = PermissionService::authorize('quick_shipper', 'show')) return $auth;

        $request->validate([
            'order_id' => 'required|integer|exists:orders,id',
        ]);

        $order = Order::with('userAdres')->findOrFail($request->order_id);

        try {
            $rates = $quickShipper->getRates([
                'destination' => [
                    'city'    => optional($order->userAdres)->city,
                    'street'  => optional($order->userAdres)->strit,
                    'zip'     => optional($order->userAdres)->zip_code,
                ],
                'order_id' => $order->id,
            ]);
        } catch (\RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], 502);
        }

        return response()->json($rates);
    }

    // Create a shipment for an order and persist QuickShipper's shipment id/tracking/label on it
    public function create_shipment(Request $request, QuickShipperService $quickShipper)
    {
        $auth = PermissionService::authorize('quick_shipper', 'add');
        if ($auth) return $auth;

        $request->validate([
            'order_id' => 'required|integer|exists:orders,id',
            'rate_id'  => 'nullable|string',
        ]);

        $order = Order::with('userAdres')->findOrFail($request->order_id);

        try {
            $shipment = $quickShipper->createShipment([
                'order_id' => $order->id,
                'rate_id'  => $request->rate_id,
                'destination' => [
                    'city'   => optional($order->userAdres)->city,
                    'street' => optional($order->userAdres)->strit,
                    'zip'    => optional($order->userAdres)->zip_code,
                ],
            ]);
        } catch (\RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], 502);
        }

        $order->update([
            'quickshipper_shipment_id'      => $shipment['shipment_id'] ?? null,
            'quickshipper_tracking_number'  => $shipment['tracking_number'] ?? null,
            'quickshipper_label_url'        => $shipment['label_url'] ?? null,
            'quickshipper_status'           => $shipment['status'] ?? 'created',
        ]);

        return response()->json($shipment);
    }

    // Refresh a shipment's status from QuickShipper
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
            $shipment = $quickShipper->getShipment($order->quickshipper_shipment_id);
        } catch (\RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], 502);
        }

        if (isset($shipment['status'])) {
            $order->update(['quickshipper_status' => $shipment['status']]);
        }

        return response()->json($shipment);
    }
}
