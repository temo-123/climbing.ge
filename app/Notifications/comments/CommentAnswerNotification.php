<?php

namespace App\Notifications\comments;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CommentAnswerNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($info)
    {
        // $this->article_title -> $info->article_title;

        $this->article_title = $info['us_article_title'];
        $this->url_title = $info['url_title'];
        $this->category = $info['category'];
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
        //             ->line('The introduction to the notification.')
        //             ->action('Notification Action', url('/'))
        //             ->line('Thank you for using our application!');

        // a notification mail: muted by "turn off all notification e-mails" (NotificationPreferenceService)
        $email = $notifiable->routes['mail'] ?? ($notifiable->email ?? null);
        $manage_url = \App\Mail\UserNotificationMessage::manage_alerts_url();
        $unsubscribe_url = \App\Services\NotificationPreferenceService::unsubscribe_url_for_email(is_string($email) ? $email : null);

        return (new MailMessage)
            ->markdown('emails.comment.comment_answer_notificatione',
                [
                    'article_title'=>$this->article_title,
                    // config(), not env(): env() is null after config:cache
                    'url'=>rtrim((string) config('app.base_url_ssh'), '/').'/'.$this->category.'/'.$this->url_title,
                    'unsubscribe_url'=>$unsubscribe_url,   // null for guest commenters
                    'manage_url'=>$manage_url,
                ]
            )
            ->subject('💬 Someone replied to your comment')
            ->withSymfonyMessage(function ($message) use ($manage_url, $unsubscribe_url) {
                \App\Services\NotificationPreferenceService::add_headers($message, $manage_url, $unsubscribe_url);
            });
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
