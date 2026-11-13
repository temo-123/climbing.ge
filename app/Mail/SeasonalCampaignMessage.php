<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Seasonal campaign mail: intro + 1–3 picked articles / sectors.
 * Queued on `emails` (docs/BACKEND/QUEUE_AND_SCHEDULER_UBUNTU.md); built by SeasonalCampaignService::send().
 */
class SeasonalCampaignMessage extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
        $this->onQueue('emails');
    }

    public function build()
    {
        $ka = ($this->data['locale'] ?? 'us') === 'ka';
        $manage_url = $this->data['manage_url'];

        return $this->subject($this->data['subject'])
            ->markdown('emails.seasonal.seasonal_campaign', [
                'd' => $this->data,
                'l' => [
                    'hi'     => $ka ? 'გამარჯობა' : 'Hi',
                    'open'   => $ka ? 'ნახვა' : 'Take a look',
                    'manage' => $ka ? 'აირჩიე რა წერილები გინდა' : 'Choose which emails you get',
                    'all_off' => $ka ? 'ყველა შეტყობინების გამორთვა' : 'Unsubscribe from all',
                    'why'    => $ka ? 'ამ წერილს იღებ, რადგან ჩართული გაქვს სეზონური რჩევები.' : 'You get this because seasonal picks are switched on in your account.',
                    'bye'    => $ka ? 'შეხვედრამდე კლდეზე! 🧗' : 'See you out there! 🧗',
                    'team'   => $ka ? 'climbing.ge-ს გუნდი' : 'The climbing.ge team',
                ],
            ])
            ->withSymfonyMessage(function ($message) use ($manage_url) {
                \App\Services\NotificationPreferenceService::add_headers($message, $manage_url, $this->data['unsubscribe_url'] ?? null);
            });
    }
}
