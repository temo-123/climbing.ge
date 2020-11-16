<?php

namespace App\Services;

use File;
use Storage;
use Carbon\Carbon;

class ImageEditService
{
    public static function image_upload($image_dir, $file_upload_name, $request)
    {
        /*
        *
        * function get 3 parameters
        *
        * $image_dir            - image wai in derectory from '/public/' derectory
        * $file_upload_name     - image value name in DB
        * $request              - HTTP request
        *
        */

        if ($request->hasFile($file_upload_name)) {
                
            $file = $request -> file($file_upload_name);

            $input[$file_upload_name] = $file -> getClientOriginalName();

            // rename file
            $pieces = explode( '.', $input[$file_upload_name] );
            $fruit = array_pop($pieces);
            $comma_separated = implode(",", $pieces);
            $image_name = $comma_separated.'_('.date('Y-m-d-H-m-s').').'.$fruit;

            // move fili in derectory
            $file -> move(public_path().$image_dir, $image_name);

            // updete file name for add in DB
            return $image_name;
        }
    }
    public static function image_update($image_dir, $model, $input, $file_update_name, $request)
    {
        /*
        *
        * function get 5 parameters
        *
        * $image_dir            - image wai in derectory from '/public/' derectory
        * $model                - updated model in copntroller
        * $input                - input updated token ($input = $request -> except('_token');)
        * $file_upload_name     - image value name in DB
        * $request              - HTTP request
        *
        */

        if ($request->hasFile($file_update_name)) {

            $file = $request -> file($file_update_name);

            //get original file
            $input[$file_update_name] = $file -> getClientOriginalName();

            //rename file
            $pieces = explode( '.', $input[$file_update_name] );
            $fruit = array_pop($pieces);
            $comma_separated = implode(",", $pieces);
            $image_name = $comma_separated.'_('.date('Y-m-d-H-m-s').').'.$fruit;

            // move fili in derectory
            $file -> move(public_path().$image_dir, $image_name);

            // delite old file if it's requird
            if ($request[$file_update_name] != NULL) {
                $fileName = $model[$file_update_name];
                $destinationPath = $image_dir;
                // dd($destinationPath.'/'.$fileName);
                // File::delete($destinationPath.'/'.$fileName);
                Storage::delete($destinationPath.'/'.$fileName);
                
            }

            // updete file name in array for add in DB
            return $image_name; 
        }
        else {
            return $input['old_'. $file_update_name];
        }
    }

    public static function image_delite($image_dir, $model, $file_name)
    {
        /*
        *
        * function get 3 parameters
        *
        * $image_dir            - image wai in derectory from '/public/' derectory
        * $model                - updated model in copntroller
        * $file_name     - image value name in DB
        *
        */

        // delete product file
        $fileName = $model[$file_name];
        $destinationPath = $image_dir;
        // dd($destinationPath.'/'.$fileName);
        File::delete($destinationPath.'/'.$fileName);
    }
}