<?php

namespace App\Http\Controllers\Api\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Guide\Header_image;
use App\Models\Guide\Article_image;

use App\Models\Guide\Article;
use App\Models\Guide\Locale_article;

use App\Models\Guide\Event;
// use App\Models\Guide\Ru_article;

use App\Models\Guide\Mount;

use App\Models\Site;
use App\Models\Locale_site;
use App\Services\SiteDataService;

use App\Models\Guide\Sector;
use App\Models\Guide\Route;
use App\Models\Guide\Mtp;
use App\Models\Guide\Mtp_pitch;

use App\Models\User;
use App\Models\User\Following_users;
use App\Models\User\Role;
use App\Models\User\Permission;

use App\Models\Shop\Product;
use App\Models\Shop\Product_category;

use App\Models\User\Service_follower;
use App\Models\User\Social_account;
use App\Models\Guide\Article_comment_complaint;

use App\Models\Guide\Comment;

use App\Models\Guide\Region;

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

        $counts['index_header_images'] = Header_image::count();
        $counts['article_gallery_images'] = Article_image::count();

        $counts['mount_masives'] = Mount::count();
        $counts['mountaineering_route'] = Article::where("category", "=", 'mount_route')->count();

        $counts['outdoor_climbing'] = Article::where("category", "=", 'outdoor')->count();

        $counts['ice_climbing'] = Article::where("category", "=", 'ice')->count();
        $counts['indoor_gyms'] = Article::where("category", "=", 'indoor')->count();
        $counts['other_antyvity'] = Article::where("category", "=", 'other')->count();
        $counts['news'] = Article::where("category", "=", 'news')->count();
        $counts['techtip'] = Article::where("category", "=", 'techtip')->count();

        $counts['active_events_count'] = Event::where("category", "=", 'event')->count();
        $counts['completed_events_count'] = Event::where("category", "=", 'event')->count();
        $counts['active_comprtitions_count'] = Event::where("category", "=", "competition")->count();
        $counts['completed_comprtitions_count'] = Event::where("category", "=", "competition")->count();

        $counts['region'] = Region::count();

        $counts['global_articles_count'] = Article::count();
        $counts['ka_articles_count'] = Locale_article::where("locale", "=", 'ka')->count();
        // $counts['ru_articles_count'] = Locale_article::where("locale", "=", 'ru')->count();
        $counts['us_articles_count'] = Locale_article::where("locale", "=", 'us')->count();

        $counts['us_articles_errors_count'] = 1;
        $counts['ka_articles_errors_count'] = 0;
        // $counts['ru_articles_errors_count'] = 0;

        $counts['bouldering_routes_count'] = Route::where("category", "=", 'bouldering')->count();
        $counts['sport_climbing_routes_count'] = Route::where("category", "=", 'sport')->count();
        $counts['top_rope_routes_count'] = Route::where("category", "=", 'top')->count();
        $counts['tred_routes_count'] = Route::where("category", "=", 'tred')->count();
        $counts['aid_routes_count'] = Route::where("category", "=", 'aid')->count();

        $counts['routes_count'] = Route::count();
        $counts['sectors_count'] = Sector::count();
        $counts['mtp_count'] = Mtp::count();
        $counts['mtp_pitch_count'] = Mtp_pitch::count();

        $counts['article_comment_complaint_count'] = Article_comment_complaint::count();
        // $counts['mtp_pitch_count'] = Mtp_pitch::count();
        
        $counts['article_comments_count'] = Comment::count();
        $counts['product_comments_count'] = Comment::count();

        $counts['users'] = User::count();
        $counts['following_users'] = Following_users::count();

        $counts['guid_follovers'] = Service_follower::where("service", "=", 'shop')->count();
        $counts['shop_follovers'] = Service_follower::where("service", "=", 'shop')->count();
        
        // $counts['google_accounts_count'] = Social_account::where("provider", "=", 'google')->count();
        // $counts['facebook_accounts_count'] = Social_account::where("provider", "=", 'facebook')->count();

        $counts['roles'] = Role::count();
        $counts['permissions'] = Permission::count();

        $counts['products'] = Product::count();
        $counts['product_categories'] = Product_category::count();

        $conflict_us_articles = array();
        $conflict_ka_articles = array();
        // $conflict_ru_articles = array();

        foreach (Locale_article::where("locale", "=", 'us')->get() as $local_article) {
            $active_global_article = $local_article->global_article_us;

            if (!$active_global_article) {
                array_push($conflict_us_articles, $active_global_article);
            }
        }
        foreach (Locale_article::where("locale", "=", 'ka')->get() as $local_article) {
            $active_global_article = $local_article->global_article_ka;

            if (!$active_global_article) {
                array_push($conflict_ka_articles, $active_global_article);
            }
        }
        // foreach (Locale_article::where("locale", "=", 'ru')->get() as $local_article) {
        //     $active_global_article = $local_article->global_article_ru;

        //     if (!$active_global_article) {
        //         array_push($conflict_ru_articles, $active_global_article);
        //     }
        // }

        $counts['us_article_errors'] = count($conflict_us_articles);
        $counts['ka_article_errors'] = count($conflict_ka_articles);
        // $counts['ru_article_errors'] = count($conflict_ru_articles);
        
        // dd(count($post_data),$conflict_ru_articles->count(), $conflict_ru_articles->count(), $conflict_ru_articles->count());
        
        return $counts;
    }

    // public function fix_article_bugs(Request $request)
    // {
    //     foreach (Locale_article::where("locale", "=", 'us')->get() as $local_article) {
    //         $active_global_article = $local_article->global_article_us;

    //         if (!$active_global_article) {
    //             // dd($local_article->global_article_us);
    //             $local_article -> delete();
    //         }
    //     }
    //     foreach (Locale_article::where("locale", "=", 'ka')->get() as $local_article) {
    //         $active_global_article = $local_article->global_article_ka;

    //         if (!$active_global_article) {
    //             $local_article -> delete();
    //         }
    //     }
    //     // foreach (Locale_article::where("locale", "=", 'ru')->get() as $local_article) {
    //     //     $active_global_article = $local_article->global_article_ru;

    //     //     if (!$active_global_article) {
    //     //         $local_article -> delete();
    //     //     }
    //     // }
    // }

    public function get_site_locale_data(Request $request, $locale)
    {
        // dd('site locale data');
        return SiteDataService::getSiteData($locale ?? 'us');
    }



    // public function get_site_global_data(){
    //     return SiteDataService::getSiteData()['global_data'] ?? null;
    // }
    // public function get_site_ka_data(){
    //     return SiteDataService::getSiteData('ka')['locale_data'] ?? null;
    // }
    // public function get_site_us_data(){
    //     return SiteDataService::getSiteData('us')['locale_data'] ?? null;
    // }




    // public function edit_site_data(Request $request)
    // {
    //     $global_data = Site::first();
    //     $ka_data = Locale_site::where("locale", "=", 'ka')->first();
    //     // $ru_data = Locale_site::where("locale", "=", 'ru')->first();
    //     $us_data = Locale_site::where("locale", "=", 'us')->first();

    //     $this->edit_global_data($request, $global_data);
    //     $this->edit_local_data($request, $ka_data, 'ka');
    //     // $this->edit_local_data($request, $ru_data, 'ru');
    //     $this->edit_local_data($request, $us_data, 'us');
    // }

    // public function edit_site_global_data(Request $request){
    //     $global_data = Site::first();
    //     $this->edit_global_data($request, $global_data);
    // }
    // public function edit_site_ka_data(Request $request){
    //     $ka_data = Locale_site::where("locale", "=", 'ka')->first();
    //     $this->edit_local_data($request, $ka_data, 'ka');
    // }
    // public function edit_site_ru_data(Request $request){
    //     $ru_data = Locale_site::where("locale", "=", 'ru')->first();
    //     $this->edit_local_data($request, $ru_data, 'ru');
    // }
    // public function edit_site_us_data(Request $request){
    //     $us_data = Locale_site::where("locale", "=", 'us')->first();
    //     $this->edit_local_data($request, $us_data, 'us');
    // }

    // public function edit_local_data($request, $model, $locale)
    // {
    //     $model['guid_title']=$request['site_'.$locale.'_info']["guid_title"];
    //     $model['guid_description']=$request['site_'.$locale.'_info']["guid_description"];
    //     $model['guid_short_description']=$request['site_'.$locale.'_info']["guid_short_description"];
    //     $model['films_title']=$request['site_'.$locale.'_info']["films_title"];
    //     $model['films_description']=$request['site_'.$locale.'_info']["films_description"];
    //     $model['films_short_description']=$request['site_'.$locale.'_info']["films_short_description"];
    //     $model['forum_title']=$request['site_'.$locale.'_info']["forum_title"];
	// 	$model['forum_description']=$request['site_'.$locale.'_info']["forum_description"];
	// 	$model['forum_short_description']=$request['site_'.$locale.'_info']["forum_short_description"];
	// 	$model['shop_title']=$request['site_'.$locale.'_info']["shop_title"];
	// 	$model['shop_description']=$request['site_'.$locale.'_info']["shop_description"];
	// 	$model['shop_short_description']=$request['site_'.$locale.'_info']["shop_short_description"];
	// 	$model['other_activity_description']=$request['site_'.$locale.'_info']["other_activity_description"];
	// 	$model['mount_description']=$request['site_'.$locale.'_info']["mount_description"];
	// 	$model['event_description']=$request['site_'.$locale.'_info']["event_description"];
	// 	$model['tech_tips_description']=$request['site_'.$locale.'_info']["tech_tips_description"];
	// 	$model['news_description']=$request['site_'.$locale.'_info']["news_description"];
	// 	$model['index_gallery_description']=$request['site_'.$locale.'_info']["index_gallery_description"];
	// 	$model['outdoor_description']=$request['site_'.$locale.'_info']["outdoor_description"];
	// 	$model['indoor_description']=$request['site_'.$locale.'_info']["indoor_description"];
	// 	$model['ice_description']=$request['site_'.$locale.'_info']["ice_description"];
	// 	$model['topo_description']=$request['site_'.$locale.'_info']["topo_description"];
	// 	$model['what_we_do_description']=$request['site_'.$locale.'_info']["what_we_do_description"];
	// 	$model['products_description']=$request['site_'.$locale.'_info']["products_description"];
	// 	$model['services_description']=$request['site_'.$locale.'_info']["services_description"];
	// 	$model['terms_of_use']=$request['site_'.$locale.'_info']["terms_of_use"];

    //     $model->save();
    // }

    // public function edit_global_data($request, $model)
    // {
    //     $model['map'] = $request->site_global_info["map"];
    //     $model['email'] = $request->site_global_info["email"];
    //     $model['ad'] = $request->site_global_info["ad"];
    //     $model['number'] = $request->site_global_info["number"];

    //     $model->save();
    // }

    // private function validation_local_data()
    // {
    //     $validator = Validator::make($data, [
    //         'guid_title' => 'max:70',
    //         'films_title' => 'max:70',
    //         'forum_title' => 'max:70',
    //         'shop_title' => 'max:70',

    //         'guid_short_description' => 'max:190',
    //         'films_short_description' => 'max:190',
    //         'forum_short_description' => 'max:190',
    //         'shop_short_description' => 'max:190',
    //     ]);
    //     if ($validator->fails()) {
    //         return $validator->messages();
    //     }
    // }

    // private function validation_global_data()
    // {
    //     $validator = Validator::make($data, [
    //         'email' => 'required | string | email',
    //     ]);
    //     if ($validator->fails()) {
    //         return $validator->messages();
    //     }
    // }
}
