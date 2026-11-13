<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * First mail after sign-up (docs/FUNCTIONS_DOCS/global/welcome_email.md).
 * Sent once the address is confirmed: e-mail sign-ups when they click the verification link
 * (VerificationController::verify), Google / Facebook sign-ups right away (SocialController).
 * Queued on `emails`, the queue the worker listens on (docs/BACKEND/QUEUE_AND_SCHEDULER_UBUNTU.md).
 */
class WelcomeEmailNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct()
    {
        $this->onQueue('emails');
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        // config(), not env(): env() is null once `php artisan config:cache` has run
        $user_page = config('app.app_ssh') . config('app.user_page_url');
        $site = rtrim((string) config('app.base_url_ssh'), '/');

        return (new MailMessage)
            ->subject('Welcome to climbing.ge! 🎉')
            ->markdown('emails.userWelcomeMessage', [
                'name'          => trim((string) $notifiable->name),
                'action_url'    => $user_page . '/',
                'outdoor_url'   => $site . '/outdoor',
                'climbers_url'  => $site . '/climbers',
            ]);
    }

    public function toArray($notifiable)
    {
        return [];
    }
}
