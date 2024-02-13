<?php

namespace App\Http\Controllers\Api\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Article;
use App\Models\Mount;
use App\Services\GetMountSystemService;
use App\Services\ImageControllService;
use App\Models\Mount_route_image;
use App\Services\ArticlesService;

class MountRouteController extends Controller
{   
    public function get_filtred_mount_route_for_user(Request $request)
    {
        $mount_article_count = Mount::where('id', '=', $request->filter_id)->count();
        // $articles = [];
        if($mount_article_count > 0){
            $filtred_articles_by_mount = Mount::where('id', '=', $request->filter_id)->first()->articles;
            $articles = ArticlesService::get_locale_article_use_locale($filtred_articles_by_mount, $request->lang);
            // return $filtred_articles_by_mount;
        }
        return $articles;

        // dd($request->lang);

        // $article_count = Article::where('category', '=', 'mount_route')->where('published', '=', 1)->count();
        // $articles = [];
        // if($article_count > 0){
        //     $global_articles = Article::latest('id')->where('category', '=', 'mount_route')->where('published', '=', 1)->get();
        //     $articles = ArticlesService::get_locale_article_use_locale($global_articles, $lang);
        // }
        // // dd($articles);
        // return $articles;
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
