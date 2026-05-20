<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\SeoService;

class IndexController extends Controller
{
    public function index(Request $request)
    {
        if (view()->exists('shop.index')) {
            $seo = (new SeoService())->forShop($request);
            return view('shop.index', compact('seo'));
        }
        abort(404);
    }
}
