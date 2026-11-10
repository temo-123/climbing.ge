<?php

namespace App\Notifications\tour;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * To the reserver after the reservation is confirmed: the tour (description, location,
 * duration, link), the trip (check-in, days, persons), the price and the guide contacts.
 * Data: TourReservationService::mail_data().
 */
class ReservationSacsesfulNotification extends Notification
{
    use Queueable;

    protected $reservation;

    public function __construct($reservation = null)
    {
        $this->reservation = $reservation;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $tour = $this->reservation['tour'] ?? null;

        return (new MailMessage)
            ->markdown('emails.tour.reservation_confirmed_client', ['reservation' => $this->reservation])
            ->subject('Your reservation is confirmed' . ($tour ? ': ' . $tour : ''));
    }

    public function toArray($notifiable)
    {
        return [];
    }
}
