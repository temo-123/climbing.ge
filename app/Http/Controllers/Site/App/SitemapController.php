<?php

namespace App\Http\Controllers\Site\App;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SitemapController extends Controller
{
    public function sitemap_xml()
    {
    	$indoors = Article::where('category', '=', 'indoor')->where('published', '=', 1)->get();
    	$outdoors = Article::where('category', '=', 'outdoor')->where('published', '=', 1)->get();
    	$ice_and_mix = Article::where('category', '=', 'ice')->where('published', '=', 1)->get();
    	$others = Article::where('category', '=', 'other')->where('published', '=', 1)->get();
    	$news = Article::where('category', '=', 'news')->where('published', '=', 1)->get();
    	$events = Article::where('category', '=', 'event')->where('published', '=', 1)->get();
    	$partners = Article::where('category', '=', 'partner')->where('published', '=', 1)->get();
    	$securitys = Article::where('category', '=', 'security')->where('published', '=', 1)->get();
    	$mountains = Mount_routes::get();
    	$products = Product::get();
    	return response()->view('sitemap/sitemap_xml', compact(
    		'indoors',
			'outdoors',
			'ice_and_mix',
			'others',
			'news',
			'events',
			'partners',
			'securitys',
			'mountains',
			'products',
    			))->header('Content-Type', 'text/xml');
    }
    public function sitemap()
    {   
        dd(Auth::user()->name);
    	// $posts = Post::orderBy('updated_at', 'DESC')->get();
    	$indoors =     Article::where('category', '=', 'indoor')->     where('published', '=', 1)->get();
    	$outdoors =    Article::where('category', '=', 'outdoor')->    where('published', '=', 1)->get();
    	$ices =        Article::where('category', '=', 'ice')->        where('published', '=', 1)->get();
    	$others =      Article::where('category', '=', 'other')->      where('published', '=', 1)->get();
    	$news =        Article::where('category', '=', 'news')->       where('published', '=', 1)->get();
    	$events =      Article::where('category', '=', 'event')->      where('published', '=', 1)->get();
    	$partners =    Article::where('category', '=', 'partner')->    where('published', '=', 1)->get();
    	$securitis =   Article::where('category', '=', 'security')->   where('published', '=', 1)->get();
    	$mountains =   Mount_routes::where('published', '=', 1)->get();
    	$products =    Product::where('approved', '=', 1)->get();
    	return response()->view('sitemap/sitemap', compact(
    		'indoors',
			'outdoors',
			'ices',
			'others',
			'news',
			'events',
			'partners',
			'securitis',
			'mountains',
			'products',
    			));
    }
}
