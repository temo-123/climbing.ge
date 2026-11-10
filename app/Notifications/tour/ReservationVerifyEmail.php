<?php

namespace App\Notifications\tour;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * "Confirm your reservation" mail to the reserver. The button opens the shop page
 * /confirm_reservation/{id}/{token} (TourReservationService::confirm_url), where the
 * reserver presses "Confirm reservation".
 */
class ReservationVerifyEmail extends Notification
{
    use Queueable;

    protected $action_url;
    protected $reservation;

    public function __construct($info)
    {
        $this->action_url = $info['action_url'];
        $this->reservation = $info['reservation'] ?? null;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->markdown('emails.tour.reservation_varification', [
                'action_url' => $this->action_url,
                'reservation' => $this->reservation,
            ])
            ->subject('Please confirm your tour reservation');
    }

    public function toArray($notifiable)
    {
        return [];
    }
}
