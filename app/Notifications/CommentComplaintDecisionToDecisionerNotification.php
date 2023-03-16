<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CommentComplaintDecisionToDecisionerNotification extends Notification
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

        if($info['decision'] == 'approve_request'){
            $this->decision_message = 'We reviewed your request and made a decision, approve request and delete this comment!';
        }
        else if($info['decision'] == 'reject_request'){
            $this->decision_message = 'We reviewed your request and made a decision, reject request don`t delete this comment!';
        }
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
        // return (new MailMessage)->markdown('emails.comment.comment_complaint_decision_to_decisione_notification');

        return (new MailMessage)
            ->markdown('emails.comment.comment_complaint_decision_to_decisione_notification', 
                [
                    'cause'=>$this->cause,
                    'decision_message'=>$this->decision_message,
                ]
            )
            ->subject('Comment Complaint Decision')
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
