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
            return view('blog.index', compact('seo'));
        }
        abort(404);
    }
}
