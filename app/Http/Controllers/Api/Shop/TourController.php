<?php

namespace App\Http\Controllers\Api\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Shop\Tour;
use App\Models\Shop\Locale_tour;
use App\Models\User\User_tour;

use App\Services\TourService;
use App\Services\URLTitleService;
use App\Services\Abstract\ImageControllService;
use App\Services\GalleryService;

use Validator;
use Auth;

class TourController extends Controller
{
    function get_guide($user_id){
        $user_tour = User_tour::where('user_id', '=', $user_id)->first();
        if($user_tour){
            return $user_tour->user;
        }
        return abort(404);
    }
    
    function get_tours(Request $request){
        $locale = $request->lang ?? 'us';

        $tours = Tour::latest('id')
            ->where('published', 1)
            ->with(['us_tour', 'ka_tour', 'tour_images', 'user'])
            ->get();

        $returned_array = [];

        foreach ($tours as $tour) {
            $localeData = ($locale === 'ka') ? $tour->ka_tour : $tour->us_tour;
            $firstImage = $tour->tour_images->first();
            $users = $tour->user;
            array_push($returned_array, [
                'id'          => $tour->id,
                'global_data' => $tour,
                'locale_data' => $localeData,
                'image'       => $firstImage ? $firstImage->image : '',
                'users'       => $users,
                'user'        => $users->first() ?? null,
            ]);
        }
        return $returned_array;
    }

    public function get_all_tours($lang = null)
    {
        $locale = $lang ?? 'us';

        $tours = Tour::latest('id')
            ->with(['us_tour', 'ka_tour', 'tour_images', 'user'])
            ->get();

        $returned_array = [];

        foreach ($tours as $tour) {
            $localeData = ($locale === 'ka') ? $tour->ka_tour : $tour->us_tour;
            $firstImage = $tour->tour_images->first();
            $users = $tour->user;
            array_push($returned_array, [
                'id'          => $tour->id,
                'global_data' => $tour,
                'locale_data' => $localeData,
                'image'       => $firstImage ? $firstImage->image : '',
                'users'       => $users,
                'user'        => $users->first() ?? null,
            ]);
        }
        return $returned_array;
    }

    public function get_tours_for_index(Request $request){
        $global_tours = Tour::where("published", "=", 1)->get();
        return $tours = TourService::get_tours_use_locale($global_tours, $request->lang);
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

    public function get_editing_tour(Request $request)
    {
        $tour = Tour::where('id', '=', $request->tour_id)->first();
        
        if (!$tour) {
            return abort(404);
        }
        
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
}
