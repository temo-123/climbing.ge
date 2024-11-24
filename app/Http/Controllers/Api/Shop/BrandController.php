<?php

namespace App\Http\Controllers\Api\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    function get_tours(Request $request){
        $global_tours = Tour::where("published", "=", 1)->get();
        return $tours = TourService::get_tours_use_locale($global_tours, $request->lang);
    }

    function get_tour(Request $request){
        $global_tour_count = Tour::where('url_title',strip_tags($request->url_title))->count();

        if($global_tour_count > 0){
            $global_tour = Tour::where('url_title',strip_tags($request->url_title))->first();
            return $tour = TourService::get_locale_tour_in_page($global_tour, $request->lang);    
        }
        return abort(404);
    }

    function add_tour(Request $request){
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
        
        // if (!array_key_exists('validation', $tour_adding->original)) {
        //     if($request->hasFile('tour_images')){
        //         GalleryService::add_gallery_images(
        //             $request->tour_images, 
        //             $tour_adding->original['global_tour_id'], 
        //             tour_image::class, 
        //             'image', 
        //             'tour_id', 
        //             '/images/tour_img/'
        //         );
        //     }

        //     $this->create_tour_user_relations($tour_adding->original['global_tour_id']);
        // }
        // else {
            return $tour_adding;
        // }
    }

    public function edit_tour(Request $request)
    {
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

        // if(!array_key_exists('validation', $tour_editing->original)){
        //     if($request->hasFile('tour_new_images')){
        //         GalleryService::add_gallery_images(
        //             $request->tour_new_images, 
        //             $tour_editing->original['global_tour_id'], 
        //             tour_image::class, 
        //             'image', 
        //             'tour_id', 
        //             '/images/tour_img/'
        //         );
        //     }
        // }
        // else{
            return $article_editing;
        // }   
    }

    function del_tour(Request $request){
        $global_id = $request->tour_id;

        $global_tour = Tour::where('id',strip_tags($global_id))->first();
        $us_tour = Locale_tour::where('id',strip_tags($global_tour->us_tour_id))->first();
        $ka_tour = Locale_tour::where('id',strip_tags($global_tour->ka_tour_id))->first();
        
        // delete tour file
        $tour_images_count = Tour_image::where('tour_id',strip_tags($global_id))->count();
        
        if ($tour_images_count > 0) {
            $tour_images = Tour_image::ShipedCountryControllerShipedCountryController('tour_id',strip_tags($global_id))->get();
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
}
