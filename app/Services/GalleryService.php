<?php

namespace App\Services;

use Carbon\Carbon;

use App\Services\Abstract\ImageControllService;

abstract class GalleryService extends ImageControllService
{
    public static function add_gallery_images($images, $actyve_id, $gallery_modael, $db_prefix, $db_id_prefix, $dir_path, $parent_model = null)
    {
        if($images != null && count($images) > 0){
            $any_saved = false;

            foreach ($images as $image) {
                $file_new_name = ImageControllService::upload_loop_image($dir_path, $image, 1);

                if(file_exists(public_path($dir_path) . $file_new_name)){
                    $new_option_image = new $gallery_modael;

                    $new_option_image[$db_prefix] = $file_new_name;
                    $new_option_image[$db_id_prefix] = $actyve_id;

                    $saiving = $new_option_image -> save();

                    if($saiving){
                        $any_saved = true;
                        echo 'Upload socsesful \n';
                    }
                }
                else{
                    echo 'Upload error \n';
                }
            }

            // Gallery images live in a separate child table, so saving them doesn't
            // naturally bump the parent's updated_at — touch it explicitly here.
            if($any_saved && $parent_model !== null){
                $parent_model::where('id', $actyve_id)->update(['updated_at' => now()]);
            }
        }
    }
}