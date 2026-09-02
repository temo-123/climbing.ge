<?php

namespace App\Http\Controllers\Api\User\Admin\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Shop\Order;
use App\Services\RsGeWaybillService;
use App\Services\PermissionService;

class RsGeWaybillController extends Controller
{
    // Manually (re)send an order's waybill to RS.ge — normally done automatically when an
    // order's status is set to "Ready to ship" (see OrderController::edit_order_status()).
    // This is for retrying one that failed automatically, or wasn't configured yet at the time.
    public function send_waybill(Request $request, RsGeWaybillService $rsGe)
    {
        $auth = PermissionService::authorize('rs_ge_waybill', 'add');
        if ($auth) return $auth;

        $request->validate([
            'order_id' => 'required|integer|exists:orders,id',
        ]);

        $order = Order::findOrFail($request->order_id);

        try {
            $result = $rsGe->sendWaybillForOrderAndPersist($order);
        } catch (\RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], 502);
        }

        return response()->json($result);
    }

    // Refresh a waybill's status from RS.ge.
    public function get_waybill_status(Request $request, RsGeWaybillService $rsGe)
    {
        if ($auth = PermissionService::authorize('rs_ge_waybill', 'show')) return $auth;

        $request->validate([
            'order_id' => 'required|integer|exists:orders,id',
        ]);

        $order = Order::findOrFail($request->order_id);

        if (!$order->rs_ge_waybill_id) {
            return response()->json(['message' => 'Order has no RS.ge waybill'], 422);
        }

        try {
            $waybill = $rsGe->getWaybill((int) $order->rs_ge_waybill_id);
        } catch (\RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], 502);
        }

        $status = $waybill->xpath(".//*[local-name()='status']")[0] ?? null;
        if ($status !== null) {
            $order->update(['rs_ge_waybill_status' => (string) $status]);
        }

        return response()->json(['waybill' => $waybill->asXML()]);
    }
}
