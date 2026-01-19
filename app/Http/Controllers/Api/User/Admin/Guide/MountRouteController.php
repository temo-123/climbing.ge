<?php

namespace App\Http\Controllers\Api\User\Admin\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Guide\Article;
use App\Models\Guide\Mount;
use App\Models\Guide\Mount_route_image;

use App\Services\ArticlesService;
use App\Services\MountSystemService;
use App\Services\Abstract\ImageControllService;
use App\Services\PermissionService;

class MountRouteController extends Controller
{
    public function get_filtred_mount_route_for_admin(Request $request)
    {
        $auth = PermissionService::authorize('mount_route', 'edit');
        // if ($auth) return $auth;
        
        $mount_article_count = Mount::where('id', '=', $request->filter_id)->count();
        if($mount_article_count > 0){
            $filtred_articles_by_mount = Mount::where('id', '=', $request->filter_id)->first()->articles;
            return $filtred_articles_by_mount;
        }
    }

    public function get_mount_routes_images(Request $request)
    {
        $auth = PermissionService::authorize('mount_route', 'edit');
        // if ($auth) return $auth;
        
        return Mount_route_image::where('article_id', '=', $request->article_id)->get();
    }

    public function del_mount_route_image(Request $request)
    {
        $auth = PermissionService::authorize('mount_route', 'del');
        // if ($auth) return $auth;
        
        $image = Mount_route_image::where('id', '=', $request->image_id)->first();
        ImageControllService::image_delete('images/mount_route_description_img/', $image, 'image');
        $image ->delete();
    }
}
