<?php

namespace App\Http\Controllers\Api\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Validator;

use App\Models\Guide\Ice_route;
use App\Models\Guide\Ice_sector;
use App\Models\Guide\Ice_sector_image;

use App\Services\Abstract\ImageControllService;

class IceSectorController extends Controller
{
   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function get_all_sectors()
    {
        return Ice_sector::latest('id')->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function get_sector_editing_data(Request $request)
    {
        return Ice_sector::where('id', '=', $request->sector_id)->first();
    }
    
    function get_sector_data_for_model(Request $request) {
        $ice_sector = Ice_sector::where('id',strip_tags($request->sector_id))->first();
        $ice_sector_routes = $ice_sector->routes;
        $ice_sector_images = $ice_sector->images;

        $data = [
            "images" => $ice_sector_images,
            "routes" => $ice_sector_routes,
        ];

        return $data;
    }

    public function get_article_sectors(Request $request)
    {
        $ice_sectors = Ice_sector::where('article_id',strip_tags($request->article_id))->where('published',strip_tags(1))->get();

        // dd($request->article_id, $ice_sectors);

        $sector = array();
        $routes = array();
        $images = array();

        $data = array();

        foreach ($ice_sectors as $ice_sector) {
            if($ice_sector){
                $sector = $ice_sector;
                if($ice_sector->routes){
                    $routes = $ice_sector->routes;
                }
                if($ice_sector->images){
                    $images = $ice_sector->images;
                }

                array_push($data, [
                    'sector' => $sector,
                    'routes' => $routes,
                    'images' => $images,
                ]);
            }
        }

        return $data;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function add_sector(Request $request)
    {
        $data = json_decode($request->data, true );
        $validate = $this->ice_sector_validate($data);

        if ($validate != null) {
            return response()->json($validate, 422);
        }
        else{
            $new_ice_sector = new Ice_sector();

            $new_ice_sector['published'] = $data['published'];
            $new_ice_sector['article_id'] = $data['article_id'];
            $new_ice_sector['name'] = $data['name'];
            $new_ice_sector['text'] = $data['text'];

            $save_ice_sector = $new_ice_sector -> save();

            if($request->sector_images){
                if(!$save_ice_sector){
                    App::abort(500, 'Saiving error');
                }
                else{
                    $this->add_ice_sector_images($request->sector_images, $new_ice_sector->id);
                }
            }
        }
    }

    public function add_ice_sector_images($images, $ice_sector_id)
    {
        foreach ($images as $image) {
            $file_new_name;
            $file_new_name = ImageControllService::upload_loop_image('images/ice_sector_img/', $image, 0);
            if(file_exists(public_path('images/ice_sector_img/') . $file_new_name)){
                $new_option_image = new Ice_sector_image;

                $ice_sector_images_count = Ice_sector_image::where('ice_sector_id',strip_tags($ice_sector_id))->count();
                if($ice_sector_images_count == 0){
                    $new_route_num = 1;
                }
                else{
                    $new_option_image['num'] = $ice_sector_images_count+1;
                }

                $new_option_image['image'] = $file_new_name;
                $new_option_image['ice_sector_id'] = $ice_sector_id;
        
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
        $data = json_decode($request->data, true );
        $validate = $this->ice_sector_validate($data);

        if ($validate != null) {
            return response()->json($validate, 422);
        }
        else{
            $edit_ice_sector = Ice_sector::where("id", "=", $request->sector_id)->first();

            $edit_ice_sector['published'] = $data['published'];
            $edit_ice_sector['article_id'] = $data['article_id'];
            $edit_ice_sector['name'] = $data['name'];
            $edit_ice_sector['text'] = $data['text'];

            $save_ice_sector = $edit_ice_sector -> save();
            // dd($request->sector_new_images);
            
            if($request->sector_new_images){
                if(!$save_ice_sector){
                    App::abort(500, 'Saiving error');
                }
                else{
                    $this->add_ice_sector_images($request->sector_new_images, $edit_ice_sector->id);
                }
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function del_sector(Request $request)
    {
        $ice_sector_id=$request->sector_id;

        $ice_sector = Ice_sector::where('id',strip_tags($ice_sector_id))->first();

        // delete product file
        $ice_sector_images_count = Ice_sector_image::where('ice_sector_id',strip_tags($ice_sector_id))->count();

        if ($ice_sector_images_count > 0) {
            $ice_sector_images = Ice_sector_image::where('ice_sector_id',strip_tags($ice_sector_id))->get();
            
            foreach ($ice_sector_images as $ice_sector_image) {
                ImageControllService::image_delete('images/ice_sector_img/', $ice_sector_image, 'image');
                $ice_sector_image ->delete();
            }
        }

        // delete product from db
        $ice_sector ->delete();
    }


    public function routes_sequence(Request $request)
    {
        if($request->routes_sequence){
            $route_num = 0;
            foreach ($request->routes_sequence as $route) {
                $route_id = $route['id'];
                $route = Ice_route::where('id',strip_tags($route_id))->first();
                $route_num++;
                $route['num'] = $route_num;
                $route->update();
            }
        }

        if($request->sector_images_sequence){
            $image_num=0;
            foreach ($request->sector_images_sequence as $image) {
                $image_id = $image['id'];
                $image = Ice_sector_image::where('id',strip_tags($image_id))->first();
                $image_num++;
                $image['num'] = $image_num;
                $image->update();
            }
        }
    }

    public function ice_sector_image_validate($request)
    {
        $request->validate([
            // 'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
    }

    private function ice_sector_validate($ice_sector_data)
    {
        $validator = Validator::make($ice_sector_data, [
            'name' => 'required|max:190',
            'article_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $validator->messages();
        }
    }
}
