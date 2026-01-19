<?php

namespace App\Http\Controllers\Api\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Guide\Event;
use App\Models\Guide\Locale_event;
use App\Models\Guide\General_info_event;
use App\Models\Guide\General_info;

use App\Services\Abstract\ImageControllService;
use App\Services\GeneralInfoService;
use App\Services\URLTitleService;

use Validator;

class EventController extends Controller
{
    public function get_event_on_site_list(Request $request)
    {
        $action_data = date("Y/m/d H:i:s");

        $events = Event::where('published', '=', 1)->orderBy('start_data')->get();

        // dd($events);

        $events_array = [];

        foreach ($events as $event) {
            if(
                date('m', strtotime($event->end_data)) >= date('m', strtotime($action_data)) &&
                date('Y', strtotime($event->end_data)) == date('Y', strtotime($action_data))
            ){
                $global_event = $event;
                
                if($request->lang == 'ka'){
                    $local_event = $event->ka_event;
                }
                // else if($request->lang == 'ru'){
                //     $local_event = $event->ru_event;
                // }
                else{
                    $local_event = $event->us_event;
                }

                if(date('d', strtotime($event->end_data)) > date('d', strtotime($action_data))){
                    array_push($events_array, [
                        'global_event' => $global_event,
                        'locale_event' => $local_event,
                    ]);
                }
                elseif (
                        date('m', strtotime($event->end_data)) > date('m', strtotime($action_data)) &&
                        date('Y', strtotime($event->end_data)) == date('Y', strtotime($action_data))
                    ) {

                    array_push($events_array, [
                        'global_event' => $global_event,
                        'locale_event' => $local_event,
                    ]);
                }
            }
        }

        return $events_array;
    }

    public function get_event_on_index_page(Request $request)
    {
        $action_data = date("Y/m/d H:i:s");

        $events = Event::where('published', '=', 1)->orderBy('start_data')->get();

        $events_array = [];

        foreach ($events as $event) {
            if(
                date('m', strtotime($event->end_data)) >= date('m', strtotime($action_data)) &&
                date('Y', strtotime($event->end_data)) == date('Y', strtotime($action_data))
            ){

                if(count($events_array) < 3){
                    $global_event = $event;
                    
                    if($request->lang == 'ka'){
                        $local_event = $event->ka_event;
                    }
                    // else if($request->lang == 'ru'){
                    //     $local_event = $event->ru_event;
                    // }
                    else{
                        $local_event = $event->us_event;
                    }

                    if(date('d', strtotime($event->end_data)) > date('d', strtotime($action_data))){
                        array_push($events_array, [
                            'global_event' => $global_event,
                            'locale_event' => $local_event,
                        ]);
                    }
                    elseif (
                            date('m', strtotime($event->end_data)) > date('m', strtotime($action_data)) &&
                            date('Y', strtotime($event->end_data)) == date('Y', strtotime($action_data))
                        ) {

                        array_push($events_array, [
                            'global_event' => $global_event,
                            'locale_event' => $local_event,
                        ]);
                    }
                }
                
            }
        }

        return $events_array;
    }

    public function get_event_on_site_page(Request $request)
    {
        $event_count = Event::where('url_title', '=', $request->url_title)->count();
        if ($event_count > 0) {
            $global_event = Event::where('url_title', '=', $request->url_title)->first();

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
