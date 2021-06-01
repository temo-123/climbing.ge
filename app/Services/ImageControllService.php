<?php

namespace App\Services;

use Storage;
use Carbon\Carbon;
use App\Models\Article;

use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManagerStatic as Image;

class imageControllService
{
    public static function image_upload($image_dir, $request, $form_value_id, $resize)
    {
        /*
        *
        * function get 3 parameters
        *
        * $image_dir            - image wai in derectory from '/public/' derectory
        * $request              - HTTP request
        * $form_value_id        - image value name in your form
        *
        */

        // https://therichpost.com/vue-laravel-image-upload/

        if ($request->hasFile($form_value_id)){   

            // rename file->;
            $extension = $request->file($form_value_id)->getClientOriginalExtension();
            $file_new_name = ImageControllService::rename_image($request, $form_value_id);
            $file_new_name = $file_new_name.'.'.$extension;
            // dd($file_new_name);

            // push image in folder
            $file = $request->file($form_value_id);
            $file -> move(public_path($image_dir.'origin_img/'), $file_new_name);

            // create demo image
            ImageControllService::create_demo_image($image_dir, $file_new_name, $resize);

            // return new filie name
            return $file_new_name;
        } 
    }

    public static function image_update($image_dir, $model, $request, $form_value_id, $resize)
    {
        /*
        *
        * function get 5 parameters
        *
        * $image_dir            - image wai in derectory from '/public/' derectory
        * $model                - updated model in copntroller
        * $request              - HTTP request
        *
        */

        if ($request->hasFile($form_value_id)){ 
            // delete old image
            ImageControllService::image_delete($image_dir, $model);
            ImageControllService::image_delete($image_dir, $model);

            // rename file
            $extension = $request->file($form_value_id)->getClientOriginalExtension();
            $file_new_name = ImageControllService::rename_image($request, $form_value_id);
            $file_new_name = $file_new_name.'.'.$extension;
            $file      = $request->file($form_value_id);

            // push image in folder
            $file->move(public_path($image_dir.'origin_img/'), $file_new_name);

            // create demo image
            ImageControllService::create_demo_image($image_dir, $file_new_name, $resize);

            return $file_new_name;
        }
    }

    public static function image_delete($image_dir, $model)
    {
        /*
        *
        * function get 3 parameters
        *
        * $image_dir            - image wai in derectory from '/public/' derectory
        * $model                - updated model in copntroller
        *
        */
        
        // delete product file
        $fileName = $model->image;
        $file = public_path($image_dir.$fileName);
        $demo_file = public_path($image_dir.'origin_img/'.$fileName);
        
        if(file_exists($file) && file_exists($demo_file)){
            File::delete($file);
            File::delete($demo_file);
        }else{
            echo ('<p> File does not exists.</p>');
            echo ('<p>'.$file.'</p>');
        }
    }

    public static function rename_image($request, $form_value_id)
    {
        // rename file
        $file      = $request->file($form_value_id);
        $filename  = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $pieces = explode( '.', $filename );
        // $file_new_name = $pieces[0].'_('.date('Y-m-d-H-m-s').').'.$extension;
        $file_new_name = date('Y-m-d-H-m-s');

        return $file_new_name;
    }

    public static function create_demo_image($image_dir, $file_new_name, $resize)
    {
        // open an image file
        $resize_filename = public_path($image_dir.'origin_img/'.$file_new_name);
        $demo_img = Image::make($resize_filename);

        // now you are able to resize the instance
        if($resize == 1){
            $demo_img->resize(1024, 576);
        }

        // finally we save the image as a new file
        $demo_img->save(public_path($image_dir.$file_new_name));
    }
}