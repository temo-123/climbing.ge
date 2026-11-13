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
        $manage_url = self::manage_alerts_url();
        $unsubscribe_url = \App\Services\NotificationPreferenceService::unsubscribe_url_for_email($this->data['email'] ?? null);

        return $this->markdown('emails.user_notifications.article_event_notification')
            ->with(['data' => $this->data, 'manage_url' => $manage_url, 'unsubscribe_url' => $unsubscribe_url])
            ->subject($this->subject)
            // mute marker + List-Unsubscribe (one-click): mail clients show their own "Unsubscribe"
            // button, so annoyed users turn mail off instead of pressing "Report spam"
            ->withSymfonyMessage(function ($message) use ($manage_url, $unsubscribe_url) {
                \App\Services\NotificationPreferenceService::add_headers($message, $manage_url, $unsubscribe_url);
            });
    }

    /** The account's alert settings: user page → Options → Notifications tab. */
    public static function manage_alerts_url(): string
    {
        return config('app.app_ssh') . config('app.user_page_url') . '/options?tab=notifications';
    }
}
