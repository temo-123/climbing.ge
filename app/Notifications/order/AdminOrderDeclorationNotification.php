<?php

namespace App\Notifications\order;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminOrderDeclorationNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($order_info)
    {
        $this->name = $order_info['name'];
        $this->surname = $order_info['surname'];
        $this->user_id = $order_info['user_id'];
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
        // return (new MailMessage)->markdown('emails.order.admin_order_decloration_notification');

        return (new MailMessage)
            ->markdown('emails.order.admin_order_decloration_notification', 
                [
                    'name'=>$this->name,
                    'surname'=>$this->surname,
                    'user_id'=>$this->user_id,

                    'action_url'=>env('APP_SSH').env('USER_PAGE_URL').'/order/userOrders',

                    // 'adres'=>$this->adres,
                    // 'products'=>$this->products,
                    // 'payment'=>$this->payment,
                    // 'shiping'=>$this->shiping
                ]
            )
            ->subject('New order')
        ;
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
