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

    // public function FunctionName($model)
    // {
    //     $model['guid_title'];
    //     $model['guid_description'];
    //     $model['guid_short_description'];
    //     $model['films_title'];
    //     $model['films_description'];
    //     $model['films_short_description'];
    //     $model['forum_title'];
	// 	$model['forum_description'];
	// 	$model['forum_short_description'];
	// 	$model['shop_title'];
	// 	$model['shop_description'];
	// 	$model['shop_short_description'];
	// 	$model['other_activity_description'];
	// 	$model['mount_description'];
	// 	$model['event_description'];
	// 	$model['tech_tips_description'];
	// 	$model['news_description'];
	// 	$model['index_gallery_description'];
	// 	$model['outdoor_description'];
	// 	$model['indoor_description'];
	// 	$model['ice_description'];
	// 	$model['topo_description'];
	// 	$model['what_we_do_description'];
	// 	$model['products_description'];
	// 	$model['services_description'];
    // }

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


    // public function get_site_locale_data(Request $request)
    // {
    //     $data = [
    //         'site_global_data' => Site::first()
    //     ];
    //     if($request->locale == 'ka') {
    //         $data = [
    //             'site_global_data' => Locale_site::where("locale", "=", 'ka')->first()
    //         ];
    //     }
    //     if($request->locale == 'ru') {
    //         $data = [
    //             'site_global_data' => Locale_site::where("locale", "=", 'ru')->first()
    //         ];
    //     }
    //     if($request->locale == 'us') {
    //         $data = [
    //             'site_global_data' => Locale_site::where("locale", "=", 'us')->first()
    //         ];
    //     }

    //     return $data;
    // }

    public function get_site_locale_data(Request $request)
    {
        $site_global_data = Site::first();

        $local_data = [];
        
        if($request->locale == 'ka'){
            $local_data = $site_global_data->ka_site;
        }
        else if($request->locale == 'ru'){
            $local_data = $site_global_data->ru_site;
        }
        else{
            $local_data = $site_global_data->us_site;
        }

        $data = [
            'locale_data' => $local_data, 
            'global_data'=>$site_global_data
        ];
        // array_push($data[0], $site_global_data);
        // dd($data);

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

    public function edit_site_data(Request $request)
    {
        $global_data = Site::first();
        $ka_data = Locale_site::where("locale", "=", 'ka')->first();
        $ru_data = Locale_site::where("locale", "=", 'ru')->first();
        $us_data = Locale_site::where("locale", "=", 'us')->first();

        $this->edit_global_data($request, $global_data);
        $this->edit_local_data($request, $ka_data, 'ka');
        $this->edit_local_data($request, $ru_data, 'ru');
        $this->edit_local_data($request, $us_data, 'us');
    }

    public function edit_site_global_data(Request $request){
        $global_data = Site::first();
        $this->edit_global_data($request, $global_data);
    }
    public function edit_site_ka_data(Request $request){
        $ka_data = Locale_site::where("locale", "=", 'ka')->first();
        $this->edit_local_data($request, $ka_data, 'ka');
    }
    public function edit_site_ru_data(Request $request){
        $ru_data = Locale_site::where("locale", "=", 'ru')->first();
        $this->edit_local_data($request, $ru_data, 'ru');
    }
    public function edit_site_us_data(Request $request){
        $us_data = Locale_site::where("locale", "=", 'us')->first();
        $this->edit_local_data($request, $us_data, 'us');
    }

    public function edit_local_data($request, $model, $locale)
    {
        // dd($request['site_'.$locale.'_info']);
        $model['guid_title']=$request['site_'.$locale.'_info']["guid_title"];
        $model['guid_description']=$request['site_'.$locale.'_info']["guid_description"];
        $model['guid_short_description']=$request['site_'.$locale.'_info']["guid_short_description"];
        $model['films_title']=$request['site_'.$locale.'_info']["films_title"];
        $model['films_description']=$request['site_'.$locale.'_info']["films_description"];
        $model['films_short_description']=$request['site_'.$locale.'_info']["films_short_description"];
        $model['forum_title']=$request['site_'.$locale.'_info']["forum_title"];
		$model['forum_description']=$request['site_'.$locale.'_info']["forum_description"];
		$model['forum_short_description']=$request['site_'.$locale.'_info']["forum_short_description"];
		$model['shop_title']=$request['site_'.$locale.'_info']["shop_title"];
		$model['shop_description']=$request['site_'.$locale.'_info']["shop_description"];
		$model['shop_short_description']=$request['site_'.$locale.'_info']["shop_short_description"];
		$model['other_activity_description']=$request['site_'.$locale.'_info']["other_activity_description"];
		$model['mount_description']=$request['site_'.$locale.'_info']["mount_description"];
		$model['event_description']=$request['site_'.$locale.'_info']["event_description"];
		$model['tech_tips_description']=$request['site_'.$locale.'_info']["tech_tips_description"];
		$model['news_description']=$request['site_'.$locale.'_info']["news_description"];
		$model['index_gallery_description']=$request['site_'.$locale.'_info']["index_gallery_description"];
		$model['outdoor_description']=$request['site_'.$locale.'_info']["outdoor_description"];
		$model['indoor_description']=$request['site_'.$locale.'_info']["indoor_description"];
		$model['ice_description']=$request['site_'.$locale.'_info']["ice_description"];
		$model['topo_description']=$request['site_'.$locale.'_info']["topo_description"];
		$model['what_we_do_description']=$request['site_'.$locale.'_info']["what_we_do_description"];
		$model['products_description']=$request['site_'.$locale.'_info']["products_description"];
		$model['services_description']=$request['site_'.$locale.'_info']["services_description"];

        // dd($request['site_'.$locale.'_info']);

        $model->save();
    }

    public function edit_global_data($request, $model)
    {
        $model['map'] = $request->site_global_info["map"];
        $model['email'] = $request->site_global_info["email"];
        $model['number'] = $request->site_global_info["number"];

        $model->save();
    }
}
