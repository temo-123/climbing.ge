<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserNotificationeMessage extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The data for the email instance.
     *
     * @var data
     */
    protected $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    // public function envelope()
    // {
    //     // return new Envelope(
    //     //     subject: 'User Article Notificatione Message',
    //     // );

    //     return new Envelope(
    //         from: new Address('jeffrey@example.com', 'Jeffrey Way'),
    //         subject: 'Order Shipped',
    //     );
    // }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    // public function content()
    // {
    //     return new Content(
    //         view: 'emails.user_notifications.article_event_notification',
    //     );
    // }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    // public function attachments()
    // {
    //     return [];
    // }


    public function build()
    { 
        $subject = 'User message from the site - ';

        return $this
        ->markdown('emails.user_notifications.article_event_notification')
        ->with([
            'data'=>$this->data,
        ])
        ->subject($subject);
    }
}
