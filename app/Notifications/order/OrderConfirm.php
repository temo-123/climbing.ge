<?php

namespace App\Notifications\order;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderConfirm extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user_order_info)
    {
        $this->name = $user_order_info['name'];
        $this->surname = $user_order_info['surname'];

        $this->user_id = $user_order_info['user_id'];
        $this->order_id = $user_order_info['order_id'];
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

        // dd(env('USER_PAGE_URL').'/confir_ordr/order_id/for_user/user_id');
        // $url = 'test';
        return (new MailMessage)
            // ->markdown('emails.order.order_confirm', 
            //     [
            //         'name'=>$this->name,
            //         'surname'=>$this->surname,
    
            //         'adres'=>$this->adres,
            //         'products'=>$this->products,
            //         'payment'=>$this->payment,
            //         'shiping'=>$this->shiping
            //     ]
            // )
            // ->subject('Order confirm')
            // ->line('Your payment details are wrong')
            // ->action('Pay', url('/'))
            // ->line('Best regards!')
            ->markdown('emails.order.order_confirm', 
                [
                    'action_url'=>env('APP_SSH').env('USER_PAGE_URL').'/order/confirm_order/'. strval($this->order_id) .'/user/' . strval($this->user_id),
                ]
            )
            // ->action('url', url(env('USER_PAGE_URL').'/confir_ordr/order_id/for_user/user_id'))
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
