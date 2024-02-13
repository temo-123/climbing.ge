<?php

namespace App\Http\Controllers\Api\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Toure;
use App\Models\Locale_toure;
use App\Models\Toure_image;

use App\Services\ToureService;
use App\Services\URLTitleService;
use App\Services\ImageControllService;

use Validator;

class ToureController extends Controller
{
    function get_tours(Request $request){
        $global_tours = Toure::where("published", "=", 1)->get();
        return $tours = ToureService::get_locale_tours_use_locale($global_tours, $request->lang);

        // return $tours;
    }

    function get_all_tours(){
        return Toure::latest('id')->get();
    }

    function get_tour(Request $request){
        $global_tour_count = Toure::where('url_title',strip_tags($request->url_title))->count();

        if($global_tour_count > 0){
            $global_tour = Toure::where('url_title',strip_tags($request->url_title))->first();
            return $tour = ToureService::get_locale_toure_in_page($global_tour, $request->lang);    
        }
        return abort(404);
    }

    function add_toure(Request $request){
        $validation_issets = [];

        $data = json_decode($request->data, true);

        $ka_validate = $this->local_toure_validate($data['ka_data']);
        if ($ka_validate != null) {
            $validation_issets['ka_info_validation'] = $ka_validate;
        }
        else{
            $validation_issets['ka_info_validation'] = false;
        }

        $us_validate = $this->local_toure_validate($data['us_data']);
        if ($us_validate != null) {
            $validation_issets['us_info_validation'] = $us_validate;
        }
        else{
            $validation_issets['us_info_validation'] = false;
        }

        $ru_validate = $this->local_toure_validate($data['ru_data']);
        if ($ru_validate != null) {
            $validation_issets['ru_info_validation'] = $ru_validate;
        }
        else{
            $validation_issets['ru_info_validation'] = false;
        }

        $global_validate = $this->global_toure_adding_validate($data['global_data']);
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
            $saiving_issets['ka_info_status'] = $this->add_locale_toure($data['ka_data'], 'ka');
            $saiving_issets['ru_info_status'] = $this->add_locale_toure($data['ru_data'], 'ru');
            $saiving_issets['us_info_status'] = $this->add_locale_toure($data['us_data'], 'us');

            if (
                $saiving_issets['ka_info_status'] != 'Error' &&
                $saiving_issets['ru_info_status'] != 'Error' &&
                $saiving_issets['us_info_status'] != 'Error'
            ) {
                $toure_toure_id = $this->add_global_toure(
                    $data['global_data'],

                    $saiving_issets['ka_info_status'],
                    $saiving_issets['ru_info_status'],
                    $saiving_issets['us_info_status']                    
                );
                
                if($request->hasFile('toure_images')){
                    $this->add_toure_images($request['toure_images'], $toure_toure_id);
                }
            }
        }
        else{            
            return response()->json([
                'validation' => $validation_issets
            ], 422);
        }
    }

    private function add_locale_toure($data, $locale){
        // $toure = new Locale_toure;
        // $toure->create($data->all());


        $toure = new Locale_toure;
        
        $toure['title']=$data["title"];
        $toure['locale']=$locale;
        $toure['short_description']=$data["short_description"];
        $toure['text']=$data["text"];

        $saved = $toure->save();

        if(!$saved){
            App::abort(500, 'Error');
        }
        else{
            return $toure->id;
        }
    }

    private function add_global_toure($global_data, $ka_info_id, $ru_info_id, $us_info_id){
        // dd($us_data);
        
        $url_title = URLTitleService::get_url_title($global_data["us_title_for_url_title"]); // make url_title from us_title value

        $toure = new Toure;
        
        $toure['url_title'] = $url_title;

        $toure['category_id']=$global_data["category_id"];
        $toure['published']=$global_data["published"];

        $toure['location']=$global_data["location"];
        $toure['duration']=$global_data["duration"];
        $toure['min_price']=$global_data["min_price"];

        $local_us = $us_info_id;
        $local_ka = $ka_info_id;
        $local_ru = $ru_info_id;

        $toure['us_toure_id']=$local_us;
        $toure['ka_toure_id']=$local_ka;
        $toure['ru_toure_id']=$local_ru;
        
        $toure -> save();

        return $toure->id;
    }

    private function add_toure_images($images, $toure_id) {
        foreach ($images as $image) {
            $file_new_name = ImageControllService::upload_loop_image('images/toure_img/', $image, 1);

            if(file_exists(public_path('images/toure_img/') . '/' . $file_new_name)){
                $add_toure_image = new Toure_image;
        
                $add_toure_image['image'] = $file_new_name;
                $add_toure_image['toure_id'] = $toure_id;
        
                $saiving = $add_toure_image -> save();

                if($saiving){
                    echo 'Mount route Upload socsesful';
                }
            }
            else{
                // return 'Upload error';
                echo 'Mount route Upload error';
            }
        }
    }

    public function edit_toure(Request $request)
    {
        $validation_issets = [];

        $data = json_decode($request->data, true );

        $ka_validate = $this->local_toure_validate($data['ka_data']);
        if ($ka_validate != null) {
            $validation_issets['ka_info_validation'] = $ka_validate;
        }
        else{
            $validation_issets['ka_info_validation'] = false;
        }

        $us_validate = $this->local_toure_validate($data['us_data']);
        if ($us_validate != null) {
            $validation_issets['us_info_validation'] = $us_validate;
        }
        else{
            $validation_issets['us_info_validation'] = false;
        }

        $ru_validate = $this->local_toure_validate($data['ru_data']);
        if ($ru_validate != null) {
            $validation_issets['ru_info_validation'] = $ru_validate;
        }
        else{
            $validation_issets['ru_info_validation'] = false;
        }

        $global_validate = $this->global_toure_editing_validate($data['global_data']);
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
            $locale_toure_values = $this->edit_global_toure(
                $data['global_data'],  
                $request->toure_id                
            );
            if (
                $locale_toure_values != 'Error'
            ) {
                $saiving_issets['ka_info_status'] = $this->edit_locale_toure($data['ka_data'], $locale_toure_values['ka_id']);
                $saiving_issets['ru_info_status'] = $this->edit_locale_toure($data['ru_data'], $locale_toure_values['ru_id']);
                $saiving_issets['us_info_status'] = $this->edit_locale_toure($data['us_data'], $locale_toure_values['us_id']);
                
                if($request->hasFile('toure_new_images')){
                    $this->add_toure_images($request['toure_new_images'], $locale_toure_values['global_id']);
                }
            }
        }
        else{            
            return response()->json([
                'validation' => $validation_issets
            ], 422);
        }
    }

    public function edit_global_toure($global_data, $id)
    {
        $editing_local_toure = Toure::where('id', '=', $id)->first();

        if($global_data['change_url_title']){
            $url_title = URLTitleService::get_url_title($global_data["us_title_for_url_title"]); // make url_title from us_title value
            $editing_local_toure['url_title'] = $url_title;
        }

        $editing_local_toure['category_id']=$global_data["category_id"];
        $editing_local_toure['published']=$global_data["published"];

        $editing_local_toure['location']=$global_data["location"];
        $editing_local_toure['duration']=$global_data["duration"];
        $editing_local_toure['min_price']=$global_data["min_price"];
        
        $saved = $editing_local_toure -> save();
        
        if(!$saved){
            App::abort(500, 'Error');
        }
        else{
            $locale_tabs = [
                'us_id' => $editing_local_toure->us_toure_id,
                'ka_id' => $editing_local_toure->ka_toure_id,
                'ru_id' => $editing_local_toure->ru_toure_id,
                'global_id' => $editing_local_toure->id
            ];
            return $locale_tabs;
        }
    }

    public function edit_locale_toure($data, $id)
    {
        $editing_locale_toure = Locale_toure::where('id', '=', $id)->first();
        
        $editing_locale_toure['title']=$data["title"];
        $editing_locale_toure['short_description']=$data["short_description"];
        $editing_locale_toure['text']=$data["text"];

        $saved = $editing_locale_toure->save();

        if(!$saved){
            App::abort(500, 'Error');
        }
        else{
            return $editing_locale_toure->id;
        }
    }


    public function get_editing_toure(Request $request)
    {
        $toure = Toure::where('id', '=', $request->toure_id)->first();
        
        $data = [
            'global_toure' => $toure,
            'us_toure' => $toure->us_toure,
            'ka_toure' => $toure->ka_toure,
            'ru_toure' => $toure->ru_toure,

            'toure_images' => $toure->toure_images,
        ];

        return $data;
    }

    function get_toure_images(Request $request) {
        $toure = Toure::where('id', '=', $request->toure_id)->first();
        
        if($toure->toure_images->count() > 0){
            return $toure->toure_images;
        }
        return [];
    }

    function del_toure(Request $request){
        $global_id = $request->toure_id;

        $global_toure = Toure::where('id',strip_tags($global_id))->first();
        $us_toure = Locale_toure::where('id',strip_tags($global_toure->us_toure_id))->first();
        $ru_toure = Locale_toure::where('id',strip_tags($global_toure->ru_toure_id))->first();
        $ka_toure = Locale_toure::where('id',strip_tags($global_toure->ka_toure_id))->first();
        
        // delete toure file
        $toure_images_count = Toure_image::where('toure_id',strip_tags($global_id))->count();
        
        if ($toure_images_count > 0) {
            $toure_images = Toure_image::where('toure_id',strip_tags($global_id))->get();
            foreach ($toure_images as $toure_image) {
                imageControllService::image_delete('images/toure_img/', $toure_image, 'image');
                $toure_image ->delete();
            }
        }

        // delete toure from db
        $global_toure ->delete();
        $us_toure ->delete();
        $ru_toure ->delete();
        $ka_toure ->delete();
    }

    function del_toure_image(Request $request) {
        $image = Toure_image::where('id', '=', $request->image_id)->first();
        if($image){
            ImageControllService::image_delete('images/toure_img/', $image, 'image');
            $image->delete();
        }
    }

    public function global_toure_editing_validate($global_data)
    {
        $validator = Validator::make($global_data, [
            'published' => 'required',
            'category_id' => 'required',
        ]);
        if ($validator->fails()) {
            return $validator->messages();
        }
    }

    public function global_toure_adding_validate($global_data)
    {
        $validator = Validator::make($global_data, [
            'published' => 'required',
            'category_id' => 'required',
            'us_title_for_url_title' => 'required|unique:locale_tours,title',
        ]);
        if ($validator->fails()) {
            return $validator->messages();
        }
    }

    private function local_toure_validate($data)
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

    private function image_validate($request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'nullable | image | mimes:jpeg,png,jpg,gif,svg | max:2048',
        ]);
        if ($validator->fails()) {
            return $validator->messages();
        }
    }

}
