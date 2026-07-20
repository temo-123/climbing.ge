<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Guide\Event;
use App\Models\Guide\Interested_event;
use App\Models\User;

use App\Services\NotificationDispatchService;

class UserAutoNotificatione extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send_event_notificatione:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send user event automaticly notificatione';

    /**
     * Reminder checkpoints: how many days before the event to notify, and the
     * dedup notification_key each one uses. Distinct keys are required — with
     * a single shared key, sendOnce()'s per-item dedup would let only the
     * first checkpoint to fire ever send, silently blocking the other three
     * for the same user/event.
     */
    private const PERIODS = [
        'event_reminder_1month' => ['days' => 30, 'label' => '1month'],
        'event_reminder_2weeks' => ['days' => 14, 'label' => '2weeks'],
        'event_reminder_1week'  => ['days' => 7,  'label' => '1week'],
        'event_reminder_1day'   => ['days' => 1,  'label' => '1day'],
    ];

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        foreach (self::PERIODS as $notificationKey => $period) {
            $targetDate = now()->addDays($period['days'])->toDateString();

            $upcoming_events = Event::where('published', 1)
                ->whereDate('start_data', $targetDate)
                ->get();

            foreach ($upcoming_events as $event) {
                $interested = Interested_event::where('event_id', $event->id)->get();

                foreach ($interested as $interest) {
                    $user = User::find($interest->user_id);
                    if (!$user) {
                        continue;
                    }

                    $locale = $user->lang === 'ka' ? 'ka' : 'us';
                    $locale_event = $locale === 'ka' ? $event->ka_event : $event->us_event;
                    if (!$locale_event) {
                        continue;
                    }

                    $url = config('app.base_url_ssh') . '/event/' . $event->url_title;

                    NotificationDispatchService::sendOnce(
                        $user,
                        $event,
                        $notificationKey,
                        $url,
                        $locale_event->title,
                        $this->reminderText($locale_event->title, $locale, $period['label'])
                    );
                }
            }
        }

        return Command::SUCCESS;
    }

    private function reminderText(string $title, string $locale, string $period): string
    {
        $periods_ka = [
            '1month' => '1 თვეზე ნაკლები',
            '2weeks' => '2 კვირაზე ნაკლები',
            '1week'  => '1 კვირაზე ნაკლები',
            '1day'   => '1 დღეზე ნაკლები',
        ];
        $periods_us = [
            '1month' => 'less than 1 month',
            '2weeks' => 'less than 2 weeks',
            '1week'  => 'less than 1 week',
            '1day'   => 'less than 1 day',
        ];

        if ($locale === 'ka') {
            return 'თქვენ ფავორიტ ღონისძიებამდე დარჩა ' . $periods_ka[$period] . '. (' . $title . ') ეწვიეთ ღონისძიების გვერდს, რათა გაიგოთ მეტი სიახლე მის შესახებ.';
        }

        return 'There is ' . $periods_us[$period] . ' left until your favorite event. (' . $title . ') Visit the event page to find out more news about it.';
    }
}
