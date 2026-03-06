<?php

namespace App\Http\Controllers\Api\User\Admin\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Guide\Article;
use App\Models\Guide\Sector;
use App\Models\Guide\Route;
use App\Models\Guide\Mtp;
use App\Models\Guide\Mtp_pitch;
use App\Models\Guide\Sector_image;
use App\Models\Guide\Spot_rocks_image;
use App\Models\Guide\Sector_local_image;
use App\Models\Guide\Sector_local_image_sector;

use App\Services\Abstract\ImageControllService;
use App\Services\PermissionService;

use Validator;

class SectorController extends Controller
{
    public function save_sector_sequence(Request $request)
    {
        $auth = PermissionService::authorize('sector', 'edit');
        if ($auth) return $auth;
        $sectors_num=0;
        foreach ($request->new_sector_sequence as $sector) {
            $sector_id = $sector['id'];
            $sector = Sector::where('id',strip_tags($sector_id))->first();
            $sectors_num++;
            $sector['num'] = $sectors_num;
            $sector->update();
        }
    }

    public function add_sector(Request $request)
    {
        $auth = PermissionService::authorize('sector', 'add');
        if ($auth) return $auth;
        $data = json_decode($request->data, true );
        $validate = $this->sector_validate($data);

        if ($validate != null) {
            return response()->json($validate, 422);
        }
        else{
            $spot_sector_count = Sector::where('article_id', '=', $data['article_id'])->count();
            $new_sector = new Sector();

            $new_sector['num'] = $spot_sector_count++;
            $new_sector['published'] = $data['published'];
            $new_sector['article_id'] = $data['article_id'];
            $new_sector['name'] = $data['name'];
            // $new_sector['text'] = $data['text'];
            $new_sector['us_description'] = $data['us_description'];
            $new_sector['ka_description'] = $data['ka_description'];
            $new_sector['all_day_in_shade'] = $data['all_day_in_shade'];
            $new_sector['all_day_in_sun'] = $data['all_day_in_sun'];
            $new_sector['in_the_shade_afternoon'] = $data['in_the_shade_afternoon'];
            $new_sector['in_the_shade_befornoon'] = $data['in_the_shade_befornoon'];
            $new_sector['in_shade_after_10'] = $data['in_shade_after_10'];
            $new_sector['in_shade_after_15'] = $data['in_shade_after_15'];
            $new_sector['slabby'] = $data['slabby'];
            $new_sector['vertical'] = $data['vertical'];
            $new_sector['overhang'] = $data['overhang'];
            $new_sector['roof'] = $data['roof'];

            $new_sector['for_family'] = $data['for_family'];
            $new_sector['for_kids'] = $data['for_kids'];
            $new_sector['wolking_time'] = $data['wolking_time'];
            $new_sector['is_helmet'] = $data['is_helmet'];

            $save_sector = $new_sector -> save();

            if($request->sector_images){
                if(!$save_sector){
                    App::abort(500, 'Saiving error');
                }
                else{
                    $this->add_sector_images($request->sector_images, $new_sector->id);
                }
            }
        }
    }

    public function add_sector_images($images, $sector_id)
    {
        foreach ($images as $image) {
            $file_new_name;
            $file_new_name = ImageControllService::upload_loop_image('images/sector_img/', $image, 0);
            if(file_exists(public_path('images/sector_img/') . $file_new_name)){
                $new_option_image = new Sector_image;

                $sector_images_count = Sector_image::where('sector_id',strip_tags($sector_id))->count();
                if($sector_images_count == 0){
                    $new_route_num = 1;
                }
                else{
                    $new_option_image['num'] = $sector_images_count+1;
                }

                $new_option_image['image'] = $file_new_name;
                $new_option_image['sector_id'] = $sector_id;
        
                $saiving = $new_option_image -> save();

                if($saiving){
                    echo 'Upload socsesful \n';
                }
            }
            else{
                echo 'Upload error \n';
            }
        }
    }

