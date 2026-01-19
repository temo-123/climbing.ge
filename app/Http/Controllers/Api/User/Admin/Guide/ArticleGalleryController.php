<?php

namespace App\Http\Controllers\Api\User\Admin\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Abstract\ImageControllService;
use App\Services\PermissionService;

use App\Models\Guide\Article_image;
use App\Models\Guide\Article;

use Validator;

class ArticleGalleryController extends Controller
{
    public function get_editing_images(Request $request)
    {
        $auth = PermissionService::authorize('article', 'edit');
        if ($auth) return $auth;
        return Article_image::where('article_id', '=', $request->article_id)->get();
    }

    public function del_image($id)
    {
        $auth = PermissionService::authorize('article', 'del');
        if ($auth) return $auth;
        $gallery = Article_image::where('id',strip_tags($id))->first();

        // delete article file
        ImageControllService::image_delete('images/article_gallery_img/', $gallery, 'image');

        // delete product from db
        $gallery -> delete();
    }
}
