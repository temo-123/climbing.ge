<?php

namespace App\Services\Abstract;

use Carbon\Carbon;
use Validator;

use App\Services\ImageControllService;
use App\Services\URLTitleService;

abstract class LocaleContentControllService
{
    /*
    *
    * This function get global and local content in array for content
    *
    * Function get 4 parameters
    *
    */
    public static function add_content($data, $global_model, $local_model, $prefix, $request, $image_path)
    {
        $validation_issets = [];

        $ka_validate = (new static)->local_content_validate($data['ka'.$prefix], 'ka');
        if ($ka_validate != null) {
            $validation_issets['ka_info_validation'] = $ka_validate;
        }
        else{
            $validation_issets['ka_info_validation'] = false;
        }

        $us_validate = (new static)->local_content_validate($data['us'.$prefix], 'us');
        if ($us_validate != null) {
            $validation_issets['us_info_validation'] = $us_validate;
        }
        else{
            $validation_issets['us_info_validation'] = false;
        }

        $ru_validate = (new static)->local_content_validate($data['ru'.$prefix], 'ru');
        if ($ru_validate != null) {
            $validation_issets['ru_info_validation'] = $ru_validate;
        }
        else{
            $validation_issets['ru_info_validation'] = false;
        }

        $global_validate = (new static)->global_content_validate($data['global'.$prefix]);
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
            $saiving_issets['ka_info_status'] = (new static)->add_locale_content($data['ka'.$prefix], 'ka', $local_model);
            $saiving_issets['ru_info_status'] = (new static)->add_locale_content($data['ru'.$prefix], 'ru', $local_model);
            $saiving_issets['us_info_status'] = (new static)->add_locale_content($data['us'.$prefix], 'us', $local_model);

            if (
                $saiving_issets['ka_info_status'] != 'Error' &&
                $saiving_issets['ru_info_status'] != 'Error' &&
                $saiving_issets['us_info_status'] != 'Error'
            ) {
                $saiving_issets['global_info_status'] = (new static)->addGlobalArticle(
                    $data['global'.$prefix],
                    $data['us'.$prefix],

                    $request,

                    $saiving_issets['ka_info_status'],
                    $saiving_issets['ru_info_status'],
                    $saiving_issets['us_info_status'],

                    $global_model,

                    $prefix,

                    $image_path
                );

                if (
                    $saiving_issets['global_info_status'] != 'Error' && gettype($saiving_issets['global_info_status']) != 'object'
                ) {
                    $info = [
                        'us'.$prefix.'_id' => $saiving_issets['ka_info_status'],
                        'ka'.$prefix.'_id' => $saiving_issets['ru_info_status'],
                        'ru'.$prefix.'_id' => $saiving_issets['us_info_status'],
                        'global'.$prefix.'_id' => $saiving_issets['ka_info_status']
                    ];
                    
                    return (object) array('original' => $info);
                }
                else{    
                    $validation_issets['global_info_validation'] = $saiving_issets['global_info_status'];
                    return response()->json([
                        'validation' => $validation_issets
                    ], 422);
                }

            }
        }
        else{            
            return response()->json([
                'validation' => $validation_issets
            ], 422);
        }
    }

    private static function addGlobalArticle($global_data, $us_data, $request, $ka_info_id, $ru_info_id, $us_info_id, $global_model, $prefix, $image_path)
    {
        $global_data['url_title'] = URLTitleService::get_url_title($us_data["title"]); // make url_title from us_title value

        $url_title_valid = (new static)->url_title_validate($global_data, $global_model);

        if ($url_title_valid != false) {
            return $url_title_valid;
        }

        if($request->hasFile('image')){
            $global_data['image'] =  ImageControllService::image_upload($image_path, $request, 'image', 1);
        }

        $global_data['published']=$global_data["published"];

        $global_data['us'.$prefix.'_id']=$us_info_id;
        $global_data['ka'.$prefix.'_id']=$ka_info_id;
        $global_data['ru'.$prefix.'_id']=$ru_info_id;

        $saved = $global_model::insertGetId($global_data); 

        if(!$saved){
            // App::abort(500, 'Error');
        }
        else{
            return $saved;
        }
    }

    private static function add_locale_content($data, $locale, $local_model)
    {
        $data['locale']=$locale;

        $saved = $local_model::insertGetId($data);
        
        if(!$saved){
            App::abort(500, 'Error');
        }
        else{
            return $saved;
        }
    }
    
    
    /*
    *
    * This function get global and local content in array for content
    *
    * Function get 4 parameters
    *
    */
    public static function edit_content($data, $global_model, $local_model, $prefix, $request, $image_path)
    {
        $validation_issets = [];

        $data = json_decode($request->data, true );

        $ka_validate = (new static)->local_content_validate($data['ka'.$prefix], 'ka');
        if ($ka_validate != null) {
            $validation_issets['ka_info_validation'] = $ka_validate;
        }
        else{
            $validation_issets['ka_info_validation'] = false;
        }

        $us_validate = (new static)->local_content_validate($data['us'.$prefix], 'us');
        if ($us_validate != null) {
            $validation_issets['us_info_validation'] = $us_validate;
        }
        else{
            $validation_issets['us_info_validation'] = false;
        }

        $ru_validate = (new static)->local_content_validate($data['ru'.$prefix], 'ru');
        if ($ru_validate != null) {
            $validation_issets['ru_info_validation'] = $ru_validate;
        }
        else{
            $validation_issets['ru_info_validation'] = false;
        }

        $global_validate = (new static)->global_content_validate($data['global'.$prefix]);
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
            $locale_content_values = (new static)->edit_global_content(
                $data['global'.$prefix],  
                $data['us'.$prefix],
                $data['global_article']['id'],
                $global_model,
                $request,
                $image_path,
                $prefix
            );
            
            if (
                $locale_content_values != 'Error' && gettype($locale_content_values) != 'object'
                // $locale_content_values != 'Error' && !array_key_exists('url_title', $locale_content_values->messages())
            ) {
                (new static)->edit_locale_content($data['ka'.$prefix], $locale_content_values['ka'.$prefix.'_id'], $local_model);
                (new static)->edit_locale_content($data['ru'.$prefix], $locale_content_values['ru'.$prefix.'_id'], $local_model);
                (new static)->edit_locale_content($data['us'.$prefix], $locale_content_values['us'.$prefix.'_id'], $local_model);
                
                return (object) array('original' => $locale_content_values);
            }
            else{    
                $validation_issets['global_info_validation'] = $locale_content_values;
                return response()->json([
                    'validation' => $validation_issets
                ], 422);
            }
        }
        else{            
            return response()->json([
                'validation' => $validation_issets
            ], 422);
        }
    }

    private static function edit_global_content($global_data, $us_data, $id, $global_model, $request, $image_path, $prefix)
    {
        $editing_global_content = $global_model::where('id', '=', $id)->first();

        if(array_key_exists('is_change_url_title', $us_data) ){
            if($us_data['is_change_url_title']){
                $global_data['url_title'] = URLTitleService::get_url_title($us_data["title"]); // make url_title from us_title value

                $url_title_valid = (new static)->url_title_validate($global_data, $global_model);
                
                if ($url_title_valid != false) {
                    return $url_title_valid;
                }
            }
        }
        
        if($request->hasFile('image')){
            $global_data['image'] =  ImageControllService::image_update($image_path, $editing_global_content, $request, 'image', 'image', 1);
        }

        $saved = $editing_global_content->update($global_data); 

        if(!$saved){
            // App::abort(500, 'Error');
            return 'Error';
        }
        else{
            return $locale_tabs = [
                'us'.$prefix.'_id' => $editing_global_content['us'.$prefix.'_id'],
                'ka'.$prefix.'_id' => $editing_global_content['ka'.$prefix.'_id'],
                'ru'.$prefix.'_id' => $editing_global_content['ru'.$prefix.'_id'],
                'global'.$prefix.'_id' => $id
            ];
            // return $locale_tabs;
        }
    }

    private static function edit_locale_content($data, $id, $local_model)
    {
        $editing_locale_content = $local_model::where('id', '=', $id)->first();

        $saved = $editing_locale_content->update($data);

        if(!$saved){
            // App::abort(500, 'Error');
            return 'Error';
        }
        else{
            return $editing_locale_content->id;
        }
    }
    
    
    /*
    *
    * This function get global and local content in array for content
    *
    * Function get 4 parameters
    *
    */
    public static function del_content($global_id, $global_model, $local_model, $content_prefix, $image_prefix, $image_path)
    {
        $gloal_content = $global_model::where('id', '=', $global_id)->first();

        $us_content = $local_model::where('id',strip_tags($gloal_content['us'.$content_prefix.'_id']))->first();
        $ru_content = $local_model::where('id',strip_tags($gloal_content['ru'.$content_prefix.'_id']))->first();
        $ka_content = $local_model::where('id',strip_tags($gloal_content['ka'.$content_prefix.'_id']))->first();
        
        if($gloal_content->$image_prefix){
            ImageControllService::image_delete($image_path, $gloal_content, $image_prefix);
        }

        $us_content ->delete();
        $ru_content ->delete();
        $ka_content ->delete();

        $gloal_content ->delete();
    }
    
    
    /*
    *
    * Data validators
    *
    */
    private static function global_content_validate($global_data)
    {
        $validator = Validator::make($global_data, [
            'published' => 'required',
        ]);

// dd($validator->fails());
        if ($validator->fails()) {
            return $validator->messages();
        }
    }
    private static function local_content_validate($data)
    {
        $validator = Validator::make($data, [
            'title' => 'required | max:190',
            'short_description' => 'required',
            'text' => 'required',
        ]);

        if ($validator->fails()) {
            return $validator->messages();
        }
    }
    private static function url_title_validate($data, $gloal_model)
    {
        $databaseName = $gloal_model::first()->getTable();

        $validator = Validator::make($data, [
            'url_title' => 'required | max:190 | unique:'.$databaseName.',url_title',
        ]);

        if ($validator->fails()) {
            return $validator->messages();
        }
        return false;
    }

}