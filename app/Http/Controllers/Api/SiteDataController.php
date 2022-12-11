<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Gallery_image;

use App\Models\Article;
use App\Models\Locale_article;
// use App\Models\Us_article;
// use App\Models\Ru_article;

use App\Models\Mount;

use App\Models\Site;
use App\Models\Locale_site;

use App\Models\Sector;
use App\Models\Route;
use App\Models\Mtp;
use App\Models\Mtp_pitch;

use App\User;
use App\Models\Following_users;
use App\Models\Role;
use App\Models\Permission;

use App\Models\Product;
use App\Models\Product_category;

use App\Models\Comment;

use App\Models\Region;

class SiteDataController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $site_data = Site::first();
    }

    public function site_data_counts()
    {
        $counts = [];

        $counts['gallery_images'] = Gallery_image::count();
        $counts['index_header_images'] = Gallery_image::where("image_type", "=", 2)->count();
        $counts['article_gallery_images'] = Gallery_image::where("image_type", "=", 1)->count();

        $counts['mount_masives'] = Mount::count();
        $counts['mountaineering_route'] = Article::where("category", "=", 'mount_route')->count();

        $counts['outdoor_climbing'] = Article::where("category", "=", 'outdoor')->count();

        $counts['ice_climbing'] = Article::where("category", "=", 'ice')->count();
        $counts['indoor_gyms'] = Article::where("category", "=", 'indoor')->count();
        $counts['other_antyvity'] = Article::where("category", "=", 'other')->count();
        $counts['event'] = Article::where("category", "=", 'event')->count();
        $counts['news'] = Article::where("category", "=", 'news')->count();
        $counts['techtip'] = Article::where("category", "=", 'techtip')->count();

        $counts['region'] = Region::count();

        $counts['global_articles_count'] = Article::count();
        $counts['ka_articles_count'] = Locale_article::where("locale", "=", 'ka')->count();
        $counts['ru_articles_count'] = Locale_article::where("locale", "=", 'ru')->count();
        $counts['us_articles_count'] = Locale_article::where("locale", "=", 'us')->count();

        $counts['us_articles_errors_count'] = 1;
        $counts['ka_articles_errors_count'] = 0;
        $counts['ru_articles_errors_count'] = 0;

        $counts['bouldering_routes_count'] = Route::where("category", "=", 'bouldering')->count();
        $counts['sport_climbing_routes_count'] = Route::where("category", "=", 'sport')->count();
        $counts['top_rope_routes_count'] = Route::where("category", "=", 'top')->count();
        $counts['tred_routes_count'] = Route::where("category", "=", 'tred')->count();
        $counts['aid_routes_count'] = Route::where("category", "=", 'aid')->count();

        $counts['routes_count'] = Route::count();
        $counts['sectors_count'] = Sector::count();
        $counts['mtp_count'] = Mtp::count();
        $counts['mtp_pitch_count'] = Mtp_pitch::count();

        $counts['users'] = User::count();
        $counts['following_users'] = Following_users::count();
        $counts['roles'] = Role::count();
        $counts['permissions'] = Permission::count();

        $counts['products'] = Product::count();
        $counts['product_categories'] = Product_category::count();
        
        return $counts;
    }

    public function get_site_locale_data(Request $request)
    {
        $data = [
            'site_global_data' => Site::first()
        ];
        if($request->locale == 'ka') {
            $data = [
                'site_global_data' => Locale_site::where("locale", "=", 'ka')->first()
            ];
        }
        if($request->locale == 'ru') {
            $data = [
                'site_global_data' => Locale_site::where("locale", "=", 'ru')->first()
            ];
        }
        if($request->locale == 'us') {
            $data = [
                'site_global_data' => Locale_site::where("locale", "=", 'us')->first()
            ];
        }

        return $data;
    }

    public function get_site_global_data(){
        return Site::first();
    }
    public function get_site_ka_data(){
        return Locale_site::where("locale", "=", 'ka')->first();
    }
    public function get_site_ru_data(){
        return Locale_site::where("locale", "=", 'ru')->first();
    }
    public function get_site_us_data(){
        return Locale_site::where("locale", "=", 'us')->first();
    }

    public function edit_all_site_data(Request $request)
    {
        # code...
    }

    public function edit_site_global_data(Request $request){
        //
    }
    public function edit_site_ka_data(Request $request){
        //
    }
    public function edit_site_ru_data(Request $request){
        //
    }
    public function edit_site_us_data(Request $request){
        //
    }
}
