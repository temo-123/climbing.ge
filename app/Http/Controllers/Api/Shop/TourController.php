<?php

namespace App\Http\Controllers\Api\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Shop\Tour;
use App\Models\Shop\Locale_tour;
use App\Models\Shop\Tour_image;
use App\Models\Shop\Users_tour;

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
        return $tours = TourService::get_locale_tours_use_locale($global_tours, $request->lang);
    }

    function get_all_tours(){
        return Tour::latest('id')->get();
    }

    function get_user_tours(){
        return Auth::user()->tours;
    }

    function get_similar_tours(Request $request) {
        $tour = Tour::where('id','=', $request->tour_id)->first();

        $global_tours_count = Tour::where('category_id',strip_tags($tour->category_id))->count();

        if($global_tours_count > 0){
            $global_tours = Tour::where('category_id',strip_tags($tour->category_id))->limit(3)->get();
            return $tour = TourService::get_locale_tours_use_locale($global_tours, $request->lang);    
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
        
        $image_path = 'images/tour_img/';

        $tour_adding = TourService::add_content($data, Tour::class, Locale_tour::class, '_tour', $request);
        
        if (!array_key_exists('validation', $tour_adding->original)) {
            GalleryService::add_gallery_images(
                $request->gallery_images, 
                $tour_adding->original['global_tour_id'], 
                tour_image::class, 
                'image', 
                'tour_id', 
                '/images/tour_img/'
            );

            $this->create_tour_user_relations($tour_adding->original['global_tour_id']);
        }
        else {
            return $tour_adding;
        }
    }

    public function edit_tour(Request $request)
    {
        // $data = json_decode($request->data, true );
        $global_blocks = json_decode($request->global_blocks, true );

        $image_path = 'images/'.$data['global_article']['category'].'_img/';

        $article_editing = ArticlesService::edit_content(Article::class, Locale_article::class, '_article', $request);

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

    public function create_tour_user_relations($tout_id) {
        $new_user_relatione = new Users_tour;
        $new_user_relatione = $tout_id;
        $new_user_relatione = Auth::user()->id;
        $new_user_relatione -> save();
    }

    public function get_editing_tour(Request $request)
    {
        $tour = Tour::where('id', '=', $request->tour_id)->first();
        
        $data = [
            'global_tour' => $tour,
            'us_tour' => $tour->us_tour,
            'ka_tour' => $tour->ka_tour,
            'ru_tour' => $tour->ru_tour,

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

    function del_tour(Request $request){
        $global_id = $request->tour_id;

        $global_tour = Tour::where('id',strip_tags($global_id))->first();
        $us_tour = Locale_tour::where('id',strip_tags($global_tour->us_tour_id))->first();
        $ru_tour = Locale_tour::where('id',strip_tags($global_tour->ru_tour_id))->first();
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
        $ru_tour ->delete();
        $ka_tour ->delete();
    }

    function del_tour_image(Request $request) {
        $image = Tour_image::where('id', '=', $request->image_id)->first();
        if($image){
            ImageControllService::image_delete('images/tour_img/', $image, 'image');
            $image->delete();
        }
    }

    public function global_tour_editing_validate($global_data)
    {
        $validator = Validator::make($global_data, [
            'published' => 'required',
            'category_id' => 'required',
        ]);
        if ($validator->fails()) {
            return $validator->messages();
        }
    }

    public function global_tour_adding_validate($global_data)
    {
        $validator = Validator::make($global_data, [
            'published' => 'required',
            'category_id' => 'required',
            'us_title_for_url_title' => 'required|unique:locale_tours,title',
        ]);
        if ($validator->fails()) {
            return $validator->messages();
        }
    }

    private function local_tour_validate($data)
    {
        $validator = Validator::make($data, [
            'title' => 'required | max:190',
            'short_description' => 'required',
            'text' => 'required',
        ]);
        if ($validator->fails()) {
            return $validator->messages();
        }
    }

    private function image_validate($request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'nullable | image | mimes:jpeg,png,jpg,gif,svg | max:2048',
        ]);
        if ($validator->fails()) {
            return $validator->messages();
        }
    }

}
