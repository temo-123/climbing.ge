<?php

namespace App\Services\Abstract;

use Carbon\Carbon;

use App\Services\Abstract\LocaleContentControllService;

abstract class LocaleContentService extends LocaleContentControllService
{
    /*
    *
    * This function get global and local content list in array for creating list
    *
    * Function get 4 parameters
    *
    * $global_data                      - Global content array where need get locale and ID
    * $local_content_model              - Locale content model for get locale content
    * $locale_content_value_prefix      - Local content id prefix in DB. DB value is "us_article_id" -> prefix is "_article_id"
    * $locale                           - Actyve locale. Default en/us
    *
    */
    public static function get_locale_content_use_locale($global_data, $local_content_model, $locale_content_value_prefix, $locale = 'en')
    {
        $contents = array();

        if($locale == 'en' || $locale == 'us'){
            $locale = 'us';
        }

        foreach ($global_data as $content) {
            $localzatione = $locale.$locale_content_value_prefix;

            $locale_contents = $local_content_model::where('id', '=', $content->$localzatione)->get();

            foreach ($locale_contents as $us_content) {
                if ($us_content->id == $content->$localzatione) {

                    $new_flag = (new static)->get_new_content_pin($content);

                    array_push($contents, [
                        "locale_data"=>$locale_contents[0], 
                        "global_data"=>$content
                    ]);
                    
                    $contents[array_key_last($contents)]["global_data"]['new_flag'] = $new_flag;
                }
            }
        }

        return $contents;
    }

    /*
    *
    * This function check if content edded on last 30 day and return tru or fals
    *
    * Get only 1 parameters
    *
    * $content                           - Ii is a content row, with one need to ceck
    *
    */
    private static function get_new_content_pin($content){
        $time = false;
        if($content->created_at){
            if ($content->created_at->lt(Carbon::now()->subDays(30))){
                $time = false;
            } else {
                $time = true;
            }
            return $time;
        }
    }


    /*
    *
    * This function get global and local content in array for content
    *
    * Function get 4 parameters
    *
    * $global_data                      - Global content array where need get locale and ID
    * $local_content_model              - Locale content model for get locale content
    * $locale_content_value_prefix      - Local content id prefix in DB. DB value is "us_article_id" -> prefix is "_article_id"
    * $locale                           - Actyve locale. Default en/us
    *
    */
    public static function get_locale_content_in_page($global_data, $local_content_model, $locale_content_value_prefix, $locale = 'en')
    {
        $locale_content = array();

        if($locale == 'en' || $locale == 'us'){
            $locale = 'us';
        }
        
        $localzatione = $locale.$locale_content_value_prefix;
        $loc_content = $local_content_model::where('id', '=', $global_data->$localzatione)->first();

        array_push($locale_content, 
            [
                "locale_data"=>$loc_content, 
                "global_data"=>$global_data,
            ]
        );

        return $locale_content[0];
    }
}