<?php

namespace App\Http\Controllers\Api\Admin\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Abstract\ImageControllService;
use App\Services\GalleryService;
use App\Services\ArticlesService;
use App\Services\GeneralInfoService;
use App\Services\MountSystemService;

use App\Models\Guide\Article_image;

use App\Models\Guide\Article;
use App\Models\Guide\Locale_article;
use App\Models\Guide\Comment;

use App\Models\Guide\Spot_rocks_image;
use App\Models\Guide\Mount_route_image;

use App\Models\Guide\Sector;
use App\Models\Guide\Route;
use App\Models\Guide\Mtp;
use App\Models\Guide\Mtp_pitch;

use App\Models\Guide\Mount;
use App\Models\Guide\Locale_mount;

use App\Models\Guide\General_info_article;
use App\Models\Guide\General_info;

use App\Models\Guide\Region;

use App\Models\Guide\Article_mount;
use App\Models\Guide\Article_region;

use App\Models\Guide\Suport_local_bisnes;

use Auth;
use File;
use Validator;

class GuideAdminController extends Controller
{
    // From ArticleController
    public function add_article(Request $request)
    {
        $data = json_decode($request->data, true );
        $global_blocks = json_decode($request->global_blocks, true );

        $image_path = 'images/'.$data['global_article']['category'].'_img/';

        $article_adding = ArticlesService::add_content($data, Article::class, Locale_article::class, '_article', $request, $image_path);

        if (!array_key_exists('validation', $article_adding->original)) {
            GalleryService::add_gallery_images(
                $request->gallery_images,
                $article_adding->original['global_article_id'],
                Article_image::class,
                'image',
                'article_id',
                '/images/article_gallery_img/'
            );

            GeneralInfoService::add_general_info_relatione($global_blocks, $article_adding->original['global_article_id'], 'article');

            $this->description_img($article_adding->original['global_article_id'], $global_blocks, $data, $request);
        }
        else {
            return $article_adding;
        }
    }

    public function edit_article(Request $request)
    {
        $data = json_decode($request->data, true );
        $global_blocks = json_decode($request->global_blocks, true );

        $image_path = 'images/'.$data['global_article']['category'].'_img/';

        $article_editing = ArticlesService::edit_content(Article::class, Locale_article::class, '_article', $request, $image_path);

        if(!array_key_exists('validation', $article_editing->original)){
            GalleryService::add_gallery_images(
                $request->gallery_images,
                $article_editing->original['global_article_id'],
                Article_image::class,
                'image',
                'article_id',
                '/images/article_gallery_img/'
            );

            GeneralInfoService::edit_general_info_relatione($global_blocks, $article_editing->original['global_article_id'], 'article');

            $this->description_img($article_editing->original['global_article_id'], $global_blocks, $data, $request);
        }
        else{
            return $article_editing;
        }
    }

