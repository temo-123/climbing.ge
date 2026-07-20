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
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $windowStart = now()->addDays(6)->startOfDay();
        $windowEnd = now()->addDays(8)->endOfDay();

        $upcoming_events = Event::where('published', 1)
            ->whereBetween('start_data', [$windowStart, $windowEnd])
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
                    'event_reminder',
                    $url,
                    $locale_event->title,
                    $this->reminderText($locale_event->title, $locale)
                );
            }
        }

        return Command::SUCCESS;
    }

    private function reminderText(string $title, string $locale): string
    {
        if ($locale === 'ka') {
            return 'თქვენ ფავორიტ ღონისძიებამდე დარჩა 1 კვირაზე ნაკლები. (' . $title . ') ეწვიეთ ღონისძიების გვერდს, რათა გაიგოთ მეტი სიახლე მის შესახებ.';
        }

        return 'Less than 1 week left until your favorite event. (' . $title . ') Visit the event page to find out more news about it.';
    }
}
