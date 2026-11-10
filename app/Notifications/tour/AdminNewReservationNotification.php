<?php

namespace App\Notifications\tour;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * "New tour reservation" mail to the site address (sites.email — info@climbing.ge),
 * like AdminOrderDeclorationNotification for orders. Sent when the reservation is made,
 * so it also says whether the reserver still has to confirm the email.
 */
class AdminNewReservationNotification extends Notification
{
    use Queueable;

    protected $reservation;
    protected $action_url;

    public function __construct(array $reservation, string $action_url)
    {
        $this->reservation = $reservation;
        $this->action_url = $action_url;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $tour = $this->reservation['tour'] ?? null;

        return (new MailMessage)
            ->markdown('emails.tour.admin_new_reservation', [
                'reservation' => $this->reservation,
                'action_url' => $this->action_url,
            ])
            ->subject('New tour reservation' . ($tour ? ': ' . $tour : ''));
    }

    public function toArray($notifiable)
    {
        return [];
    }
}
