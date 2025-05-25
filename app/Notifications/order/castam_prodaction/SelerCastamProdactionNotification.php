<?php

namespace App\Notifications\order\castam_prodaction;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SelerCastamProdactionNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($info)
    {
        $this->product_name = $info['product_name'];
        $this->messaged_user_name = $info['messaged_user_name'];
        $this->messaged_user_email = $info['messaged_user_email'];
        $this->product_id = $info['product_id'];
        $this->number = $info['number'];
        $this->message = $info['message'];
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
        return (new MailMessage)
        ->markdown('emails.order.castom_prodaction.seler_castam_prodaction', [
            'message' => "Your have new order message. Please answer buyer as soon as possible!",
            'product_id' => $this->product_id,
            'product_name' => $this->product_name,
            'messaged_user_name' => $this->messaged_user_name,
            'messaged_user_email' => $this->messaged_user_email,
            'buyer_number' => $this->number,
            'buyer_message' => $this->message,
        ])
        ->subject('New order message');
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
