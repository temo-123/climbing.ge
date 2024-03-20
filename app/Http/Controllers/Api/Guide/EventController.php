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
                else if($request->lang == 'ru'){
                    $local_event = $event->ru_event;
                }
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
                    else if($request->lang == 'ru'){
                        $local_event = $event->ru_event;
                    }
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
            else if($request->lang == 'ru'){
                $local_event = $global_event->ru_event;
            }
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
                else if($request->lang == 'ru'){
                    $text = $general_info->text_ru;
                }
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

    public function edit_event(Request $request)
    {

        // dd($request->global_blocks);

        $validation_issets = [];

        $data = json_decode($request->data, true );

        $ka_validate = $this->local_event_validate($data['ka_data']);
        if ($ka_validate != null) {
            $validation_issets['ka_info_validation'] = $ka_validate;
        }
        else{
            $validation_issets['ka_info_validation'] = false;
        }

        $us_validate = $this->local_event_validate($data['us_data']);
        if ($us_validate != null) {
            $validation_issets['us_info_validation'] = $us_validate;
        }
        else{
            $validation_issets['us_info_validation'] = false;
        }

        $ru_validate = $this->local_event_validate($data['ru_data']);
        if ($ru_validate != null) {
            $validation_issets['ru_info_validation'] = $ru_validate;
        }
        else{
            $validation_issets['ru_info_validation'] = false;
        }

        $global_validate = $this->global_event_edit_validate($data['global_data']);
        if ($global_validate != null) {
            $validation_issets['global_info_validation'] = $global_validate;
        }
        else{
            $validation_issets['global_info_validation'] = false;
        }

        if (!$validation_issets['global_info_validation'] && 
            !$validation_issets['ru_info_validation'] && 
            !$validation_issets['ka_info_validation'] && 
            !$validation_issets['us_info_validation']
        ) {
            // dd($data);
            $locale_event_values = $this->edit_global_event(
                $data['global_data'],  
                $request->event_id,
                $request           
            );
            if (
                $locale_event_values != 'Error'
            ) {
                $saiving_issets['ka_info_status'] = $this->edit_locale_event($data['ka_data'], $locale_event_values['ka_id']);
                $saiving_issets['ru_info_status'] = $this->edit_locale_event($data['ru_data'], $locale_event_values['ru_id']);
                $saiving_issets['us_info_status'] = $this->edit_locale_event($data['us_data'], $locale_event_values['us_id']);
                
                if($request->hasFile('event_new_images')){
                    $this->add_event_images($request->event_new_images, $locale_event_values['global_id']);
                }
            }
        }
        else{            
            return response()->json([
                'validation' => $validation_issets
            ], 422);
        }
    }

    public function edit_global_event($global_data, $id, $request)
    {
        $editing_local_event = Event::where('id', '=', $id)->first();

        if($global_data['change_url_title']){
            $url_title = URLTitleService::get_url_title($global_data["us_title_for_url_title"]); // make url_title from us_title value
            $editing_local_event['url_title'] = $url_title;
        }

        if($request->hasFile('image')){      
            $editing_local_event['image'] = ImageControllService::image_update('images/event_img/', $editing_local_event, $request, 'image', 'image', 1);
        }
        
        if($global_data['change_event_category']){
            if($editing_local_event['category'] == "competition"){
                $editing_local_event['category']="event";
            }
            else if($editing_local_event['category'] == "event"){
                $editing_local_event['category']="competition";
            }
        }
        else{
            $editing_local_event['category']=$global_data["category"];
        }

        $editing_local_event['published']=$global_data["published"];
        $editing_local_event['map']=$global_data["map"];

        if ($global_data["start_data"] != '') {
            $editing_local_event['start_data'] = date("Y-m-d\TH:i:s", strtotime($global_data["start_data"]));
        }
        if ($global_data["end_data"] != '') {
            $editing_local_event['end_data'] = date("Y-m-d\TH:i:s", strtotime($global_data["end_data"]));
        }
        
        $saved = $editing_local_event -> save();
        
        $inf_blo = json_decode($request->global_blocks, true);
        if($inf_blo['info_block_id'] != 0){
            GeneralInfoService::edit_general_info_relatione(json_decode($request->global_blocks, true), $id, "event");
        }
        
        if(!$saved){
            App::abort(500, 'Error');
        }
        else{
            $locale_tabs = [
                'us_id' => $editing_local_event->us_event_id,
                'ka_id' => $editing_local_event->ka_event_id,
                'ru_id' => $editing_local_event->ru_event_id,
                'global_id' => $editing_local_event->id
            ];
            return $locale_tabs;
        }
    }

    public function edit_locale_event($data, $id)
    {
        $editing_locale_event = Locale_event::where('id', '=', $id)->first();
        
        $editing_locale_event['title']=$data["title"];
        $editing_locale_event['short_description']=$data["short_description"];
        $editing_locale_event['text']=$data["text"];
        $editing_locale_event['info']=$data["info"];

        $saved = $editing_locale_event->save();

        if(!$saved){
            App::abort(500, 'Error');
        }
        else{
            return $editing_locale_event->id;
        }
    }


    public function get_editing_event(Request $request)
    {
        $global_event = Event::where('id',strip_tags($request->event_id))->first();

        $blobal_data = [];
        if($global_event->general_info){
            foreach ($global_event->general_info as $info) {
                $info_position = General_info_event::where('event_id',strip_tags($info->pivot->event_id))->where('info_id',strip_tags($info->pivot->info_id))->first();

                array_push($blobal_data, [
                    'data' => $info,
                    'position' => $info_position
                ]);
            }
        }

        return $data = [
            'global_data' => $global_event,
            'us_data' => $global_event->us_event,
            'ru_data' => $global_event->ru_event,
            'ka_data' => $global_event->ka_event,
            'general_info' => $blobal_data
        ];

        // return $data;
    }

    public function del_event(Request $request)
    {
        $global_global_info_relatione = General_info_event::where('event_id', '=', $request->event_id)->first();

        $global_event = Event::where('id',strip_tags($request->event_id))->first();
        $us_event = Locale_event::where('id',strip_tags($global_event->us_event_id))->first();
        $ru_event = Locale_event::where('id',strip_tags($global_event->ru_event_id))->first();
        $ka_event = Locale_event::where('id',strip_tags($global_event->ka_event_id))->first();
        
        // delete event file
        ImageControllService::image_delete('images/event_img/', $global_event, 'image');
        
        // Del general info event relatione
        // if ($global_event->general_info->count() > 0) {
        //     foreach ($global_event->general_info as $del_info) {
        //         $deliting_info = General_info_event::
        //                                             where('event_id',strip_tags($del_info->pivot->event_id))->
        //                                             where('info_id',strip_tags($del_info->pivot->info_id))->
        //                                             first();
        //         $deliting_info -> delete();
        //     }
        // }

        // delete event from db
        // dd($global_global_info_relatione);
        if($global_global_info_relatione){
            $global_global_info_relatione ->delete();
        }
        $global_event ->delete();
        $us_event ->delete();
        $ru_event ->delete();
        $ka_event ->delete();
    }


    public function add_event(Request $request)
    {
        $validation_issets = [];

        // dd($request);

        $data = json_decode($request->data, true );
        $global_blocks = json_decode($request->global_blocks, true );

        $ka_validate = $this->local_event_validate($data['ka_data']);
        if ($ka_validate != null) {
            $validation_issets['ka_info_validation'] = $ka_validate;
        }
        else{
            $validation_issets['ka_info_validation'] = false;
        }

        $us_validate = $this->local_event_validate($data['us_data']);
        if ($us_validate != null) {
            $validation_issets['us_info_validation'] = $us_validate;
        }
        else{
            $validation_issets['us_info_validation'] = false;
        }

        $ru_validate = $this->local_event_validate($data['ru_data']);
        if ($ru_validate != null) {
            $validation_issets['ru_info_validation'] = $ru_validate;
        }
        else{
            $validation_issets['ru_info_validation'] = false;
        }

        $global_validate = $this->global_event_add_validate($data['global_data']);
        if ($global_validate != null) {
            $validation_issets['global_info_validation'] = $global_validate;
        }
        else{
            $validation_issets['global_info_validation'] = false;
        }

        if (
            !$validation_issets['global_info_validation'] && 
            !$validation_issets['ru_info_validation'] && 
            !$validation_issets['ka_info_validation'] && 
            !$validation_issets['us_info_validation']
        ) {
            $saiving_issets['ka_info_status'] = $this->add_locale_event($data['ka_data'], 'ka');
            $saiving_issets['ru_info_status'] = $this->add_locale_event($data['ru_data'], 'ru');
            $saiving_issets['us_info_status'] = $this->add_locale_event($data['us_data'], 'us');

            if (
                $saiving_issets['ka_info_status'] != 'Error' &&
                $saiving_issets['ru_info_status'] != 'Error' &&
                $saiving_issets['us_info_status'] != 'Error'
            ) {
                $action_event_id = $this->add_global_event(
                    $data['global_data'], 
                    $global_blocks,
                    $request,

                    $saiving_issets['ka_info_status'],
                    $saiving_issets['ru_info_status'],
                    $saiving_issets['us_info_status']                    
                );
            }
        }
        else{            
            return response()->json([
                'validation' => $validation_issets
            ], 422);
        }
    }

    public function add_locale_event($data, $locale)
    {
        $new_locale_event = new Locale_event;
        
        $new_locale_event['title']=$data["title"];
        $new_locale_event['locale']=$locale;
        $new_locale_event['short_description']=$data["short_description"];
        $new_locale_event['text']=$data["text"];
        $new_locale_event['info']=$data["info"];

        $saved = $new_locale_event->save();

        if(!$saved){
            App::abort(500, 'Error');
        }
        else{
            return $new_locale_event->id;
        }
    }

    public function add_global_event($global_data, $global_blocks, $request, $ka_info_id, $ru_info_id, $us_info_id)
    {
        $url_title = URLTitleService::get_url_title($global_data["us_title_for_url_title"]); // make url_title from us_title value
        
        $event = new Event;

        if($request->hasFile('image')){
            $event['image'] =  ImageControllService::image_upload('images/event_img/', $request, 'image', 1);
        }
        
        $event['url_title'] = $url_title;

        $event['category']=$global_data["category"];
        $event['published']=$global_data["published"];
        $event['map']=$global_data["map"];

        if ($global_data["start_data"] != '') {
            $event['start_data'] = date("Y-m-d\TH:i:s", strtotime($global_data["start_data"]));
        }
        if ($global_data["end_data"] != '') {
            $event['end_data'] = date("Y-m-d\TH:i:s", strtotime($global_data["end_data"]));
        }

        $local_us = $us_info_id;
        $local_ka = $ka_info_id;
        $local_ru = $ru_info_id;

        $event['us_event_id']=$local_us;
        $event['ka_event_id']=$local_ka;
        $event['ru_event_id']=$local_ru;
        
        $event -> save();

        $inf_blo = json_decode($request->global_blocks, true);
        if($inf_blo['info_block_id'] != 0){
            GeneralInfoService::add_general_info_relatione($global_blocks, $event->id, 'event');
        }

        return $event->id;
    }


    public function global_event_add_validate($global_data)
    {
        $validator = Validator::make($global_data, [
            'published' => 'required',

            'start_data' => 'required',
            'end_data' => 'required',

            'us_title_for_url_title' => 'required|unique:events,url_title',
        ]);
        if ($validator->fails()) {
            return $validator->messages();
        }
    }

    public function global_event_edit_validate($global_data)
    {
        $validator = Validator::make($global_data, [
            'published' => 'required',

            'start_data' => 'required',
            'end_data' => 'required',
        ]);
        if ($validator->fails()) {
            return $validator->messages();
        }
    }

    public function image_validate($request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'nullable | image | mimes:jpeg,png,jpg,gif,svg | max:2048',
        ]);
        if ($validator->fails()) {
            return $validator->messages();
        }
    }

    public function local_event_validate($data)
    {
        $validator = Validator::make($data, [
            'title' => 'required',
            'short_description' => 'required',
            'text' => 'required',
        ]);
        if ($validator->fails()) {
            return $validator->messages();
        }
    }
}
