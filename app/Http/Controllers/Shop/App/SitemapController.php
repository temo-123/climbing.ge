<?php

namespace App\Http\Controllers\Shop\App;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Product;

class SitemapController extends Controller
{
    public function sitemap_xml()
    {
        $products = Product::where('published', '=', 1)->get();
        // dd($products);
    	return response()->view('shop/sitemap/sitemap_xml', compact(
    		'products',
    			))->header('Content-Type', 'text/xml');
    }
}
