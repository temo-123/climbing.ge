<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Storage;
use Carbon\Carbon;

class imageControllService
{
    public static function image_upload($image_dir, $db_row, $request)
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

        // https://therichpost.com/vue-laravel-image-upload/

        if ($request->hasFile('profile_pic')){   
            // rename file
            $file      = $request->file('profile_pic');
            $filename  = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $pieces = explode( '.', $filename );
            $file_new_name = $pieces[0].'_('.date('Y-m-d-H-m-s').').'.$extension;
            
            // push image in folder
            $file->move(public_path('images/'.$image_dir), $file_new_name);

            // save new value in db
            $db_row['image']=$file_new_name;
            // dd($db_row);
            $db_row -> save();

            return response()->json(["message" => "Image Uploaded Succesfully"]);
        } 
        else{
            return response()->json(["message" => "Select image first."]);
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

        if ($request->hasFile('profile_pic')){ 
            // rename file
            $file      = $request->file('profile_pic');
            $filename  = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $pieces = explode( '.', $filename );
            $file_new_name = $pieces[0].'_('.date('Y-m-d-H-m-s').').'.$extension;

            // fined article category
            $global_article = Article::where('id',strip_tags($request->id))->first();;
            $article_category = $global_article->category;

            // push image in folder
            $file->move(public_path('images/'.$article_category.'_img'), $file_new_name);

            // edit article image
            $article = Article::where('id',strip_tags($request->id))->first();
            $article['image'] = $file_new_name;
            $article -> save();
        }
        else {
            // $article = Article::where('id',strip_tags($request->id))->first();

            // $global_article->published = $article->image;

            // dd($article);
            
            // $global_article->update();
        }
    }

    public static function image_delete($image_dir, $image_in_db, $file_name)
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
        $fileName = $image_in_db->image;
        $file = public_path('images/'.$image_dir.'/'.$fileName);
        
        if(file_exists($file)){
            File::delete($file);
        }else{
            echo $file;
            dd('File does not exists.');
        }

        // delete product from db
        $image_in_db ->delete();
    }
}