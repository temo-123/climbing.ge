<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\SeoService;

class IndexController extends Controller
{
    public function index(Request $request)
    {
        if (view()->exists('blog.index')) {
            $seo = (new SeoService())->forBlog($request);
            // a missing article / product / … answers 404 (not a soft 404); the SPA shows its 404 page
            return response()->view('blog.index', compact('seo'), $seo['status'] ?? 200);
        }
        abort(404);
    }
}
