<?php

namespace App\Notifications\tour;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/** To the PREVIOUS guide after a switch: the reservation moved to someone else. */
class ReservationGuideUnassignedNotification extends Notification
{
    use Queueable;

    public function __construct(protected array $reservation, protected ?array $next, protected ?string $by) {}

    public function via($notifiable) { return ['mail']; }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->markdown('emails.tour.guide_unassigned', [
                'reservation' => $this->reservation,
                'next' => $this->next,
                'by' => $this->by,
            ])
            ->subject('Reservation #' . ($this->reservation['id'] ?? '') . ' moved to another guide');
    }

    public function toArray($notifiable) { return []; }
}
