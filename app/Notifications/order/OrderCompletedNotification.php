<?php

namespace App\Notifications\order;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Sent once, instead of the generic status mail, when an order becomes "Order Complete":
 * thanks + the ordered products + one button to the review page (order + product reviews).
 * Docs: docs/FUNCTIONS_DOCS/shop/order_review.md
 */
class OrderCompletedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public int $order_id,
        public string $name,
        public array $products,     // [['title' => …, 'options' => […]], …]
        public string $review_url
    ) {}

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->markdown('emails.order.order_completed', [
                'order_id'   => $this->order_id,
                'name'       => $this->name,
                'products'   => $this->products,
                'review_url' => $this->review_url,
            ])
            ->subject('Your order #' . $this->order_id . ' is complete 🎉 How did it go?');
    }

    public function toArray($notifiable)
    {
        return [];
    }
}
