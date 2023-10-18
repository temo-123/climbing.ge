<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Article;
use App\Models\Locale_bisnes;
use App\Models\Suport_local_bisnes;
use App\Models\Suport_local_bisnes_image;

use App\Services\URLTitleService;
use App\Services\ImageControllService;

use Validator;

class LocalBisnesController extends Controller
{
    public function get_local_bisnes_for_article(Request $request)
    {
        $action_data = date("Y/m/d H:i:s");

        $article = Article::where('url_title', '=', $request->article_url_title)->first();
        $article_bisnes_global_data = $article->bisnes;

        if($article_bisnes_global_data){

            $pulic_year = $article_bisnes_global_data->published_data;
            $public_month = $article_bisnes_global_data->published_data;
            $pulic_day = $article_bisnes_global_data->published_data;

            if ($article_bisnes_global_data->public_totaly) {
                $article_bisnes_local_data = $this->get_article_bisnes_local_data($request->locale == 'ka', $article_bisnes_global_data);
                $bisnes_images = $article_bisnes_global_data->bisnes_images[0];

                $data = [
                    'global_data' => $article_bisnes_global_data,
                    'local_data' => $article_bisnes_local_data,
                    'image' => $bisnes_images
                ];
            }
            else if(
                date('m', strtotime($public_month)) >= date('m', strtotime($action_data)) &&
                date('Y', strtotime($pulic_year)) == date('Y', strtotime($action_data))
            ){

                $article_bisnes_local_data = $this->get_article_bisnes_local_data($request->locale == 'ka', $article_bisnes_global_data);

                // if($request->locale == 'ka'){
                //     $article_bisnes_local_data = $article_bisnes_global_data->ka_bisnes;
                // }
                // else if($request->locale == 'ru'){
                //     $article_bisnes_local_data = $article_bisnes_global_data->ru_bisnes;
                // }
                // else{
                //     $article_bisnes_local_data = $article_bisnes_global_data->us_bisnes;
                // }

                if(date('d', strtotime($pulic_day)) > date('d', strtotime($action_data))){
                    $bisnes_images = $article_bisnes_global_data->bisnes_images[0];

                    $data = [
                        'global_data' => $article_bisnes_global_data,
                        'local_data' => $article_bisnes_local_data,
                        'image' => $bisnes_images
                    ];
                }
                elseif (
                        date('m', strtotime($pulic_day)) > date('m', strtotime($action_data)) &&
                        date('Y', strtotime($pulic_day)) == date('Y', strtotime($action_data))
                    ) {

                    $bisnes_images = $article_bisnes_global_data->bisnes_images[0];

                    $data = [
                        'global_data' => $article_bisnes_global_data,
                        'local_data' => $article_bisnes_local_data,
                        'image' => $bisnes_images
                    ];
                }
            }
                return $data;
        }
    }

    private function get_article_bisnes_local_data($lang, $article_bisnes_global_data){
        $article_bisnes_local_data = [];

        if($lang == 'ka'){
            $article_bisnes_local_data = $article_bisnes_global_data->ka_bisnes;
        }
        else if($lang == 'ru'){
            $article_bisnes_local_data = $article_bisnes_global_data->ru_bisnes;
        }
        else{
            $article_bisnes_local_data = $article_bisnes_global_data->us_bisnes;
        }

        return $article_bisnes_local_data;
    }

    public function get_local_bisnes_in_page(Request $request)
    {
        $article_bisnes_global_data = Suport_local_bisnes::where('url_title', '=', $request->url_title)->first();
        if ($article_bisnes_global_data) {
            if($request->locale == 'ka'){
                $article_bisnes_local_data = $article_bisnes_global_data->ka_bisnes;
            }
            else if($request->locale == 'ru'){
                $article_bisnes_local_data = $article_bisnes_global_data->ru_bisnes;
            }
            else{
                $article_bisnes_local_data = $article_bisnes_global_data->us_bisnes;
            }
    
            $bisnes_images = $article_bisnes_global_data->bisnes_images;
    
            $data = [
                'global_data' => $article_bisnes_global_data,
                'locale_data' => $article_bisnes_local_data,
                'images' => $bisnes_images
            ];
            return $data;
        }
        // else{
        //     return 'No ';
        // }
    }

    public function get_local_bisneses(Request $request)
    {
        return Suport_local_bisnes::get();
    }