    public function edit_sector(Request $request, )
    {
        $auth = PermissionService::authorize('sector', 'edit');
        if ($auth) return $auth;
        $data = json_decode($request->data, true );
        $validate = $this->sector_validate($data);

        if ($validate != null) {
            return response()->json($validate, 422);
        }
        else{
            $edit_sector = Sector::where("id", "=", $request->sector_id)->first();

            $edit_sector['published'] = $data['published'];
            $edit_sector['article_id'] = $data['article_id'];
            $edit_sector['name'] = $data['name'];
            // $edit_sector['text'] = $data['text'];
            $edit_sector['us_description'] = $data['us_description'];
            $edit_sector['ka_description'] = $data['ka_description'];
            $edit_sector['all_day_in_shade'] = $data['all_day_in_shade'];
            $edit_sector['all_day_in_sun'] = $data['all_day_in_sun'];
            $edit_sector['in_the_shade_afternoon'] = $data['in_the_shade_afternoon'];
            $edit_sector['in_the_shade_befornoon'] = $data['in_the_shade_befornoon'];
            $edit_sector['in_shade_after_10'] = $data['in_shade_after_10'];
            $edit_sector['in_shade_after_15'] = $data['in_shade_after_15'];
            $edit_sector['slabby'] = $data['slabby'];
            $edit_sector['vertical'] = $data['vertical'];
            $edit_sector['overhang'] = $data['overhang'];
            $edit_sector['roof'] = $data['roof'];

            $edit_sector['for_family'] = $data['for_family'];
            $edit_sector['for_kids'] = $data['for_kids'];
            $edit_sector['wolking_time'] = $data['wolking_time'];
            $edit_sector['is_helmet'] = $data['is_helmet'];

            $save_sector = $edit_sector -> save();
            
            if($request->sector_new_images){
                if(!$save_sector){
                    App::abort(500, 'Saving error');
                }
                else{
                    $this->add_sector_images($request->sector_new_images, $edit_sector->id);
                }
            }
        }
    }

    public function del_sector(Request $request)
    {
        $auth = PermissionService::authorize('sector', 'del');
        if ($auth) return $auth;
        $sector_id=$request->sector_id;

        $sector = Sector::where('id',strip_tags($sector_id))->first();

        // delete product file
        $sector_images_count = Sector_image::where('sector_id',strip_tags($sector_id))->count();
        
        if ($sector_images_count > 0) {
            $sector_images = Sector_image::where('sector_id',strip_tags($sector_id))->get();
            foreach ($sector_images as $sector_image) {
                imageControllService::image_delete('images/sector_img/', $sector_image, $request);
                $sector_image ->delete();
            }
        }

        // delete product from db
        $sector ->delete();
    }

    public function sector_image_validate($request)
    {
        $request->validate([
            // 'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
    }

    private function sector_validate($sector_data)
    {
        $validator = Validator::make($sector_data, [
            'name' => 'required|max:190',
            'article_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $validator->messages();
        }
    }

    public function routes_sequence(Request $request)
    {
        $auth = PermissionService::authorize('route', 'edit');
        if ($auth) return $auth;
        // dd($request);
        if($request->routes_sequence){
            $route_num = 0;
            foreach ($request->routes_sequence as $route) {
                $route_id = $route['id'];
                $route = Route::where('id',strip_tags($route_id))->first();
                $route_num++;
                $route['num'] = $route_num;
                $route->update();
            }
        }

        if($request->mtp_sequence){
            $mtp_num=0;
            foreach ($request->mtp_sequence as $mtp) {
                $mtp_id = $mtp['id'];
                $mtp = mtp::where('id',strip_tags($mtp_id))->first();
                $mtp_num++;
                $mtp['num'] = $mtp_num;
                $mtp->update();
            }
        }

        if($request->sector_images_sequence){
            $image_num=0;
            foreach ($request->sector_images_sequence as $image) {
                $image_id = $image['id'];
                // dd($image_id);
                $image = Sector_image::where('id',strip_tags($image_id))->first();
                $image_num++;
                $image['num'] = $image_num;
                $image->update();
            }
        }
    }

    public function del_sector_image_from_db(Request $request)
    {
        $auth = PermissionService::authorize('sector', 'edit');
        if ($auth) return $auth;
        $image = Sector_image::where('id', '=', $request->image_id)->first();
        ImageControllService::image_delete('images/sector_img/', $image, 'image');
        $image ->delete();
    }

    public function get_sector_editing_data(Request $request)
    {
        $auth = PermissionService::authorize('sector', 'edit');
        if ($auth) return $auth;
        $sector = Sector::where('id',strip_tags($request->sector_id))->first();
        $data = [
            'sector' => $sector,
            'article' => $sector->article,
            'images' => $sector->images,
        ];
        return $data;
    }

}
