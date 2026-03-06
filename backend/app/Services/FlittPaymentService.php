<?php

namespace App\Services;

use Flitt\Checkout;
use Flitt\Order;

class FlittPaymentService
{
    /**
     * Create checkout URL for an order
     *
     * @param $order
     * @return string
     */
    public function createCheckout($order)
    {
        $data = [
            'order_id' => $order->id,
            'order_desc' => 'Order #' . $order->id,
            'currency' => 'GEL',
            'amount' => $order->amount, // in cents
            'response_url' => config('app.frontend_url') . '/payment-success',
            'server_callback_url' => route('payments.callback'),
        ];

        $response = Checkout::url($data);

        return $response->getData()['checkout_url'];
    }

    /**
     * Create checkout URL for a donation
     *
     * @param $donation
     * @return string|null
     */
    public function createDonationCheckout($donation)
    {
        $orderDescription = 'Donation';
        if ($donation->name) {
            $orderDescription = 'Donation from ' . $donation->name;
            if ($donation->surname) {
                $orderDescription .= ' ' . $donation->surname;
            }
        }

        $data = [
            'order_id' => $donation->id,
            'order_desc' => $orderDescription,
            'currency' => $donation->currency ?? 'GEL',
            'amount' => (int) ($donation->amount * 100), // convert to cents
            'response_url' => config('app.frontend_url') . '/donation-success',
            'server_callback_url' => route('donations.callback'),
        ];

        $response = Checkout::url($data);

        return $response->getUrl();
    }

    /**
     * Check order status
     *
     * @param $orderId
     * @return mixed
     */
    public function checkStatus($orderId)
    {
        return Order::status(['order_id' => $orderId]);
    }
}
