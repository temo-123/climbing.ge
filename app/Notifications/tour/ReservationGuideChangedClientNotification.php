<?php

namespace App\Notifications\tour;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/** To the reserver (confirmed reservations only, optional): your guide is now … */
class ReservationGuideChangedClientNotification extends Notification
{
    use Queueable;

    public function __construct(protected array $reservation, protected array $guide) {}

    public function via($notifiable) { return ['mail']; }

    public function toMail($notifiable)
    {
        $tour = $this->reservation['tour'] ?? null;
        return (new MailMessage)
            ->markdown('emails.tour.guide_changed_client', [
                'reservation' => $this->reservation,
                'guide' => $this->guide,
            ])
            ->subject('Meet your new guide' . ($tour ? ' for ' . $tour : '') . ' 🧭');
    }

    public function toArray($notifiable) { return []; }
}
