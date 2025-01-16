<?php

namespace App\Notifications\order;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatusNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($order_status_info)
    {
        $this->status = $order_status_info['status'];
        $this->data_time = $order_status_info['data_time'];
        $this->order_id = $order_status_info['order_id'];
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        // return (new MailMessage)->markdown('emails.order.order_status_notification');

        return (new MailMessage)
            ->markdown('emails.order.order_status_notification', 
                [
                    'status'=>$this->status,
                    'data_time'=>$this->data_time,
                    'order_id'=>$this->order_id,

                    'action_url'=>env('APP_SSH').env('USER_PAGE_URL').'/order/userOrders',
                ]
            )
            ->subject('Order status updating');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
