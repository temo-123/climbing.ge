<?php

namespace App\Services\Abstract;

use Illuminate\Http\Request;

use Storage;
use Carbon\Carbon;
use App\Models\Article;

use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManagerStatic as Image;

class ImageControllService
{
    public static function image_upload($image_dir, $request, $form_value_id, $resize = 0, $save_origin_image = 1)
    {
        /*
        *
        * This function upload 1 file
        *
        * Function get 5 parameters
        *
        * $image_dir            - image wai in derectory from '/public/' derectory
        * $request              - HTTP request
        * $form_value_id        - image value name in your form
        * $resize               - Image resize action (defolt it null)
        * $save_origin_image    - Save orifinal image in ./origin_img/ folder (defolt it true)
        *
        */

        // https://therichpost.com/vue-laravel-image-upload/

        // if ($request->hasFile($form_value_id)){   

            // rename file
            $extension = $request->file($form_value_id)->getClientOriginalExtension();
            $file_new_name = ImageControllService::rename_image($request, $form_value_id);
            $file_new_name = $file_new_name.'.'.$extension;
            // dd($file_new_name);

            // push image in folder
            if($save_origin_image){
                $file = $request->file($form_value_id);
                $file -> move(public_path($image_dir.'origin_img/'), $file_new_name);
            }

            // create demo image
            ImageControllService::create_demo_image($image_dir, $file_new_name, $resize);

            // return new filie name
            return $file_new_name;
        // } 
    }

    public static function upload_loop_image($image_dir, $form_value_id, $resize = 0)
    {
        /*
        *
        * this function uploading 1 file in files loop
        *
        * Function get 3 parameters
        *
        * $image_dir            - image wai in derectory from '/public/' derectory
        * $form_value_id        - image value name in your form
        * $resize               - Image resize action (defolt it null)
        *
        */

        $extension = $form_value_id->getClientOriginalExtension();
        $filename  = $form_value_id->getClientOriginalName();

        // $file_new_name = date('Y-m-d-H-m-s-U').'{'.rand(1,1000000).'}'; 
        $file_new_name = ImageControllService::create_image_name(); 

        $file_new_name = $file_new_name.'.'.$extension;

        if (!file_exists(public_path($image_dir))) {
            mkdir(public_path($image_dir));
        }

        $path = public_path($image_dir . $file_new_name);

        if($resize == 1){
            Image::make($form_value_id)->resize(1024, 576)->save( $path );
        }
        else{
            Image::make($form_value_id)->save( $path );
        }

        if(!$form_value_id->isValid()){
            App::abort(500, 'Image uploading error');
        }
        else{
            return $file_new_name;
        }
    }

    public static function image_update($image_dir, $editing_model_value, $request, $form_value_id, $db_value, $resize = 0)
    {
        /*
        *
        * This function deliting old image and upload new
        *
        * Function get 6 parameters
        *
        * $image_dir            - image wai in derectory from '/public/' derectory
        * $editing_model_value  - updated model in copntroller
        * $request              - HTTP request
        * $form_value_id        - image value name in your form
        * $db_value             - Database value name
        * $resize               - Image resize action (defolt it null)
        *
        */
        
        if ($request->hasFile($form_value_id)){ 
            // delete old image
            ImageControllService::image_delete($image_dir, $editing_model_value, $db_value);
            // ImageControllService::image_delete($image_dir, $editing_model_value, $db_value);

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

    public static function image_delete($image_dir, $editing_model_value, $db_value)
    {
        /*
        *
        * This function delite image. it chech ./[dir] and ./[dir]/origin_img/ folder and delite file from this folders
        * If one of them is not exist it skip it
        *
        * function get 3 parameters
        *
        * $image_dir            - image derectory from '/public/'
        * $editing_model_value  - updated model in copntroller
        * $db_value             - Database value name
        *
        */
        
        // delete product file
        $fileName = $editing_model_value->$db_value;
        $file = public_path($image_dir.$fileName);
        $original_file = public_path($image_dir.'origin_img/'.$fileName);
        
        if(file_exists($file) && file_exists($original_file)){
            File::delete($file);
            File::delete($original_file);
        } 
        if(file_exists($file)){
            File::delete($file);
        }
        if(file_exists($original_file)){
            File::delete($original_file);
        }
        if(!file_exists($file)){
            echo ('<p> Demo file does not exists.</p>');
            echo ('<p>'.$file.'</p>');
        }
        if(!file_exists($original_file)){
            echo ('<p> Origin file does not exists.</p>');
            echo ('<p>'.$original_file.'</p>');
        }
        
    }

    public static function rename_image($request, $form_value_id)
    {
        // rename file
        $file      = $request->file($form_value_id);
        $filename  = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $pieces = explode( '.', $filename );
        // $file_new_name = date('Y-m-d-H-m-s');

        $file_new_name = ImageControllService::create_image_name(); 

        return $file_new_name;
    }

    public static function create_demo_image($image_dir, $file_new_name, $resize, $size = 's')
    {
        // dd($image_dir, hasFile($file_new_name), $resize, $size = 's');
        // open an image file
        $resize_filename = public_path($image_dir.'origin_img/'.$file_new_name);
        $demo_img = Image::make($resize_filename);
        dd($demo_img);
        // now you are able to resize the instance
        if($resize == 1){
            if($size == 'l' || $size == 'L'){
                $demo_img->resize(1920, 1080);
            }
            if($size == 'm' || $size == 'M'){
                $demo_img->resize(1280, 720);
            }
            if($size == 's' || $size == 'S'){
                $demo_img->resize(1024, 576);
            }
        }

        ImageControllService::convertImageToWebp($demo_img, $resize_filename);

        // finally we save the image as a new file
        // $demo_img->save(public_path($image_dir.$file_new_name));
    }

    private static function create_image_name()
    {
        return date('Y-m-d-H-m-s-U').'{'.rand(1,1000000).'}'; 
    }

    /**
     * @param string $inputFile: relative or absolute path
     * @param string $outputFile: relative or absolute path
     * @param int $quality of output: 0 is worst, 100 is best
     * @return void
     * 
     * exemple -> convertImageToWebp('/home/paul/image.gif', 'image.webp', 90);
     */
    private static function convertImageToWebp(string $inputFile, string $outputFile, int $quality = 100): void
    {
        $fileType = exif_imagetype($inputFile);

        switch ($fileType) {
            case IMAGETYPE_GIF:
                $image = imagecreatefromgif($inputFile);
                imagepalettetotruecolor($image);
                imagealphablending($image, true);
                imagesavealpha($image, true);
                break;
            case IMAGETYPE_JPEG:
                $image = imagecreatefromjpeg($inputFile);
                break;
            case IMAGETYPE_PNG:
                $image = imagecreatefrompng($inputFile);
                imagepalettetotruecolor($image);
                imagealphablending($image, true);
                imagesavealpha($image, true);
                break;
            case IMAGETYPE_WEBP:
                rename($inputFile, $outputFile);
                return;
            default:
                return;
        }

        imagewebp($image, $outputFile, $quality);

        imagedestroy($image);
    }
}