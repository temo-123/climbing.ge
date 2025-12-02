<?php

namespace App\Http\Controllers\Api\Shop;

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

use Validator;
use Auth;

class TourController extends Controller
{
    function get_tours(Request $request){
        $global_tours = Tour::where("published", "=", 1)->get();
        return $tours = TourService::get_tours_use_locale($global_tours, $request->lang);
    }

    public function get_all_tours()
    {
        $tours = Tour::get();
        $returned_array = [];
        foreach($tours as $tour){
            array_push($returned_array, [
                'tour' => $tour,
                'user' => $tour->user[0]
            ]);
        }
        return $returned_array;
    }

    public function get_tours_for_index()
    {
        $tours = Tour::latest()->take(3)->get();
        $returned_array = [];
        foreach($tours as $tour){
            array_push($returned_array, [
                'tour' => $tour,
                'user' => $tour->user[0]
            ]);
        }
        return $returned_array;
    }

    function get_user_tours(){
        return Auth::user()->tours;
    }

    function get_similar_tours(Request $request) {
        $tour = Tour::where('id','=', $request->tour_id)->where("published", "=", 1)->first();

        $global_tours_count = Tour::where('category_id',strip_tags($tour->category_id))->count();

        if($global_tours_count > 0){
            $global_tours = Tour::where('id','!=', $request->tour_id)->where('category_id',strip_tags($tour->category_id))->where("published", "=", 1)->limit(3)->get();
            return $tour = TourService::get_tours_use_locale($global_tours, $request->lang);    
        }
    }

    function get_tour(Request $request){
        $global_tour_count = Tour::where('url_title',strip_tags($request->url_title))->count();

        if($global_tour_count > 0){
            $global_tour = Tour::where('url_title',strip_tags($request->url_title))->first();
            return $tour = TourService::get_locale_tour_in_page($global_tour, $request->lang);    
        }
        return abort(404);
    }

    // function add_tour(Request $request){
    //     $data = json_decode($request->data, true );

    //     $validator = Validator::make($data, [
    //         'global_tour.category_id' => 'required',
    //     ]);
        
    //     if ($validator->fails()) {
    //         return response()->json([
    //             'validation' => $validator->messages()
    //         ], 422);
    //     }
        
    //     // $image_path = 'images/tour_img/';

    //     $tour_adding = TourService::add_content($data, Tour::class, Locale_tour::class, '_tour', $request);
        
    //     if (!array_key_exists('validation', $tour_adding->original)) {
    //         if($request->hasFile('tour_images')){
    //             GalleryService::add_gallery_images(
    //                 $request->tour_images, 
    //                 $tour_adding->original['global_tour_id'], 
    //                 tour_image::class, 
    //                 'image', 
    //                 'tour_id', 
    //                 '/images/tour_img/'
    //             );
    //         }

    //         $this->create_tour_user_relations($tour_adding->original['global_tour_id']);
    //     }
    //     else {
    //         return $tour_adding;
    //     }
    // }

    // public function edit_tour(Request $request)
    // {
    //     $data = json_decode($request->data, true );

    //     $validator = Validator::make($data, [
    //         'global_tour.category_id' => 'required',
    //     ]);
        
    //     if ($validator->fails()) {
    //         return response()->json([
    //             'validation' => $validator->messages()
    //         ], 422);
    //     }

    //     $tour_editing = TourService::edit_content(Tour::class, Locale_tour::class, '_tour', $request, 'images/tour_img/');

    //     if(!array_key_exists('validation', $tour_editing->original)){
    //         if($request->hasFile('tour_new_images')){
    //             GalleryService::add_gallery_images(
    //                 $request->tour_new_images, 
    //                 $tour_editing->original['global_tour_id'], 
    //                 tour_image::class, 
    //                 'image', 
    //                 'tour_id', 
    //                 '/images/tour_img/'
    //             );
    //         }
    //     }
    //     else{
    //         return $article_editing;
    //     }   
    // }

    // public function create_tour_user_relations($tour_id, $user_id = null) {
    //     $new_user_relatione = new User_tour;
    //     $new_user_relatione['tour_id'] = $tour_id;
    //     if($user_id == null){
    //         $new_user_relatione['user_id'] = Auth::user()->id;
    //     }
    //     else{
    //         $new_user_relatione['user_id'] = $user_id;
    //     }
    //     $new_user_relatione -> save();
    // }

    // public function change_user_relation(Request $request) {
    //     if($request['data']['old_user_id'] != []){
    //         $user_relatione = User_tour::where('tour_id', '=', $request['data']['tour_id'])->where('user_id', '=', $request['data']['old_user_id'])->first();
    //         if($user_relatione != []){
    //             $user_relatione['user_id'] = $request['data']['new_user_id'];
    //             $user_relatione -> save();
    //         }
    //     }
    //     else{
    //         $this->create_tour_user_relations($request['data']['tour_id'], $request['data']['new_user_id']);
    //     }
    // }

    public function get_editing_tour(Request $request)
    {
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
        
        if($tour->tour_images->count() > 0){
            return $tour->tour_images;
        }
        return [];
    }

    // function del_tour(Request $request){
    //     $global_id = $request->tour_id;

    //     $global_tour = Tour::where('id',strip_tags($global_id))->first();
    //     $us_tour = Locale_tour::where('id',strip_tags($global_tour->us_tour_id))->first();
    //     // $ru_tour = Locale_tour::where('id',strip_tags($global_tour->ru_tour_id))->first();
    //     $ka_tour = Locale_tour::where('id',strip_tags($global_tour->ka_tour_id))->first();
        
    //     // delete tour file
    //     $tour_images_count = Tour_image::where('tour_id',strip_tags($global_id))->count();
        
    //     if ($tour_images_count > 0) {
    //         $tour_images = Tour_image::ShipedCountryControllerShipedCountryController('tour_id',strip_tags($global_id))->get();
    //         foreach ($tour_images as $tour_image) {
    //             imageControllService::image_delete('images/tour_img/', $tour_image, 'image');
    //             $tour_image ->delete();
    //         }
    //     }

    //     // delete tour from db
    //     $global_tour ->delete();
    //     $us_tour ->delete();
    //     // $ru_tour ->delete();
    //     $ka_tour ->delete();
    // }

    // function del_tour_image(Request $request) {
    //     $image = Tour_image::where('id', '=', $request->image_id)->first();
    //     if($image){
    //         ImageControllService::image_delete('images/tour_img/', $image, 'image');
    //         $image->delete();
    //     }
    // }
}
