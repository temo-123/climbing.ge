<?php

namespace App\Services\Abstract;

use Illuminate\Http\Request;

use Storage;
use Carbon\Carbon;
use App\Models\Article;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManagerStatic as Image;

class ImageControllService
{
    /**
     * @param string $image_dir: image derectory from '/public/'
     * @param int $request:  HTTP request
     * @param int $form_value_id:  image value name in your form
     * @param int $resize:  Image resize action (defolt it null). 0 = no resize, 1 = resize + crop to 1920x1080, 2 = resize to fit inside 1920x1080 keeping original aspect ratio (no crop), 3 = resize + crop to a 1080x1080 square (avatars)
     *
     * This function deliting old image and upload new
     */
    public static function image_upload($image_dir, $request, $form_value_id, $resize = 1)
    {
        // https://therichpost.com/vue-laravel-image-upload/

        [$file_temp_path, $isOwnTempFile] = ImageControllService::resolveImageSource($request, $form_value_id);
        if (!$file_temp_path) {
            return null;
        }

        $new_name = ImageControllService::generate_image_name();
        $file_new_name = $new_name . '.webp';

        $converted = ImageControllService::convertImageToWebp($file_temp_path, public_path($image_dir . $file_new_name), 80, $resize);

        if ($isOwnTempFile) {
            @unlink($file_temp_path);
        }

        return $converted ? $file_new_name : null;
    }

    /**
     * Resolves either a real multipart upload OR a base64 fallback field into a
     * temp file path. The mobile app now sends new images as base64 inside a
     * plain JSON body (e.g. "photo_base64" / "photo_ext") instead of multipart,
     * because some networks and on-device software (VPNs, firewalls, "security"
     * apps that proxy all app traffic to inspect it) reject or corrupt
     * multipart/form-data bodies specifically while leaving plain JSON/
     * urlencoded POSTs untouched. Device-confirmed case: an upload failed on
     * every attempt with the connection-level error
     * "multipart != application/x-www-form-urlencoded" — some interceptor
     * rejecting the Content-Type outright — while an identical plain-JSON
     * request from the same device always succeeded.
     *
     * @return array{0: ?string, 1: bool} [$tempPath, $isOwnTempFile] — the
     *         second element tells the caller whether it's safe/necessary to
     *         unlink $tempPath afterwards (true for our own base64-decoded
     *         file; false for Laravel's framework-managed upload tmp path).
     */
    private static function resolveImageSource($request, $form_value_id)
    {
        if ($request->hasFile($form_value_id)) {
            return [$request->file($form_value_id)->getPathName(), false];
        }

        $base64 = $request->input($form_value_id . '_base64');
        if (!$base64) {
            return [null, false];
        }

        $decoded = base64_decode($base64, true);
        if ($decoded === false) {
            Log::warning('ImageControllService: invalid base64 for ' . $form_value_id . '_base64');
            return [null, false];
        }

        $ext = preg_replace('/[^a-zA-Z0-9]/', '', $request->input($form_value_id . '_ext', 'jpg')) ?: 'jpg';
        $tempPath = tempnam(sys_get_temp_dir(), 'upl_') . '.' . $ext;
        file_put_contents($tempPath, $decoded);

        return [$tempPath, true];
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
        if ($request->hasFile($form_value_id) || $request->filled($form_value_id . '_base64')){
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

        $converted = ImageControllService::convertImageToWebp($file_temp_path, public_path($image_dir . $file_new_name), 80, $resize);

        return $converted ? $file_new_name : null;
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
    }

    private static function generate_image_name()
    {
        return date('Y-m-d-H-m-s-U').'{'.rand(1,1000000).'}'; 
    }

    /**
     * @param string $inputFile: relative or absolute path
     * @param string $outputFile: relative or absolute path
     * @param int $quality of output: 0 is worst, 100 is best
     * @return bool true if the image was decoded and written, false if the type was unrecognized or decoding failed
     *
     * exemple -> convertImageToWebp('/home/paul/image.gif', 'image.webp', 90);
     */
    private static function convertImageToWebp(string $inputFile, string $outputFile, int $quality = 80, int $resize): bool
    {
        $dir = dirname($outputFile);
        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }

        $fileType = exif_imagetype($inputFile);

        switch ($fileType) {
            case IMAGETYPE_GIF:
                $image = imagecreatefromgif($inputFile);
                if ($image !== false) {
                    imagepalettetotruecolor($image);
                    imagealphablending($image, true);
                    imagesavealpha($image, true);
                }
                break;
            case IMAGETYPE_JPEG:
                $image = imagecreatefromjpeg($inputFile);
                break;
            case IMAGETYPE_PNG:
                $image = imagecreatefrompng($inputFile);
                if ($image !== false) {
                    imagepalettetotruecolor($image);
                    imagealphablending($image, true);
                    imagesavealpha($image, true);
                }
                break;
            case IMAGETYPE_WEBP:
                $image = imagecreatefromwebp($inputFile);
                if ($image !== false) {
                    imagesavealpha($image, true);
                }
                break;
            default:
                Log::warning('ImageControllService: unrecognized image type, conversion skipped', [
                    'inputFile' => $inputFile,
                    'exif_imagetype' => $fileType,
                ]);
                return false;
        }

        if ($image === false) {
            Log::warning('ImageControllService: GD failed to decode image', [
                'inputFile' => $inputFile,
                'exif_imagetype' => $fileType,
            ]);
            return false;
        }

        if ($resize == 1) {
            ImageControllService::resize_image($image, $outputFile, $quality, 1920, 1080, true);
        } elseif ($resize == 2) {
            ImageControllService::resize_image($image, $outputFile, $quality, 1920, 1080, false);
        } elseif ($resize == 3) {
            ImageControllService::resize_image($image, $outputFile, $quality, 1080, 1080, true);
        } else {
            imagewebp($image, $outputFile, $quality);
        }

        imagedestroy($image);

        return true;
    }


    /**
     * @param resource $image: GD image resource
     * @param string $destination_file: relative or absolute path
     * @param int $quality of output: 0 is worst, 100 is best
     * @return void
     *
     * exemple -> resize_image($image,'dst_image.webp',75);
     * https://honarsystems.com/php-resize-image/
     */
    private static function resize_image($image, $destination_file, $quality = 80, $width = 1920, $height = 1080, $crop = TRUE)
    {
        $current_width = imagesx($image);
        $current_height = imagesy($image);
        $rate = $current_width / $current_height;

        $src_x = 0;
        $src_y = 0;
        $src_width = $current_width;
        $src_height = $current_height;

        if ($crop) {
            // Crop a centered rectangle matching the target aspect ratio, then scale it to fit.
            if ($rate > $width / $height) {
                $src_width = ceil($current_height * ($width / $height));
                $src_x = (int) (($current_width - $src_width) / 2);
            } else {
                $src_height = ceil($current_width * ($height / $width));
                $src_y = (int) (($current_height - $src_height) / 2);
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
        $src_file = $image;
        $dst_file = imagecreatetruecolor($newwidth, $newheight);
        imagealphablending($dst_file, false);
        imagesavealpha($dst_file, true);
        imagecopyresampled($dst_file, $src_file, 0, 0, $src_x, $src_y, $newwidth, $newheight, $src_width, $src_height);

        imagewebp($dst_file, $destination_file, $quality);
        imagedestroy($dst_file);
    }
}