    public function add_local_bisnes(Request $request)
    {
        $validation_issets = [];

        $data = json_decode($request->data, true );

        $ka_validate = $this->local_bisnes_validate($data['ka_data']);
        if ($ka_validate != null) {
            $validation_issets['ka_info_validation'] = $ka_validate;
        }
        else{
            $validation_issets['ka_info_validation'] = false;
        }

        $us_validate = $this->local_bisnes_validate($data['us_data']);
        if ($us_validate != null) {
            $validation_issets['us_info_validation'] = $us_validate;
        }
        else{
            $validation_issets['us_info_validation'] = false;
        }

        $ru_validate = $this->local_bisnes_validate($data['ru_data']);
        if ($ru_validate != null) {
            $validation_issets['ru_info_validation'] = $ru_validate;
        }
        else{
            $validation_issets['ru_info_validation'] = false;
        }

        $global_validate = $this->adding_global_bisnes_validate($data['global_data']);
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
            $saiving_issets['ka_info_status'] = $this->add_locale_bisnes($data['ka_data'], 'ka');
            $saiving_issets['ru_info_status'] = $this->add_locale_bisnes($data['ru_data'], 'ru');
            $saiving_issets['us_info_status'] = $this->add_locale_bisnes($data['us_data'], 'us');

            if (
                $saiving_issets['ka_info_status'] != 'Error' &&
                $saiving_issets['ru_info_status'] != 'Error' &&
                $saiving_issets['us_info_status'] != 'Error'
            ) {
                $action_bisnes_id = $this->addGlobalArticle(
                    $data['global_data'],

                    $saiving_issets['ka_info_status'],
                    $saiving_issets['ru_info_status'],
                    $saiving_issets['us_info_status']                    
                );
                
                if($request->hasFile('bisnes_images')){
                    $this->add_bisnes_images($request['bisnes_images'], $action_bisnes_id);
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

        $local_bisnes = new Suport_local_bisnes;
        
        $local_bisnes['url_title'] = $url_title;

        $local_bisnes['article_id'] = $global_data["article_id"];

        if (!$global_data["public_totaly"]) {
            $local_bisnes['published_data']=$global_data["published_data"];
        }
        else{
            $editing_local_bisnes['published_data']=null;
        }

        $local_bisnes['public_totaly']=$global_data["public_totaly"];

        $local_us = $us_info_id;
        $local_ka = $ka_info_id;
        $local_ru = $ru_info_id;

        $local_bisnes['us_bisnes_id']=$local_us;
        $local_bisnes['ka_bisnes_id']=$local_ka;
        $local_bisnes['ru_bisnes_id']=$local_ru;
        
        $saved = $local_bisnes -> save();

        if(!$saved){
            App::abort(500, 'Error');
        }
        else{
            return $local_bisnes->id;
        }
    }

    public function add_locale_bisnes($data, $locale)
    {
        $new_locale_bisnes = new Locale_bisnes;
        
        $new_locale_bisnes['title']=$data["title"];
        $new_locale_bisnes['locale']=$locale;
        $new_locale_bisnes['short_description']=$data["short_description"];
        $new_locale_bisnes['text']=$data["text"];

        $saved = $new_locale_bisnes->save();

        if(!$saved){
            App::abort(500, 'Error');
        }
        else{
            return $new_locale_bisnes->id;
        }
    }
    public function add_bisnes_images($images, $bisnes_id)
    {
        foreach ($images as $image) {
            // if($file_new_name = ImageControllService::upload_loop_image('images/suport_local_bisnes_img/', $image, 1)){
                $file_new_name;
                $file_new_name = ImageControllService::upload_loop_image('images/suport_local_bisnes_img/', $image, 1);
                if(file_exists(public_path('images/suport_local_bisnes_img/') . $file_new_name)){
                    $new_option_image = new Suport_local_bisnes_image;
            
                    $new_option_image['image'] = $file_new_name;
                    $new_option_image['bisnes_id'] = $bisnes_id;
            
                    $saiving = $new_option_image -> save();

                    if($saiving){
                        echo 'Upload socsesful \n';
                    }
                }
                else{
                    echo 'Upload error \n';
                }
            // }
        }
    }
    // public function get_local_bisneses()
    // {
    //     # code...
    // }
    public function get_editing_local_bisnes_info(Request $request)
    {
        $bisnes = Suport_local_bisnes::where('id', '=', $request->bisnes_id)->first();

        $data = [
            'global_bisnes' => $bisnes,
            'us_bisnes' => $bisnes->us_bisnes,
            'ka_bisnes' => $bisnes->ka_bisnes,
            'ru_bisnes' => $bisnes->ru_bisnes,

            // 'bisnes_images' => $bisnes->bisnes_images,
        ];

        return $data;
    }
    public function get_bisnes_images(Request $request)
    {
        $bisnes = Suport_local_bisnes::where('id', '=', $request->bisnes_id)->first();

        return $bisnes->bisnes_images;
    }
    public function edit_local_bisnes(Request $request)
    {
        $validation_issets = [];

        $data = json_decode($request->data, true );
        // dd($request->input('files'));

        $ka_validate = $this->local_bisnes_validate($data['ka_data']);
        if ($ka_validate != null) {
            $validation_issets['ka_info_validation'] = $ka_validate;
        }
        else{
            $validation_issets['ka_info_validation'] = false;
        }

        $us_validate = $this->local_bisnes_validate($data['us_data']);
        if ($us_validate != null) {
            $validation_issets['us_info_validation'] = $us_validate;
        }
        else{
            $validation_issets['us_info_validation'] = false;
        }

        $ru_validate = $this->local_bisnes_validate($data['ru_data']);
        if ($ru_validate != null) {
            $validation_issets['ru_info_validation'] = $ru_validate;
        }
        else{
            $validation_issets['ru_info_validation'] = false;
        }

        if (!$validation_issets['ru_info_validation'] && 
            !$validation_issets['ka_info_validation'] && 
            !$validation_issets['us_info_validation']
        ) {
            $locale_bisnes_values = $this->edit_global_bisnes(
                $data['global_data'],  
                $request->bisnes_id                
            );
            if (
                $locale_bisnes_values != 'Error'
            ) {
                $saiving_issets['ka_info_status'] = $this->edit_locale_bisnes($data['ka_data'], $locale_bisnes_values['ka_id']);
                $saiving_issets['ru_info_status'] = $this->edit_locale_bisnes($data['ru_data'], $locale_bisnes_values['ru_id']);
                $saiving_issets['us_info_status'] = $this->edit_locale_bisnes($data['us_data'], $locale_bisnes_values['us_id']);
                
                if($request->hasFile('bisnes_new_images')){
                    $this->add_bisnes_images($request->bisnes_new_images, $locale_bisnes_values['global_id']);
                }
            }
        }
        else{            
            return response()->json([
                'validation' => $validation_issets
            ], 422);
        }
    }

    public function edit_global_bisnes($global_data, $id)
    {
        $editing_local_bisnes = Suport_local_bisnes::where('id', '=', $id)->first();

        if($global_data['change_url_title']){
            $url_title = URLTitleService::get_url_title($global_data["us_title_for_url_title"]); // make url_title from us_title value
            $editing_local_bisnes['url_title'] = $url_title;
        }

        $editing_local_bisnes['article_id']=$global_data["article_id"];

        if (!$global_data["public_totaly"]) {
            $editing_local_bisnes['published_data']=$global_data["published_data"];
        }
        else{
            $editing_local_bisnes['published_data']=null;
        }

        $editing_local_bisnes['public_totaly']=$global_data["public_totaly"];
        
        $saved = $editing_local_bisnes -> save();
        
        if(!$saved){
            App::abort(500, 'Error');
        }
        else{
            $locale_tabs = [
                'us_id' => $editing_local_bisnes->us_bisnes_id,
                'ka_id' => $editing_local_bisnes->ka_bisnes_id,
                'ru_id' => $editing_local_bisnes->ru_bisnes_id,
                'global_id' => $editing_local_bisnes->id
            ];
            return $locale_tabs;
        }
    }

    public function edit_locale_bisnes($data, $id)
    {
        $editing_locale_bisnes = Locale_bisnes::where('id', '=', $id)->first();
        
        $editing_locale_bisnes['title']=$data["title"];
        $editing_locale_bisnes['short_description']=$data["short_description"];
        $editing_locale_bisnes['text']=$data["text"];

        $saved = $editing_locale_bisnes->save();

        if(!$saved){
            App::abort(500, 'Error');
        }
        else{
            return $editing_locale_bisnes->id;
        }
    }
    public function del_local_bisnes(Request $request)
    {
        $bisnes = Suport_local_bisnes::where('id', '=', $request->bisnes_id)->first();
        $bisnes_images_count = Suport_local_bisnes_image::where('bisnes_id', '=', $bisnes->id)->count();

        if($bisnes_images_count > 0){
            $bisnes_images = Suport_local_bisnes_image::where('bisnes_id', '=', $bisnes->id)->get();
            // dd($bisnes_images);
            foreach ($bisnes_images as $image) {
                ImageControllService::image_delete('images/suport_local_bisnes_img/', $image, 'image');
                $image ->delete();
            }
        }
        $bisnes ->delete();
    }

    public function del_local_bisnes_image(Request $request)
    {
        $image = Suport_local_bisnes_image::where('id', '=', $request->image_id)->first();
        // if($image){
            ImageControllService::image_delete('images/suport_local_bisnes_img/', $image, 'image');
            $image ->delete();
        // }
    }

    public function adding_global_bisnes_validate($global_data)
    {
        $validator = Validator::make($global_data, [
            'us_title_for_url_title' => 'required|unique:suport_local_bisneses,url_title',
        ]);
        if ($validator->fails()) {
            return $validator->messages();
        }
    }

    public function local_bisnes_validate($data)
    {
        // dd($data);
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
