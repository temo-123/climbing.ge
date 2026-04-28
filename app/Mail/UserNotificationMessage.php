<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserNotificationMessage extends Mailable
{
    use Queueable, SerializesModels;

    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
        $this->subject = $data['subject'];
    }

    public function build()
    {
        return $this->markdown('emails.user_notifications.article_event_notification')
            ->with(['data' => $this->data])
            ->subject($this->subject);
    }
}
