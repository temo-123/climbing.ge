<?php

namespace App\Services\Abstract;

use Carbon\Carbon;
use Validator;

// use App\Services\ImageControllService;
use App\Services\URLTitleService;

// abstract class LocaleContentControllService
class LocaleContentControllService
{
    
    /*
    *
    * This function create new locale and global dataase value
    *
    * Function get 6 parameters
    *
    * $data             - locale and global data
    * $global_model     - gloal data model
    * $local_model      - local data model
    * $prefix           - content prefix (It is necessary that the prefix be common both for the database and for the content of the form!)
    * $request          - $request
    * $image_path       - article images dir path (parameter is optional, if image value in db tab not exist)
    *
    */
    public static function add_content($data, $global_model, $local_model, $prefix, $request, $image_path = false)
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

        // $ru_validate = (new static)->local_content_validate($data['ru'.$prefix], 'ru');
        // if ($ru_validate != null) {
        //     $validation_issets['ru_info_validation'] = $ru_validate;
        // }
        // else{
        //     $validation_issets['ru_info_validation'] = false;
        // }

        $global_validate = (new static)->global_content_validate($data['global'.$prefix]);
        if ($global_validate != null) {
            $validation_issets['global_info_validation'] = $global_validate;
        }
        else{
            $validation_issets['global_info_validation'] = false;
        }

