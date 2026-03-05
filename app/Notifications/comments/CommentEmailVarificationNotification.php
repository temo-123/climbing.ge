<?php

namespace App\Notifications\comments;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CommentEmailVarificationNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($info)
    {
        $this->host = $info['host'];
        $this->id = $info['id'];
        $this->user_email = $info['user_email'];
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
        ->markdown('emails.comment.comment_email_varification', [
            'action_url'=> url(env('APP_SSH').$this->host.'/confirm_comment_email/'.$this->id.'/'.$this->user_email),
            // 'title'=> 'Confirmation completion of your task.',
            // 'text'=> 'Thank you for execution task. Pleas visit your task panel for check other tasks',
        ])
        ->subject('Mail varificatione');
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
