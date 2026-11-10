<?php

namespace App\Notifications\tour;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * "Confirmed reservation" — to info@climbing.ge (sites.email) and to the tour's guides
 * after the reserver confirmed: full contact, trip, price, message, link to the panel.
 * Data: TourReservationService::mail_data().
 */
class ReservationCreatingGuideNotification extends Notification
{
    use Queueable;

    protected $reservation;
    protected $action_url;

    public function __construct($reservation = null, $action_url = null)
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
        $r = $this->reservation ?: [];
        $subject = 'Confirmed tour reservation';
        if (!empty($r['tour'])) $subject .= ': ' . $r['tour'];
        if (!empty($r['check_in'])) $subject .= ' — ' . substr(str_replace('T', ' ', $r['check_in']), 0, 10);

        return (new MailMessage)
            ->markdown('emails.tour.reservation_confirmed_guide', [
                'reservation' => $r,
                'action_url' => $this->action_url,
            ])
            ->subject($subject);
    }

    public function toArray($notifiable)
    {
        return [];
    }
}
