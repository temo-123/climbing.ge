<?php

namespace App\Http\Controllers\Site\App;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Article;
use App\Models\Mount_route;

use App\Services\GetArticlesService;

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
		$mountains = Article::where('category', '=', 'mount_route')->where('published', '=', 1)->get();
    	return response()->view('site/sitemap/sitemap_xml', compact(
    		'indoors',
			'outdoors',
			'ice_and_mix',
			'others',
			'news',
			'events',
			'partners',
			'securitys',
			'mountains',
    			))->header('Content-Type', 'text/xml');
    }

    public function sitemap()
    {   
		$global_news = Article::latest('id')->where('category', '=', 'news')->where('published', '=', 1)->get();
		$news = GetArticlesService::get_locale_article_for_sitemap($global_news, "ru");
		// dd($news);
    	// $news =        Article::where('category', '=', 'news')->       where('published', '=', 1)->get();
        // dd(Auth::user()->name);
    	// $posts = Post::orderBy('updated_at', 'DESC')->get();
    	$indoors =     Article::where('category', '=', 'indoor')->     where('published', '=', 1)->get();
    	$outdoors =    Article::where('category', '=', 'outdoor')->    where('published', '=', 1)->get();
    	$ices =        Article::where('category', '=', 'ice')->        where('published', '=', 1)->get();
    	$others =      Article::where('category', '=', 'other')->      where('published', '=', 1)->get();
    	$events =      Article::where('category', '=', 'event')->      where('published', '=', 1)->get();
    	$partners =    Article::where('category', '=', 'partner')->    where('published', '=', 1)->get();
    	$securitis =   Article::where('category', '=', 'security')->   where('published', '=', 1)->get();
		// $mountains =   Mount_route::where('published', '=', 1)->get();
		$data = [
			'indoors'=>$indoors,
			'outdoors'=>$outdoors,
			'ices'=>$ices,
			'others'=>$others,
			'news'=>$news,
			'events'=>$events,
			'partners'=>$partners,
			'securitis'=>$securitis,
			'mountains' => [],
		];

    	return response()->view('site/sitemap/sitemap', $data);
    }

	public function robots()
	{
		echo(
			'
				Disallow: /search
				Sitemap: http://climbing.loc/sitemap.xml
			'
		);
	}
}