    private function description_img($actyve_id, $global_blocks, $data, $request)
    {
        if($data['global_article']["category"] == 'outdoor'){
            if($request->hasFile('outdoor_area_images')){
                $this->add_outdoor_area_images($request->outdoor_area_images, $actyve_id);
            }
        }
        else if($data['global_article']["category"] == 'mount_route'){
            if($request->hasFile('mount_route_images')){
                $this->add_mount_route_images($request->mount_route_images, $actyve_id);
            }
        }

        if($data['global_article']["category"] == 'outdoor'){
            if(isset($data['global_article']['region_id'])){
                $article_region = Article_region::where('article_id', '=', $actyve_id)->first();
                if($article_region){
                    if($article_region['region_id'] != $data['global_article']['region_id']){
                        $article_region['article_id'] = $actyve_id;
                        $article_region['region_id'] = $data['global_article']['region_id'];
                        $article_region -> save();
                    }
                }
                else{
                    $new_mount = new Article_region;
                    $new_mount['article_id'] = $actyve_id;
                    $new_mount['region_id'] = $data['global_article']['region_id'];
                    $new_mount -> save();
                }
            }
            else if(!isset($data['global_article']['region_id'])){
                $deliting_article_region = Article_region::where('article_id', '=', $actyve_id)->first();
                if($deliting_article_region){
                    $deliting_article_region -> delete();
                }
            }
        }

        if($data['global_article']["category"] == 'mount_route'){
            if(isset($data['global_article']['mount_id'])){
                $article_mount = Article_mount::where('article_id', '=', $actyve_id)->first();
                if($article_mount){
                    if($article_mount['mount_id'] != $data['global_article']['mount_id']){
                        $article_mount['article_id'] = $actyve_id;
                        $article_mount['mount_id'] = $data['global_article']['mount_id'];
                        $article_mount -> save();
                    }
                }
                else{
                    $new_mount = new Article_mount;
                    $new_mount['article_id'] = $actyve_id;
                    $new_mount['mount_id'] = $data['global_article']['mount_id'];
                    $new_mount -> save();
                }
            }
            else if(!isset($data['global_article']['mount_id'])){
                $deliting_article_mount = Article_mount::where('article_id', '=', $actyve_id)->first();
                if($deliting_article_mount){
                    $deliting_article_mount -> delete();
                }
            }
        }
    }

    public function add_outdoor_area_images($images, $article_id)
    {
        foreach ($images as $image) {
            $file_new_name = ImageControllService::upload_loop_image('images/spot_rocks_img/', $image);

            if(file_exists(public_path('images/spot_rocks_img/') . '/' . $file_new_name)){
                $add_outdoor_area_image = new Spot_rocks_image;

                $add_outdoor_area_image['image'] = $file_new_name;
                $add_outdoor_area_image['article_id'] = $article_id;

                $saiving = $add_outdoor_area_image -> save();

                if($saiving){
                    echo 'Spot rocks image Upload socsesful';
                }
            }
            else{
                // return 'Upload error';
                echo 'Spot rocks image Upload error';
            }
        }
    }

