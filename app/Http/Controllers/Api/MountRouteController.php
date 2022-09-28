<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Article;
use App\Models\Mount;
use App\Services\GetMountSystemService;

class MountRouteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $global_mount_routes = Article::latest('id')->where('category', '=', 'mount_route')->where('published', '=', 1)->get();
        // $mount_routes = GetMountSystemService::get_locale_article($global_mount_routes);

        // return $mount_routes;
    }

    
    public function get_filtred_mount_route_for_user(Request $request)
    {
        $mount_article_count = Mount::where('id', '=', $request->filter_id)->count();
        if($mount_article_count > 0){
            $filtred_articles_by_mount = Mount::where('id', '=', $request->filter_id)->first()->articles;
            return $filtred_articles_by_mount;
        }
    }

    public function get_filtred_mount_route_for_admin(Request $request)
    {
        $mount_article_count = Mount::where('id', '=', $request->filter_id)->count();
        if($mount_article_count > 0){
            $filtred_articles_by_mount = Mount::where('id', '=', $request->filter_id)->first()->articles;
            return $filtred_articles_by_mount;
        }
    }
}
