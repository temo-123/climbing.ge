<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\SeoService;

class IndexController extends Controller
{
    public function index(Request $request)
    {
        if (view()->exists('site.index')) {
            $seo = (new SeoService())->forSite($request);
            return view('site.index', compact('seo'));
        }
        abort(404);
    }
}