    public function add_mount_route_images($images, $article_id)
    {
        foreach ($images as $image) {
            $file_new_name = ImageControllService::upload_loop_image('images/mount_route_description_img/', $image);

            if(file_exists(public_path('images/mount_route_description_img/') . '/' . $file_new_name)){
                $add_mount_image = new Mount_route_image;

                $add_mount_image['image'] = $file_new_name;
                $add_mount_image['article_id'] = $article_id;

                $saiving = $add_mount_image -> save();

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

    public function add_regioin_articles($region_id, $article_id){
        $article = new Article_region;

        $article['region_id'] = $region_id;
        $article['article_id'] = $article_id;

        $article -> save();
    }

    public function add_mount_articles($mount_id, $article_id){
        $article = new Article_mount;

        $article['mount_id'] = $mount_id;
        $article['article_id'] = $article_id;

        $article -> save();
    }

    public function del_article(Request $request)
    {
        $global_id=$request->article_id;

        $global_article = Article::where('id',strip_tags($global_id))->first();

        $image_path = 'images/'.$global_article->category.'_img/';

        if ($global_article->general_info->count() > 0) {
            foreach ($global_article->general_info as $del_info) {
                $deliting_info = General_info_article::
                                                    where('article_id',strip_tags($del_info->pivot->article_id))->
                                                    where('info_id',strip_tags($del_info->pivot->info_id))->
                                                    first();
                $deliting_info -> delete();
            }
        }

        if ($global_article->gallery_images->count() > 0) {
            foreach ($global_article->gallery_images as $del_img) {
                ImageControllService::image_delete('images/article_gallery_img/', $del_img, 'image');
                $del_img -> delete();
            }
        }

        ArticlesService::del_content($global_id, Article::class, Locale_article::class, '_article', 'image', $image_path);

        ImageControllService::image_delete('images/region_sectors_img/', $global_article, 'climbing_area_image');
    }

    // From OutdoorController
    public function add_spot(Request $request)
    {
        $validate = $this->region_validate($request->data);

        if ($validate != null) {
            return response()->json([
                'validation' => $validate
            ], 422);
        }
        else{
            $spot = new Region;

            $spot['us_name'] = $request->data['us_name'];
            // $spot['ru_name'] = $request->data['ru_name'];
            $spot['ka_name'] = $request->data['ka_name'];

            $spot['us_text'] = $request->data['us_text'];
            // $spot['ru_text'] = $request->data['ru_text'];
            $spot['ka_text'] = $request->data['ka_text'];

            $spot['map'] = $request->data['map'];

            $spot -> save();
        }
    }

    public function edit_spot(Request $request)
    {
        $validate = $this->region_validate($request->data);


        if ($validate != null) {
            return response()->json([
                'validation' => $validate
            ], 422);
        }
        else{
            $editing_region = Region::where('id',strip_tags($request->id))->first();

            $editing_region['us_name'] = $request->data['us_name'];
            // $editing_region['ru_name'] = $request->data['ru_name'];
            $editing_region['ka_name'] = $request->data['ka_name'];

            $editing_region['us_text'] = $request->data['us_text'];
            // $editing_region['ru_text'] = $request->data['ru_text'];
            $editing_region['ka_text'] = $request->data['ka_text'];

            $editing_region['map'] = $request->data['map'];

            $editing_region -> save();
        }
    }

    public function del_spot(Request $request)
    {
        $region = Region::where('id',strip_tags($request->id))->first();
        $region -> delete();
    }

    public function region_validate($data)
    {
        $validator = Validator::make($data, [
            'us_name' => 'required',
            // 'ru_name' => 'required',
            'ka_name' => 'required',
        ]);
        if ($validator->fails()) {
            return $validator->messages();
        }
    }

    // From MountController
    public function create_mount_massive(Request $request)
    {
        $validation_issets = [];

        $ka_validate = $this->local_mount_validate($request['data']['ka_data']);
        if ($ka_validate != null) {
            $validation_issets['ka_info_validation'] = $ka_validate;
        }
        else{
            $validation_issets['ka_info_validation'] = false;
        }

        $us_validate = $this->local_mount_validate($request['data']['us_data']);
        if ($us_validate != null) {
            $validation_issets['us_info_validation'] = $us_validate;
        }
        else{
            $validation_issets['us_info_validation'] = false;
        }

        $global_validate = $this->global_mount_validate($request['data']['global_data']);
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

            $saiving_issets['ka_info_status'] = $this->add_locale_mount($request['data']['ka_data'], 'ka');
            // $saiving_issets['ru_info_status'] = $this->add_locale_mount($request['data']['ru_data'], 'ru');
            $saiving_issets['us_info_status'] = $this->add_locale_mount($request['data']['us_data'], 'us');

            if (
                $saiving_issets['ka_info_status'] != 'Error' &&
                // $saiving_issets['ru_info_status'] != 'Error' &&
                $saiving_issets['us_info_status'] != 'Error'
            ) {
                $action_mount_id = $this->add_global_mount(
                    $request['data']['global_data'],

                    $saiving_issets['ka_info_status'],
                    // $saiving_issets['ru_info_status'],
                    $saiving_issets['us_info_status']
                );
            }
        }
        else{
            return response()->json([
                'validation' => $validation_issets
            ], 422);
        }
    }

    public function add_locale_mount($data, $locale)
    {
        $article = new Locale_mount;

        $article['title']=$data["title"];
        $article['short_description']=$data["short_description"];
        $article['text']=$data["text"];
        $article['how_get']=$data["how_get"];
        $article['best_time']=$data["best_time"];

        $article['locale']=$locale;

        $saved = $article->save();

        if(!$saved){
            App::abort(500, 'Error');
        }
        else{
            return $article->id;
        }
    }

    public function add_global_mount($global_data, $ka_info_id, $us_info_id)
    {
        $article = new Mount;

        $article['map']=$global_data["map"];
        $article['weather']=$global_data["weather"];

        $article['name'] = $global_data["name"];

        $article['us_mount_id'] = $us_info_id;
        $article['ka_mount_id'] = $ka_info_id;
        // $article['ru_mount_id'] = $ru_info_id;

        $article -> save();
    }

    public function edit_mount_massive(Request $request)
    {
        $validation_issets = [];

        $ka_validate = $this->local_mount_validate($request['data']['ka_data']);
        if ($ka_validate != null) {
            $validation_issets['ka_info_validation'] = $ka_validate;
        }
        else{
            $validation_issets['ka_info_validation'] = false;
        }

        $us_validate = $this->local_mount_validate($request['data']['us_data']);
        if ($us_validate != null) {
            $validation_issets['us_info_validation'] = $us_validate;
        }
        else{
            $validation_issets['us_info_validation'] = false;
        }

        // $ru_validate = $this->local_mount_validate($request['data']['ru_data']);
        // if ($ru_validate != null) {
        //     $validation_issets['ru_info_validation'] = $ru_validate;
        // }
        // else{
        //     $validation_issets['ru_info_validation'] = false;
        // }

        $global_validate = $this->global_mount_validate($request['data']['global_data']);
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
            $local_mounts = $this->edit_global_mount(
                $request['data']['global_data'],
            );

            if (
                $local_mounts['ka_info_status'] != 'Error'
            ) {
                $saiving_issets['ka_info_status'] = $this->edit_locale_mount($request['data']['ka_data'], $local_mounts['ka_mount_id']);
                // $saiving_issets['ru_info_status'] = $this->edit_locale_mount($request['data']['ru_data'], $local_mounts['ru_mount_id']);
                $saiving_issets['us_info_status'] = $this->edit_locale_mount($request['data']['us_data'], $local_mounts['us_mount_id']);
            }
        }
        else{
            return response()->json([
                'validation' => $validation_issets
            ], 422);
        }
    }

    public function edit_locale_mount($data, $locale_mount_id)
    {
        $edit_local_data = Locale_mount::where('id', '=', $locale_mount_id)->first();

        $edit_local_data['title']=$data["title"];
        $edit_local_data['short_description']=$data["short_description"];
        $edit_local_data['text']=$data["text"];
        $edit_local_data['how_get']=$data["how_get"];
        $edit_local_data['best_time']=$data["best_time"];

        $saved = $edit_local_data->save();

        if(!$saved){
            App::abort(500, 'Error');
        }
    }

    public function edit_global_mount($global_data,)
    {
        // dd($global_data['id']);
        $editing_global_mount = Mount::where('id', '=', $global_data['id'])->first();

        $editing_global_mount['map']=$global_data["map"];
        $editing_global_mount['weather']=$global_data["weather"];

        $editing_global_mount['name'] = $global_data["name"];

        $saved = $editing_global_mount -> save();

        if(!$saved){
            App::abort(500, 'Error');
        }
        else{
            return $editing_global_mount;

            $locale_mount = [
                'us_mount_id' => $editing_article['us_mount_id'],
                'ka_mount_id' => $editing_article['ka_mount_id'],
                // 'ru_mount_id' => $editing_article['ru_mount_id'],
            ];

            return $locale_mount;
        }
    }

    public function update(Request $request, $id)
    {
        $editing_product_category = Mount::where("id", "=", $id)->first();

        $editing_product_category['map'] = $request->editing_data['map'];

        $editing_product_category -> save();
    }

    public function destroy($id)
    {
        $deleted_product_category = Mount::where("id", "=", $id)->first();
        $deleted_product_category -> delete();
    }

    public function global_mount_validate($data)
    {
        $validator = Validator::make($data, [
            'name' => 'required',
        ]);
        if ($validator->fails()) {
            return $validator->messages();
        }
    }

    public function local_mount_validate($data)
    {
        $validator = Validator::make($data, [
            'text' => 'required',
            'title' => 'required',
            'short_description' => 'required | max:190',
        ]);
        if ($validator->fails()) {
            return $validator->messages();
        }
    }
}