        if (
            !$validation_issets['global_info_validation'] && 
            // !$validation_issets['ru_info_validation'] && 
            !$validation_issets['ka_info_validation'] && 
            !$validation_issets['us_info_validation']
        ) {
            $saiving_issets['ka_info_status'] = (new static)->add_locale_content($data['ka'.$prefix], 'ka', $local_model);
            // $saiving_issets['ru_info_status'] = (new static)->add_locale_content($data['ru'.$prefix], 'ru', $local_model);
            $saiving_issets['us_info_status'] = (new static)->add_locale_content($data['us'.$prefix], 'us', $local_model);

            if (
                $saiving_issets['ka_info_status'] != 'Error' &&
                // $saiving_issets['ru_info_status'] != 'Error' &&
                $saiving_issets['us_info_status'] != 'Error'
            ) {
                $saiving_issets['global_info_status'] = (new static)->addGlobalArticle(
                    $data['global'.$prefix],
                    $data['us'.$prefix],

                    $request,

                    $saiving_issets['ka_info_status'],
                    // $saiving_issets['ru_info_status'],
                    $saiving_issets['us_info_status'],

                    $global_model,

                    $prefix,

                    $image_path
                );

                if (
                    $saiving_issets['global_info_status'] != 'Error' && gettype($saiving_issets['global_info_status']) != 'object'
                ) {
                    $info = [
                        'us'.$prefix.'_id' => $saiving_issets['us_info_status'],
                        'ka'.$prefix.'_id' => $saiving_issets['ka_info_status'],
                        // 'ru'.$prefix.'_id' => $saiving_issets['us_info_status'],
                        'global'.$prefix.'_id' => $saiving_issets['global_info_status']
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

    private static function addGlobalArticle($global_data, $us_data, $request, $ka_info_id, $us_info_id, $global_model, $prefix, $image_path)
    {
        $global_data['url_title'] = URLTitleService::get_url_title($us_data["title"]); // make url_title from us_title value

        $url_title_valid = (new static)->adding_url_title_validate($global_data, $global_model);
        
        if ($url_title_valid != false) {
            return $url_title_valid;
        }

        if($request->hasFile('image') && $image_path){
            $global_data['image'] =  ImageControllService::image_upload($image_path, $request, 'image', 1);
        }

        $global_data['published']=$global_data["published"];

        $global_data['us'.$prefix.'_id']=$us_info_id;
        $global_data['ka'.$prefix.'_id']=$ka_info_id;
        // $global_data['ru'.$prefix.'_id']=$ru_info_id;

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
    * This function edit new locale and global dataase value
    *
    * Function get 6 parameters
    *
    * $data             - locale and global data
    * $global_model     - gloal data model
    * $local_model      - local data model
    * $prefix           - content prefix (It is necessary that the prefix be common both for the database and for the content of the form!)
    * $request          - $request
    * $image_path       - article images dir path (parameter is optional, if image value in db tab not exist)
    *
    */
    public static function edit_content($global_model, $local_model, $prefix, $request, $image_path = false)
    {
        $validation_issets = [];

        $data = json_decode($request->data, true );

        // dd($data);

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

        // $ru_validate = (new static)->local_content_validate($data['ru'.$prefix], 'ru');
        // if ($ru_validate != null) {
        //     $validation_issets['ru_info_validation'] = $ru_validate;
        // }
        // else{
        //     $validation_issets['ru_info_validation'] = false;
        // }

        $global_validate = (new static)->global_content_validate($data['global'.$prefix]);
        if ($global_validate != null) {
            $validation_issets['global_info_validation'] = $global_validate;
        }
        else{
            $validation_issets['global_info_validation'] = false;
        }

        if (//!$validation_issets['ru_info_validation'] && 
            !$validation_issets['ka_info_validation'] && 
            !$validation_issets['us_info_validation']
        ) {
            $locale_content_values = (new static)->edit_global_content(
                $data['global'.$prefix],  
                $data['us'.$prefix],
                $data['global'.$prefix]['id'],
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
                // (new static)->edit_locale_content($data['ru'.$prefix], $locale_content_values['ru'.$prefix.'_id'], $local_model);
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

        if(array_key_exists('is_change_url_title', $us_data) && $us_data['is_change_url_title']){
            if($us_data['is_change_url_title']){
                $global_data['url_title'] = URLTitleService::get_url_title($us_data["title"]); // make url_title from us_title value

                $url_title_valid = (new static)->editing_url_title_validate($global_data, $global_model);
                
                if ($url_title_valid != false) {
                    return $url_title_valid;
                }
            }
        }
        
        if($request->hasFile('image') && $image_path){
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
                // 'ru'.$prefix.'_id' => $editing_global_content['ru'.$prefix.'_id'],
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
    * This function edit new locale and global dataase value
    *
    * Function get 6 parameters
    *
    * $global_id        - deliting global data value ID
    * $global_model     - gloal data model
    * $local_model      - local data model
    * $prefix           - content prefix (It is necessary that the prefix be common both for the database and for the content of the form!)
    * $image_prefix     - image value name in db
    * $image_path       - article images dir path (parameter is optional, if image value in db tab not exist)
    *
    */
    public static function del_content($global_id, $global_model, $local_model, $prefix, $image_prefix, $image_path = false)
    {
        $gloal_content = $global_model::where('id', '=', $global_id)->first();



        // Handle Articles model specifically to prevent foreign key constraint violations
        $isArticleModel = ($global_model === 'App\Models\Guide\Article') || 
                          ($global_model === \App\Models\Guide\Article::class) ||
                          (is_string($global_model) && str_contains($global_model, 'Article')) ||
                          (is_object($global_model) && $global_model instanceof \App\Models\Guide\Article);
        
        if ($isArticleModel) {
            // Delete child records first to prevent foreign key constraint violations
            
            // Delete spot_rocks_images
            $spotRockImages = \App\Models\Guide\Spot_rocks_image::where('article_id', '=', $global_id)->get();
            foreach ($spotRockImages as $spotImage) {
                if ($spotImage->image && $image_path) {
                    ImageControllService::image_delete('images/spot_rocks_img/', $spotImage, 'image');
                }
                $spotImage->delete();
            }
            
            // Delete article_images (gallery images)
            $articleImages = \App\Models\Guide\Article_image::where('article_id', '=', $global_id)->get();
            foreach ($articleImages as $articleImage) {
                if ($articleImage->image && $image_path) {
                    ImageControllService::image_delete('images/article_gallery_img/', $articleImage, 'image');
                }
                $articleImage->delete();
            }
            

            // For Articles, we need to handle the cascade properly
            // Store locale IDs before deleting the global content
            $us_content_id = $gloal_content['us'.$prefix.'_id'];
            // $ru_content_id = $gloal_content['ru'.$prefix.'_id'];
            $ka_content_id = $gloal_content['ka'.$prefix.'_id'];
            
            if($gloal_content->$image_prefix && $image_path){
                ImageControllService::image_delete($image_path, $gloal_content, $image_prefix);
            }

            $gloal_content->delete();
            
            // Now delete the locale content manually since Article deletion won't cascade to locale_articles
            // (locale_articles is not directly related to articles, it's referenced via foreign keys)
            $us_content = $local_model::where('id', '=', $us_content_id)->first();
            // $ru_content = $local_model::where('id', '=', $ru_content_id)->first();
            $ka_content = $local_model::where('id', '=', $ka_content_id)->first();
            
            if($us_content) $us_content->delete();
            // if($ru_content) $ru_content->delete();
            if($ka_content) $ka_content->delete();
            
            return;
        }

        $us_content = $local_model::where('id',strip_tags($gloal_content['us'.$prefix.'_id']))->first();
        // $ru_content = $local_model::where('id',strip_tags($gloal_content['ru'.$prefix.'_id']))->first();
        $ka_content = $local_model::where('id',strip_tags($gloal_content['ka'.$prefix.'_id']))->first();
        
        if($gloal_content->$image_prefix && $image_path){
            ImageControllService::image_delete($image_path, $gloal_content, $image_prefix);
        }

        $us_content ->delete();
        // $ru_content ->delete();
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

        if ($validator->fails()) {
            return $validator->messages();
        }
    }
    private static function local_content_validate($data)
    {
        $validator = Validator::make($data, [
            'title' => 'required | max:190',
            'short_description' => 'required | max:190',
            'text' => 'required',
        ]);

        if ($validator->fails()) {
            return $validator->messages();
        }
    }

    private static function editing_url_title_validate($data, $gloal_model)
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

    private static function adding_url_title_validate($data, $gloal_model)
    {
        $gloal_model = new $gloal_model;
        $databaseName = $gloal_model->getTable();
        
        $validator = Validator::make($data, [
            'url_title' => 'required | max:190 | unique:'.$databaseName.',url_title',
        ]);

        if ($validator->fails()) {
            return $validator->messages();
        }
        return false;
    }


}