<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Guide\Event;
use App\Models\User\user_notification;

use App\Jobs\UserNotifications;

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
        // return Command::SUCCESS;
        // $url = '';
        // $text = '';
        // $subject = '';

        $user_notifictions = user_notification::where('interested_event', '=', 1)->get();
        if($user_notifictions){
            foreach ($user_notifictions as $notifiction) {
                $user = $notifiction->user;
                
                $action_data = date("Y-m-d H:i:s");
                $plas_6_day = date('Y-m-d H:i:s', strtotime($action_data. ' + 6 days'));
                $plas_8_day = date('Y-m-d H:i:s', strtotime($action_data. ' + 8 days'));
                
                $global_event = Event::where('start_data', '>', $plas_6_day)->where('start_data', '<', $plas_8_day)->first();

                if($global_event){
                    $locale_event;
                    // if($user->lang == 'ka'){
                    //     $locale_event = $global_event->ka_event;
                    //     $url = config('app.base_url_ssh').'/ka/event/'.$global_event->url_title;
                    //     $text = 'თქვენ ფავორიტ ღონისძიებამდე დარჩა 1 კვირა. (' . $locale_event->title . ') ეწვიეთ ღონისძიების გვერდს, რათა გაიგოთ მეტი სიახლე მის შესახებ.';
                    //     $subject = $locale_event->title;
                    // }
                    // else if($user->lang == 'ru'){
                    //     $locale_event = $global_event->ru_event;
                    //     $url = config('app.base_url_ssh').'/ru/event/'.$global_event->url_title;
                    //     $text = 'До любимого события осталось меньше недели. (' . $locale_event->title . ') Посетите страницу мероприятия, чтобы узнать больше новостей о нем.';
                    //     $subject = $locale_event->title;
                    // }
                    // else{
                        $locale_event = $global_event->us_event;
                        $url = config('app.base_url_ssh').'/event/'.$global_event->url_title;
                        $text = 'Less than 1 week left until your favorite event. (' . $locale_event->title . ') Visit the event page to find out more news about it.';
                        $subject = $locale_event->title;
                    // }

                    UserNotifications::dispatch($url, $text, $subject, $user->email)->onQueue('emails');
                }
            }
        }
    }
}
