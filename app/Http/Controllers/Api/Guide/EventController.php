<?php

namespace App\Http\Controllers\Api\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Guide\Event;
use App\Models\Guide\Locale_event;
use App\Models\Guide\General_info_event;
use App\Models\Guide\General_info;
use App\Models\Guide\Interested_event;

use App\Services\Abstract\ImageControllService;
use App\Services\GeneralInfoService;
use App\Services\URLTitleService;

use Validator;

class EventController extends Controller
{
    /**
     * Every published event (upcoming, running and past — the list page
     * filters and groups them), oldest first, with how many climbers are
     * interested. Used to return only events ending later in the current
     * year, compared by day and month separately (broke across years).
     */
    public function get_event_on_site_list(Request $request)
    {
        $events = Event::where('published', '=', 1)->with(['us_event', 'ka_event'])->orderBy('start_data')->get();

        return $this->with_locale_and_counts($events, $request->lang);
    }

    // homepage: the next three events that haven't finished yet
    public function get_event_on_index_page(Request $request)
    {
        $now = now();
        $events = Event::where('published', '=', 1)
            ->where(function ($q) use ($now) {
                $q->where('end_data', '>=', $now)
                  ->orWhere(fn ($q2) => $q2->whereNull('end_data')->where('start_data', '>=', $now->copy()->startOfDay()));
            })
            ->with(['us_event', 'ka_event'])
            ->orderBy('start_data')
            ->limit(3)
            ->get();

        return $this->with_locale_and_counts($events, $request->lang);
    }

    private function with_locale_and_counts($events, $lang)
    {
        $counts = Interested_event::whereIn('event_id', $events->pluck('id'))
            ->selectRaw('event_id, COUNT(*) as n')->groupBy('event_id')->pluck('n', 'event_id');

        return $events->map(function ($event) use ($lang, $counts) {
            $local = $lang == 'ka' ? $event->ka_event : $event->us_event;
            $global = $event->withoutRelations();
            $global->interested_count = (int) ($counts[$event->id] ?? 0);
            return ['global_event' => $global, 'locale_event' => $local];
        })->filter(fn ($e) => $e['locale_event'])->values();
    }

    public function get_event_on_site_page(Request $request)
    {
        // unpublished events are not public (they used to open by URL)
        $event_count = Event::where('url_title', '=', $request->url_title)->where('published', '=', 1)->count();
        if ($event_count > 0) {
            $global_event = Event::where('url_title', '=', $request->url_title)->where('published', '=', 1)->first();
            $global_event->interested_count = Interested_event::where('event_id', $global_event->id)->count();

            if($request->lang == 'ka'){
                $local_event = $global_event->ka_event;
            }
            // else if($request->lang == 'ru'){
            //     $local_event = $global_event->ru_event;
            // }
            else{
                $local_event = $global_event->us_event;
            }

            if(General_info_event::where('event_id', '=', $global_event->id)->count() > 0){
                $info_article_relatione = General_info_event::where('event_id', '=', $global_event->id)->first();
                $general_info = General_info::where('id', '=', $info_article_relatione->info_id)->first();
                
                $text = '';

                if($request->lang == 'ka'){
                    $text = $general_info->text_ka;
                }
                // else if($request->lang == 'ru'){
                //     $text = $general_info->text_ru;
                // }
                else{
                    $text = $general_info->text_us;
                }

                $global_info = [
                    "block_action" => $info_article_relatione->block_action,
                    "text"=>$text,
                ];
            }

            $data = [];

            if(isset($global_info)){
                $data = [
                    "general_info"=>[
                        "info_block" => $global_info,
                    ],
                    'global_event' => $global_event,
                    'locale_event' => $local_event,
                ];
            }
            else{
                $data = [
                    'global_event' => $global_event,
                    'locale_event' => $local_event,
                ];
            }

            return $data;
        }
        else{
            return abort(404);
        }
    }

    public function get_all_events(Request $request)
    {
        return Event::where("category", "=", "event")->latest('end_data')->get();
    }

}
