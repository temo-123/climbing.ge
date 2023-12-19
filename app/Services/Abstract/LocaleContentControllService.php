<?php

namespace App\Services\Abstract;

use Carbon\Carbon;

abstract class LocaleContentControllService
{

    /*
    *
    * This function get global and local content in array for content
    *
    * Function get 4 parameters
    *
    */
    public function add_service(Request $request)
    {
        $validation_issets = [];

        $data = json_decode($request->data, true );

        $ka_validate = $this->local_service_validate($data['ka_data']);
        if ($ka_validate != null) {
            $validation_issets['ka_info_validation'] = $ka_validate;
        }
        else{
            $validation_issets['ka_info_validation'] = false;
        }

        $us_validate = $this->local_service_validate($data['us_data']);
        if ($us_validate != null) {
            $validation_issets['us_info_validation'] = $us_validate;
        }
        else{
            $validation_issets['us_info_validation'] = false;
        }

        $ru_validate = $this->local_service_validate($data['ru_data']);
        if ($ru_validate != null) {
            $validation_issets['ru_info_validation'] = $ru_validate;
        }
        else{
            $validation_issets['ru_info_validation'] = false;
        }

        $global_validate = $this->adding_global_service_validate($data['global_data']);
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
            $saiving_issets['ka_info_status'] = $this->add_locale_service($data['ka_data'], 'ka');
            $saiving_issets['ru_info_status'] = $this->add_locale_service($data['ru_data'], 'ru');
            $saiving_issets['us_info_status'] = $this->add_locale_service($data['us_data'], 'us');

            if (
                $saiving_issets['ka_info_status'] != 'Error' &&
                $saiving_issets['ru_info_status'] != 'Error' &&
                $saiving_issets['us_info_status'] != 'Error'
            ) {
                $action_service_id = $this->addGlobalArticle(
                    $data['global_data'],

                    $saiving_issets['ka_info_status'],
                    $saiving_issets['ru_info_status'],
                    $saiving_issets['us_info_status']                    
                );
                
                if($request->hasFile('service_images')){
                    $this->add_secvice_images($request['service_images'], $action_service_id);
                }
            }
        }
        else{            
            return response()->json([
                'validation' => $validation_issets
            ], 422);
        }
    }

    public function addGlobalArticle($global_data, $ka_info_id, $ru_info_id, $us_info_id)
    {
        $url_title = URLTitleService::get_url_title($global_data["us_title_for_url_title"]); // make url_title from us_title value

        $service = new Service;
        
        $service['url_title'] = $url_title;

        $service['published']=$global_data["published"];

        $local_us = $us_info_id;
        $local_ka = $ka_info_id;
        $local_ru = $ru_info_id;

        $service['us_service_id']=$local_us;
        $service['ka_service_id']=$local_ka;
        $service['ru_service_id']=$local_ru;
        
        $saved = $service -> save();

        if(!$saved){
            App::abort(500, 'Error');
        }
        else{
            return $service->id;
        }
    }

    public function add_locale_service($data, $locale)
    {
        $service = new Locale_service;
        
        $service['title']=$data["title"];
        $service['locale']=$locale;
        $service['short_description']=$data["short_description"];
        $service['text']=$data["text"];

        $saved = $service->save();

        if(!$saved){
            App::abort(500, 'Error');
        }
        else{
            return $service->id;
        }
    }
    
    
    /*
    *
    * This function get global and local content in array for content
    *
    * Function get 4 parameters
    *
    */public function edit_service(Request $request)
    {
        $validation_issets = [];

        $data = json_decode($request->data, true );

        $ka_validate = $this->local_service_validate($data['ka_data']);
        if ($ka_validate != null) {
            $validation_issets['ka_info_validation'] = $ka_validate;
        }
        else{
            $validation_issets['ka_info_validation'] = false;
        }

        $us_validate = $this->local_service_validate($data['us_data']);
        if ($us_validate != null) {
            $validation_issets['us_info_validation'] = $us_validate;
        }
        else{
            $validation_issets['us_info_validation'] = false;
        }

        $ru_validate = $this->local_service_validate($data['ru_data']);
        if ($ru_validate != null) {
            $validation_issets['ru_info_validation'] = $ru_validate;
        }
        else{
            $validation_issets['ru_info_validation'] = false;
        }

        $global_validate = $this->adding_global_service_validate($data['global_data']);
        if ($global_validate != null) {
            $validation_issets['global_info_validation'] = $global_validate;
        }
        else{
            $validation_issets['global_info_validation'] = false;
        }

        if (!$validation_issets['ru_info_validation'] && 
            !$validation_issets['ka_info_validation'] && 
            !$validation_issets['us_info_validation']
        ) {
            $locale_service_values = $this->edit_global_service(
                $data['global_data'],  
                $request->service_id                
            );
            if (
                $locale_service_values != 'Error'
            ) {
                $saiving_issets['ka_info_status'] = $this->edit_locale_service($data['ka_data'], $locale_service_values['ka_id']);
                $saiving_issets['ru_info_status'] = $this->edit_locale_service($data['ru_data'], $locale_service_values['ru_id']);
                $saiving_issets['us_info_status'] = $this->edit_locale_service($data['us_data'], $locale_service_values['us_id']);
                
                if($request->hasFile('service_new_images')){
                    $this->add_secvice_images($request['service_new_images'], $locale_service_values['global_id']);
                }
            }
        }
        else{            
            return response()->json([
                'validation' => $validation_issets
            ], 422);
        }
    }

    public function edit_global_service($global_data, $id)
    {
        $editing_local_service = Service::where('id', '=', $id)->first();

        if($global_data['change_url_title']){
            $url_title = URLTitleService::get_url_title($global_data["us_title_for_url_title"]); // make url_title from us_title value
            $editing_local_service['url_title'] = $url_title;
        }

        $editing_local_service['published']=$global_data["published"];
        
        $saved = $editing_local_service -> save();
        
        if(!$saved){
            App::abort(500, 'Error');
        }
        else{
            $locale_tabs = [
                'us_id' => $editing_local_service->us_service_id,
                'ka_id' => $editing_local_service->ka_service_id,
                'ru_id' => $editing_local_service->ru_service_id,
                'global_id' => $editing_local_service->id
            ];
            return $locale_tabs;
        }
    }

    public function edit_locale_service($data, $id)
    {
        $editing_locale_service = Locale_service::where('id', '=', $id)->first();
        
        $editing_locale_service['title']=$data["title"];
        $editing_locale_service['short_description']=$data["short_description"];
        $editing_locale_service['text']=$data["text"];

        $saved = $editing_locale_service->save();

        if(!$saved){
            App::abort(500, 'Error');
        }
        else{
            return $editing_locale_service->id;
        }
    }
    
    
    /*
    *
    * This function get global and local content in array for content
    *
    * Function get 4 parameters
    *
    */
    public function del_service(Request $request)
    {
        $service = Service::where('id', '=', $request->service_id)->first();
        $service_images_count = Service_image::where('service_id', '=', $service->id)->count();

        if($service_images_count > 0){
            $service_images = Service_image::where('service_id', '=', $service->id)->get();
            // dd($service_images);
            foreach ($service_images as $image) {
                ImageControllService::image_delete('images/service_img/', $image, 'image');
                $image ->delete();
            }
        }
        $service ->delete();
    }
}