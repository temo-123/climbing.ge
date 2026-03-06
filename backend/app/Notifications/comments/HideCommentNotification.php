<?php

namespace App\Notifications\comments;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use Auth;

class HideCommentNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($info)
    {
        $this->cause = $info['cause'];
        $this->data_time = $info['data_time'];
        $this->comented_title = $info['comented_title'];
        $this->violation_count = $info['violation_count'];
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
        // return (new MailMessage)->markdown('emails.delete_comment__notification');

        return (new MailMessage)
            ->markdown('emails.comment.hide_comment_notification', 
                [
                    'cause'=>$this->cause,
                    'data_time'=>$this->data_time,
                    'comented_title'=>$this->comented_title,
                    'violation_count'=>$this->violation_count
                ]
            )
            ->subject('Comment deliting')
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
