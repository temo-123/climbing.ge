<?php

namespace App\Http\Controllers\Api\User\Admin\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Shop\Tour;
use App\Models\Shop\Locale_tour;
use App\Models\Shop\Tour_image;
use App\Models\User\User_tour;

use App\Services\TourService;
use App\Services\URLTitleService;
use App\Services\Abstract\ImageControllService;
use App\Services\GalleryService;
use App\Services\PermissionService;

use Validator;
use Auth;

class TourController extends Controller
{
    function get_user_tours(){
        // $auth = PermissionService::authorize('tour', 'view');
        // if ($auth) return $auth;
        return Auth::user()->tours;
    }

    function add_tour(Request $request){
        $auth = PermissionService::authorize('tour', 'add');
        // if ($auth) return $auth;
        $data = json_decode($request->data, true );

        $validator = Validator::make($data, [
            'global_tour.category_id' => 'required',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'validation' => $validator->messages()
            ], 422);
        }
        
        // $image_path = 'images/tour_img/';

        $tour_adding = TourService::add_content($data, Tour::class, Locale_tour::class, '_tour', $request);
        
        if (!array_key_exists('validation', $tour_adding->original)) {
            if($request->hasFile('tour_images')){
                GalleryService::add_gallery_images(
                    $request->tour_images, 
                    $tour_adding->original['global_tour_id'], 
                    tour_image::class, 
                    'image', 
                    'tour_id', 
                    '/images/tour_img/'
                );
            }

            $this->create_tour_user_relations($tour_adding->original['global_tour_id']);
        }
        else {
            return $tour_adding;
        }
    }

    public function edit_tour(Request $request)
    {
        $auth = PermissionService::authorize('tour', 'edit');
        // if ($auth) return $auth;
        $data = json_decode($request->data, true );

        $validator = Validator::make($data, [
            'global_tour.category_id' => 'required',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'validation' => $validator->messages()
            ], 422);
        }

        $tour_editing = TourService::edit_content(Tour::class, Locale_tour::class, '_tour', $request, 'images/tour_img/');

        if(!array_key_exists('validation', $tour_editing->original)){
            if($request->hasFile('tour_new_images')){
                GalleryService::add_gallery_images(
                    $request->tour_new_images, 
                    $tour_editing->original['global_tour_id'], 
                    tour_image::class, 
                    'image', 
                    'tour_id', 
                    '/images/tour_img/'
                );
            }
        }
        else{
            return $article_editing;
        }   
    }

    public function create_tour_user_relations($tour_id, $user_id = null) {
        $new_user_relatione = new User_tour;
        $new_user_relatione['tour_id'] = $tour_id;
        if($user_id == null){
            $new_user_relatione['user_id'] = Auth::user()->id;
        }
        else{
            $new_user_relatione['user_id'] = $user_id;
        }
        $new_user_relatione -> save();
    }

    public function change_user_relation(Request $request) {
        if($request['data']['old_user_id'] != []){
            $user_relatione = User_tour::where('tour_id', '=', $request['data']['tour_id'])->where('user_id', '=', $request['data']['old_user_id'])->first();
            if($user_relatione != []){
                $user_relatione['user_id'] = $request['data']['new_user_id'];
                $user_relatione -> save();
            }
        }
        else{
            $this->create_tour_user_relations($request['data']['tour_id'], $request['data']['new_user_id']);
        }
    }

    public function get_editing_tour(Request $request)
    {
        $auth = PermissionService::authorize('tour', 'edit');
        // if ($auth) return $auth;
        $tour = Tour::where('id', '=', $request->tour_id)->first();
        
        $data = [
            'global_tour' => $tour,
            'us_tour' => $tour->us_tour,
            'ka_tour' => $tour->ka_tour,
            // 'ru_tour' => $tour->ru_tour,

            'tour_images' => $tour->tour_images,
        ];

        return $data;
    }

    function get_tour_images(Request $request) {
        $tour = Tour::where('id', '=', $request->tour_id)->first();
        
        if (!$tour) {
            return abort(404);
        }
        
        if($tour->tour_images->count() > 0){
            return $tour->tour_images;
        }
        return [];
    }

    function del_tour(Request $request){
        $auth = PermissionService::authorize('tour', 'del');
        // if ($auth) return $auth;
        $global_id = $request->tour_id;

        $global_tour = Tour::where('id',strip_tags($global_id))->first();
        $us_tour = Locale_tour::where('id',strip_tags($global_tour->us_tour_id))->first();
        // $ru_tour = Locale_tour::where('id',strip_tags($global_tour->ru_tour_id))->first();
        $ka_tour = Locale_tour::where('id',strip_tags($global_tour->ka_tour_id))->first();
        
        // delete tour file
        $tour_images_count = Tour_image::where('tour_id',strip_tags($global_id))->count();
        
        if ($tour_images_count > 0) {
            $tour_images = Tour_image::where('tour_id',strip_tags($global_id))->get();
            foreach ($tour_images as $tour_image) {
                imageControllService::image_delete('images/tour_img/', $tour_image, 'image');
                $tour_image ->delete();
            }
        }

        // delete tour from db
        $global_tour ->delete();
        $us_tour ->delete();
        // $ru_tour ->delete();
        $ka_tour ->delete();
    }

    function del_tour_image(Request $request) {
        $auth = PermissionService::authorize('tour', 'edit');
        // if ($auth) return $auth;
        $image = Tour_image::where('id', '=', $request->image_id)->first();
        if($image){
            ImageControllService::image_delete('images/tour_img/', $image, 'image');
            $image->delete();
        }
    }
}
