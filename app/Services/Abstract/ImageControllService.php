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
    /**
     * @param string $image_dir: image derectory from '/public/'
     * @param int $request:  HTTP request
     * @param int $form_value_id:  image value name in your form
     * @param int $resize:  Image resize action (defolt it null)
     * @param int $save_origin_image:  Save orifinal image in ./origin_img/ folder (defolt it true)
     * 
     * This function deliting old image and upload new
     */
    public static function image_upload($image_dir, $request, $form_value_id, $resize = 1)
    {
        // https://therichpost.com/vue-laravel-image-upload/

        if ($request->hasFile($form_value_id)){
            // rename file
            $new_name = ImageControllService::generate_image_name();
            $file_new_name = $new_name . '.webp';

            $file_temp_path = $request->file($form_value_id)->getPathName();

            ImageControllService::convertImageToWebp($file_temp_path, public_path($image_dir . $file_new_name), 80, $resize);
            
            return $file_new_name;
        }
    }

    /**
     * @param string $image_dir: image derectory from '/public/'
     * @param string $editing_model_value: updated model in copntroller
     * @param int $request:  HTTP request
     * @param int $form_value_id:  image value name in your form
     * @param int $db_value:  Database value name
     * @param int $resize:  Image resize action (defolt it null)
     * 
     * This function deliting old image and upload new
     */
    public static function image_update($image_dir, $editing_model_value, $request, $form_value_id, $db_value, $resize = 1)
    {
        if ($request->hasFile($form_value_id)){ 
            // delete old image
            ImageControllService::image_delete($image_dir, $editing_model_value, $db_value);

            // add new image
            return ImageControllService::image_upload($image_dir, $request, $form_value_id, $resize);
        }
    }

    /**
     * @param string $image_dir: image derectory from '/public/'
     * @param int $form_value_id:  image value name in your form
     * @param int $resize:  Image resize action (defolt it null)
     * 
     * this function uploading 1 file in files loop
     */
    public static function upload_loop_image($image_dir, $form_value_id, $resize = 1)
    {
        $new_name = ImageControllService::generate_image_name();
        $file_new_name = $new_name . '.webp';

        $file_temp_path = $form_value_id->getPathName();

        ImageControllService::convertImageToWebp($file_temp_path, public_path($image_dir . $file_new_name), 80, $resize);
        
        return $file_new_name;
    }

    /**
     * @param string $image_dir: image derectory from '/public/'
     * @param string $editing_model_value: updated model in copntroller
     * @param int $db_value:  Database value name
     * 
     * This function delite image. it chech ./[dir] and ./[dir]/origin_img/ folder and delite file from this folders
     * If one of them is not exist it skip it
     */
    public static function image_delete($image_dir, $editing_model_value, $db_value)
    {
        // delete product file
        $fileName = $editing_model_value->$db_value;
        $file = public_path($image_dir . pathinfo($fileName, PATHINFO_FILENAME) . '.webp');
        
        $original_file = public_path($image_dir . 'origin_img/' . $fileName);
        
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

    private static function generate_image_name()
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
    private static function convertImageToWebp(string $inputFile, string $outputFile, int $quality = 80, int $resize): void
    {
        // Ensure the output directory exists
        $outputDir = dirname($outputFile);
        if (!file_exists($outputDir)) {
            mkdir($outputDir, 0755, true);
        }

        $inputFile_2 = $inputFile;
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

        if ($resize == 1) {
            ImageControllService::resize_webp_image($inputFile_2, $outputFile, $quality);
        } else {
            if (!imagewebp($image, $outputFile, $quality)) {
                // Log error if imagewebp fails
                error_log("Failed to save WebP image: $outputFile");
            }
        }

        imagedestroy($image);
    }


    /**
     * @param string $inputFile: relative or absolute path
     * @param string $outputFile: relative or absolute path
     * @param int $quality of output: 0 is worst, 100 is best
     * @return void
     * 
     * exemple -> resize_webp_image('image.webp','dst_image.webp','100','100',75,false);
     * https://honarsystems.com/php-resize-image/
     * https://www.google.com/search?q=resizing+webp+image+in+php&sca_esv=a03deeebd4ceaea7&sxsrf=AE3TifNVHDNensST_nOYoEh4wyVAdcbVZw%3A1748585712813&ei=8Ew5aOO0MYGGxc8P5MyugQM&oq=+resizing+webp+image+php&gs_lp=Egxnd3Mtd2l6LXNlcnAiGCByZXNpemluZyB3ZWJwIGltYWdlIHBocCoCCAAyCBAhGKABGMMEMggQIRigARjDBEjjgwFQkAlYjm9wAngBkAEAmAGIAqABww2qAQYwLjEwLjG4AQHIAQD4AQH4AQKYAg2gAr8OwgIKEAAYsAMY1gQYR8ICBhAAGAcYHsICCxAAGIAEGIYDGIoFwgIFEAAY7wXCAgoQIRigARjDBBgKwgIIEAAYgAQYogSYAwCIBgGQBgiSBwYyLjEwLjGgB_8ZsgcGMC4xMC4xuAehDsIHBzAuMS42LjbIB1k&sclient=gws-wiz-serp
     */
    private static function resize_webp_image($source_file, $destination_file, $width = 1920, $height = 1080, $quality = 80, $crop = FALSE)
        {
            // Ensure the destination directory exists
            $destination_dir = dirname($destination_file);
            if (!file_exists($destination_dir)) {
                mkdir($destination_dir, 0755, true);
            }

            // dd('resize_webp_image function is deprecated, use resize_image_webp instead');
            list($current_width, $current_height) = getimagesize($source_file);
            $rate = $current_width / $current_height;
            if ($crop) {
                if ($current_width > $current_height) {
                    $current_width = ceil($current_width-($current_width*abs($rate-$width/$height)));
                } else {
                    $current_height = ceil($current_height-($current_height*abs($rate-$width/$height)));
                }
                $newwidth = $width;
                $newheight = $height;
            } else {
                if ($width/$height > $rate) {
                    $newwidth = $height*$rate;
                    $newheight = $height;
                } else {
                    $newheight = $width/$rate;
                    $newwidth = $width;
                }
            }
            $src_file = @imagecreatefromwebp($source_file);
            if (!$src_file) {
                // If unable to create WebP image (e.g., missing GD WebP support), skip resizing
                error_log("Failed to create WebP image from source: $source_file");
                return;
            }
            $dst_file = imagecreatetruecolor($newwidth, $newheight);
            imagecopyresampled($dst_file, $src_file, 0, 0, 0, 0, $newwidth, $newheight, $current_width, $current_height);

            if (!imagewebp($dst_file, $destination_file, $quality)) {
                error_log("Failed to save resized WebP image: $destination_file");
            }
            imagedestroy($src_file);
            imagedestroy($dst_file);
        }

    // function resize_image_webp($source_file,$destination_file, $width, $height, $quality, $crop=FALSE) {
    //     list($current_width, $current_height) = getimagesize($source_file);
    //     $rate = $current_width / $current_height;
    //     if ($crop) {
    //         if ($current_width > $current_height) {
    //             $current_width = ceil($current_width-($current_width*abs($rate-$width/$height)));
    //         } else {
    //             $current_height = ceil($current_height-($current_height*abs($rate-$width/$height)));
    //         }
    //         $newwidth = $width;
    //         $newheight = $height;
    //     } else {
    //         if ($width/$height > $rate) {
    //             $newwidth = $height*$rate;
    //             $newheight = $height;
    //         } else {
    //             $newheight = $width/$rate;
    //             $newwidth = $width;
    //         }
    //     }
    //     $src_file = imagecreatefromwebp($source_file);
    //     $dst_file = imagecreatetruecolor($newwidth, $newheight);
    //     imagecopyresampled($dst_file, $src_file, 0, 0, 0, 0, $newwidth, $newheight, $current_width, $current_height);

    //     imagewebp($dst_file, $destination_file, $quality);
    // }
}