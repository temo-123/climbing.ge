<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeEmailNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
    //     return (new MailMessage)
    //                 ->line('Welcome on climbing.ge')
    //                 ->action('Go to your profile', url('/'))
    //                 ->line('ჩongratulations! You have successfully registered. Thank you for using our services!');
        
        return (new MailMessage)
        ->markdown('emails.userWelcomeMessage', [
            'action_url'=> url(env('APP_SSH').env('USER_PAGE_URL').'/'),
            'text'=> 'Congratulations! You have successfully registered. Thank you for using our services!',
        ])
        ->subject('Welcome on climbing.ge');
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
