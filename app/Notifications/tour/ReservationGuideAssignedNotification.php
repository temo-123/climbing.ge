<?php

namespace App\Notifications\tour;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/** To the NEW guide in charge after a switch (TourGuideService::assign). */
class ReservationGuideAssignedNotification extends Notification
{
    use Queueable;

    public function __construct(protected array $reservation, protected ?array $previous, protected ?string $by, protected ?string $note, protected string $action_url) {}

    public function via($notifiable) { return ['mail']; }

    public function toMail($notifiable)
    {
        $tour = $this->reservation['tour'] ?? null;
        return (new MailMessage)
            ->markdown('emails.tour.guide_assigned', [
                'reservation' => $this->reservation,
                'previous' => $this->previous,
                'by' => $this->by,
                'note' => $this->note,
                'action_url' => $this->action_url,
            ])
            ->subject('🧭 A reservation is now yours' . ($tour ? ': ' . $tour : ''));
    }

    public function toArray($notifiable) { return []; }
}
