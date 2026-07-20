<?php

namespace App\Http\Controllers\Api\Shop\Payment;

use App\Http\Controllers\Controller;
use App\Models\Shop\Order;
use App\Models\Shop\Order_products;
use App\Models\Shop\Product_option;
use App\Services\TbcPaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderPaymentController extends Controller
{
    private TbcPaymentService $tbc;

    public function __construct()
    {
        $this->tbc = new TbcPaymentService('shop');
    }

    // POST /api/payment/shop/initiate/{order_id}
    public function initiate(int $order_id)
    {
        if (!$this->tbc->isConfigured()) {
            return response()->json(['error' => 'Online payment is currently unavailable.'], 503);
        }

        $user = Auth::user();
        if (!$user) return response()->json(['error' => 'Unauthenticated'], 401);

        $order = Order::where('id', $order_id)->where('user_id', $user->id)->first();
        if (!$order) return response()->json(['error' => 'Order not found'], 404);

        if ($order->tbc_pay_id) {
            return response()->json(['error' => 'Payment already initiated for this order'], 400);
        }

        $amount = $this->calculateOrderTotal($order);

        $userBase = rtrim(env('APP_SSH', 'http://') . env('USER_PAGE_URL', 'user.climbing.ge'), '/');

        $result = $this->tbc->createOrder([
            'amount'            => ['currency' => 'GEL', 'total' => $amount],
            'intent'            => 'CAPTURE',
            'returnUrl'         => $userBase . '/payment/success?order_id=' . $order_id,
            'cancelUrl'         => $userBase . '/payment/cancel?order_id=' . $order_id,
            'callbackUrl'       => url('/api/payment/shop/callback'),
            'merchantPaymentId' => (string) $order_id,
            'lang'              => 'KA',
        ]);

        $paymentUrl = TbcPaymentService::extractPaymentUrl($result);
        if (!$paymentUrl) {
            return response()->json(['error' => 'No payment URL in TBC response'], 500);
        }

        $order->tbc_pay_id     = $result['payId'];
        $order->tbc_pay_status = $result['status'] ?? 'CREATED';
        $order->payment_amount = $amount;
        $order->save();

        return response()->json([
            'payment_url' => $paymentUrl,
            'pay_id'      => $result['payId'],
        ]);
    }

    // POST /api/payment/shop/callback  (no auth — called by TBC server)
    public function callback(Request $request)
    {
        $payId             = $request->input('PaymentId') ?? $request->input('payId');
        $merchantPaymentId = $request->input('MerchantPaymentId') ?? $request->input('merchantPaymentId');

        $order = null;
        if ($merchantPaymentId) {
            $order = Order::find((int) $merchantPaymentId);
        }
        if (!$order && $payId) {
            $order = Order::where('tbc_pay_id', $payId)->first();
        }

        if (!$order) {
            Log::warning('TBC shop callback: order not found', $request->all());
            return response()->json(['error' => 'Order not found'], 404);
        }

        try {
            $tbcOrder  = $this->tbc->getOrder($order->tbc_pay_id);
            $tbcStatus = $tbcOrder['status'] ?? null;
        } catch (\Exception $e) {
            Log::error('TBC shop callback: getOrder failed', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Could not verify payment'], 500);
        }

        $order->tbc_pay_status = $tbcStatus;

        if (TbcPaymentService::isSucceeded($tbcStatus)) {
            $order->status               = 'paid';
            $order->confirm              = 1;
            $order->status_updating_data = now();
        } elseif (TbcPaymentService::isFailed($tbcStatus)) {
            $order->status               = 'payment_failed';
            $order->status_updating_data = now();
        }

        $order->save();

        return response()->json(['ok' => true]);
    }

    // GET /api/payment/shop/status/{order_id}  (auth:sanctum)
    public function status(int $order_id)
    {
        $user = Auth::user();
        if (!$user) return response()->json(['error' => 'Unauthenticated'], 401);

        $order = Order::where('id', $order_id)->where('user_id', $user->id)->first();
        if (!$order) return response()->json(['error' => 'Order not found'], 404);

        if (!$order->tbc_pay_id) {
            return response()->json(['tbc_pay_status' => null, 'status' => $order->status]);
        }

        try {
            $tbcOrder  = $this->tbc->getOrder($order->tbc_pay_id);
            $tbcStatus = $tbcOrder['status'] ?? $order->tbc_pay_status;
        } catch (\Exception $e) {
            return response()->json(['tbc_pay_status' => $order->tbc_pay_status, 'status' => $order->status]);
        }

        if ($tbcStatus !== $order->tbc_pay_status) {
            $order->tbc_pay_status = $tbcStatus;
            if (TbcPaymentService::isSucceeded($tbcStatus)) {
                $order->status               = 'paid';
                $order->confirm              = 1;
                $order->status_updating_data = now();
            } elseif (TbcPaymentService::isFailed($tbcStatus)) {
                $order->status               = 'payment_failed';
                $order->status_updating_data = now();
            }
            $order->save();
        }

        return response()->json([
            'tbc_pay_status' => $order->tbc_pay_status,
            'status'         => $order->status,
            'total'          => $this->calculateOrderTotal($order),
        ]);
    }

    private function calculateOrderTotal(Order $order): float
    {
        $items = Order_products::where('order_id', $order->id)->get();
        $total = $items->sum(function ($item) {
            $option = Product_option::find($item->product_option_id);
            return $option ? floatval($option->price) * $item->quantity : 0;
        });

        if ($order->shiping) {
            $total += floatval($order->shiping);
        }

        if ($order->discount) {
            $total = $total * (1 - floatval($order->discount) / 100);
        }

        return round($total, 2);
    }
}
