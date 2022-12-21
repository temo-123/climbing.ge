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

    public function get_mount_routes_images(Request $request)
    {
        $bisnes = Suport_local_bisnes::where('id', '=', $request->bisnes_id)->first();
        $bisnes_images_count = Suport_local_bisnes_image::where('bisnes_id', '=', $bisnes->id)->count();

        if($bisnes_images_count > 0){
            $bisnes_images = Suport_local_bisnes_image::where('bisnes_id', '=', $bisnes->id)->get();
            // dd($bisnes_images);
            foreach ($bisnes_images as $image) {
                ImageControllService::image_delete('images/mount_route_img/', $image, 'image');
                $image ->delete();
            }
        }
        $bisnes ->delete();
    }

    public function del_mount_routes_images(Request $request)
    {   $image = Suport_local_bisnes_image::where('id', '=', $request->image_id)->first();
        ImageControllService::image_delete('images/mount_route_img/', $image, 'image');
        $image ->delete();
    }
}
