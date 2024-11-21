<?php

namespace App\Http\Controllers\Api\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Guide\Article;
use App\Models\Guide\Mount;
use App\Models\Guide\Mount_route_image;

use App\Services\ArticlesService;
use App\Services\MountSystemService;
use App\Services\Abstract\ImageControllService;

class MountRouteController extends Controller
{   

    public function get_mount_routes_by_maunt(Request $request)
    {
        $mounts_array = [];
        $mount_systems = MountSystemService::get_local_mounts_use_locale(Mount::latest('id')->get(), $request->lang);

        foreach($mount_systems as $mount){
            $global_mount_routes = $mount['global_data']->articles->where('published', '=', '1');
            if($global_mount_routes->count() > 0){
                array_push($mounts_array, ['mount' => $mount, 'mount_route' =>  ArticlesService::get_locale_article_use_locale($global_mount_routes, $request->lang)]);
            }
        }

        $all_mount_routes = json_decode(json_encode(Article::where('category', '=', 'mount_route')->where('published', '=', '1')->get()), true);
        $route_array = [];
        // dd($mounts_array);

        foreach ($mounts_array as $mount) {
            foreach ($mount['mount_route'] as $route) {
                array_push($route_array, $route);

                $route_array = json_decode(json_encode($route_array), true);
            }
        }
        
        $results = array_diff(
            array_column($all_mount_routes, 'id'), 
            array_column(
                array_column($route_array, 'global_data'), 'id'
            )
        );
        

        $other_articles = [];
        foreach($results as $result){
            $other_article = Article::where('id', '=', $result)->where('published', '=', '1')->get();
            
            if($other_article->count() > 0){
                $art = ArticlesService::get_locale_article_use_locale($other_article, $request->lang);
                array_push($other_articles, $art[0]);
            }
        }
        if(count($other_articles)){
            array_push($mounts_array, ['mount_route' => $other_articles]);
        }

        // dd($mounts_array);

        return $mounts_array;
    }

    public function get_filtred_mount_route_for_user(Request $request)
    {
        $mount_article_count = Mount::where('id', '=', $request->filter_id)->count();
        
        if($mount_article_count > 0){
            $filtred_articles_by_mount = Mount::where('id', '=', $request->filter_id)->first()->articles;
            $articles = ArticlesService::get_locale_article_use_locale($filtred_articles_by_mount, $request->lang);
        }
        return $articles;
    }

    public function get_filtred_mount_route_for_admin(Request $request)
    {
        $mount_article_count = Mount::where('id', '=', $request->filter_id)->count();
        if($mount_article_count > 0){
            $filtred_articles_by_mount = Mount::where('id', '=', $request->filter_id)->first()->articles;
            return $filtred_articles_by_mount;
        }
    }

    public function get_mount_routes_images(Request $request)
    {
        return Mount_route_image::where('article_id', '=', $request->article_id)->get();
    }

    public function del_mount_route_image(Request $request)
    {   
        $image = Mount_route_image::where('id', '=', $request->image_id)->first();
        ImageControllService::image_delete('images/mount_route_description_img/', $image, 'image');
        $image ->delete();
    }
}
