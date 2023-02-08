<?php

namespace App\Notifications\user_notification;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ArticleEventNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($article)
    {
        // dd($article);
        $this->global_article = $article['global_article'];
        $this->locale_article = $article['locale_article'];
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
        // return (new MailMessage)
        //            ->line('The introduction to the notification.')
        //            ->action('Notification Action', url('/'))
        //            ->line('Thank you for using our application!')
        //            ->markdown('emails.user_notifications.article_event_notification');

        return (new MailMessage)
            ->markdown('emails.user_notifications.article_event_notification', 
                [
                    'locale_article'=>$this->locale_article,
                    'global_article'=>$this->global_article,
                ]
            )
            ->subject('New article on climbing.ge')
        ;
        // return (new MailMessage)->markdown('emails.user_notifications.article_event_notification');
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
