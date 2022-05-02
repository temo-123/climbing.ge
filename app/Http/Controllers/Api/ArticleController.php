<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Article;
use App\Models\Ka_article;
use App\Models\Us_article;
use App\Models\Ru_article;
use App\Services\GetArticlesService;
use App\Models\Comment;
use App\Services\ImageEditService;
use App\Services\ImageControllService;

use App\Services\URLTitleService;

use File;
use Validator;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Article::get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        dd('$request');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return Article::latest('id')->where('category', '=', $request->category)->get();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($url_title)
    {
        // dd($url_title);
        $global_news = Article::where('url_title',strip_tags($url_title))->first();
        $news = GetArticlesService::get_locale_article_in_page($global_news);

        return $news[0];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        dd($id);
        if ($request -> isMethod('post')) {
            dd('post');
        } else {
            dd('get');
        }
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $globa_validate = $this->addGlobamArticle($request->data['global_data']);
        $ka_validate = $this->add_ka_article($request->data['ka_data']);
        $ru_validate = $this->add_ru_article($request->data['ru_data']);
        $us_validate = $this->add_us_article($request->data['us_data']);

        if ($globa_validate || $ka_validate || $ru_validate || $us_validate) {
            $errors = [ 'global_data' => $globa_validate, 
                        'ka_data' => $ka_validate, 
                        'ru_data' => $ru_validate, 
                        'us_data' => $us_validate
                    ];
            return response()->json($errors, 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // dd($id);

		// if ($request->isMethod('post')) {

            $global_id=$id;

            $global_article = Article::where('id',strip_tags($global_id))->first();
            $us_article = Us_article::where('id',strip_tags($global_article->us_article_id))->first();
            $ru_article = Ru_article::where('id',strip_tags($global_article->ru_article_id))->first();
            $ka_article = Ka_article::where('id',strip_tags($global_article->ka_article_id))->first();
            
            // delete article file
            ImageControllService::image_delete('images/'.$global_article->category.'_img/', $global_article, 'image');
            ImageControllService::image_delete('images/region_sectors_img/', $global_article, 'climbing_area_image');

            // delete article from db
            $global_article ->delete();
            $us_article ->delete();
            $ru_article ->delete();
            $ka_article ->delete();
    }








    public function addGlobamArticle($global_data)
    {
        // if ($request -> isMethod('post')) {
            // $input = $request -> except('_token');
            $validate = $this->global_article_validate($global_data);
            if ($validate != null) {
                return $validate;
            }

            $global_article = Article::get();
            foreach ($global_article as $global) {
                $last_globale_id = $global->id;
            }

            // make url_title from us_title value
            $url_title = URLTitleService::get_url_title($global_data["us_title_for_url_title"]);

            $article = Article::find($last_globale_id);
            
            $article['url_title'] = $url_title;

            $article['category']=$global_data["category"];
            // $article['mount_id']=$global_data["mount_id"];
            $article['published']=$global_data["published"];
            $article['completed']=$global_data["completed"];
            $article['map']=$global_data["map"];
            $article['weather']=$global_data["weather"];

            $article['start_data']=$global_data["start_data"];
            $article['end_data']=$global_data["end_data"];

            $article['open_timen'] = $global_data["open_timen"];
            $article['closed_time'] = $global_data["closed_time"];

            $article['price_from'] = $global_data["price_from"];

            $article['fb_link']=$global_data["fb_link"];
            $article['twit_link']=$global_data["twit_link"];
            $article['google_link']=$global_data["google_link"];
            $article['inst_link']=$global_data["inst_link"];
            $article['web_link']=$global_data["web_link"];
            
            $article -> update();
        // }
    }
    public function add_ka_article($ka_data)
    {
        // $request->user()->authorizeRoles(['manager', 'admin']);
        // if ($request -> isMethod('post')) {
            $validate = $this->ka_sector_validate($ka_data);
            if ($validate != null) {
                return( $validate);
            }

            $ka_articl = Ka_article::get();
            foreach ($ka_articl as $ka) {
                $last_ka_article_id = $ka->id;
            }

            $article = Ka_article::find($last_ka_article_id);
            
            $article['title']=$ka_data["ka_title"];
            $article['short_description']=$ka_data["ka_short_description"];
            $article['text']=$ka_data["ka_text"];
            $article['route']=$ka_data["ka_route"];
            $article['how_get']=$ka_data["ka_how_get"];
            $article['best_time']=$ka_data["ka_best_time"];
            $article['what_need']=$ka_data["ka_what_need"];
            $article['info']=$ka_data["ka_info"];
            $article['meta_keyword']=$ka_data["ka_meta_keyword"];

            $article -> update();
        // }
    }
    public function add_ru_article($ru_data)
    {
        // $request->user()->authorizeRoles(['manager', 'admin']);
        // if ($request -> isMethod('post')) {
            $validate = $this->ru_sector_validate($ru_data);
            if ($validate != null) {
                return $validate;
            }

            $ru_articl = Ru_article::get();
            foreach ($ru_articl as $ru) {
                $last_ru_article_id = $ru->id;
            }

            $article = Ru_article::find($last_ru_article_id);

            $article['title']=$ru_data["ru_title"];
            $article['short_description']=$ru_data["ru_short_description"];
            $article['text']=$ru_data["ru_text"];
            $article['route']=$ru_data["ru_route"];
            $article['how_get']=$ru_data["ru_how_get"];
            $article['best_time']=$ru_data["ru_best_time"];
            $article['what_need']=$ru_data["ru_what_need"];
            $article['info']=$ru_data["ru_info"];
            $article['meta_keyword']=$ru_data["ru_meta_keyword"];
            
            $article -> update();
        // }
    }
    public function add_us_article($us_data)
    {
        // $request->user()->authorizeRoles(['manager', 'admin']);
        // if ($request -> isMethod('post')) {
            $validate = $this->us_sector_validate($us_data);
            if ($validate != null) {
                return $validate;
            }

            $us_articl = Us_article::get();
            foreach ($us_articl as $us) {
                $last_us_article_id = $us->id;
            }

            $article = Us_article::find($last_us_article_id);
            // $article = new Us_article();

            $article['title']=$us_data["us_title"];
            $article['short_description']=$us_data["us_short_description"];
            $article['text']=$us_data["us_text"];
            $article['route']=$us_data["us_route"];
            $article['how_get']=$us_data["us_how_get"];
            $article['best_time']=$us_data["us_best_time"];
            $article['what_need']=$us_data["us_what_need"];
            $article['info']=$us_data["us_info"];
            $article['meta_keyword']=$us_data["us_meta_keyword"];

            $article -> update();
        // }
    }


    public function global_article_validate($global_data)
    {
        $validator = Validator::make($global_data, [
            'published' => 'required',
        ]);
        if ($validator->fails()) {
            return $validator->messages();
        }
    }

    public function ka_sector_validate($ka_data)
    {
        $validator = Validator::make($ka_data, [
            'ka_title' => 'required',
            'ka_short_description' => 'required',
            'ka_text' => 'required',
            'ka_info' => 'required',
        ]);
        if ($validator->fails()) {
            return $validator->messages();
        }
        // dd($validator->messages());
    }

    public function ru_sector_validate($ru_data)
    {
        $validator = Validator::make($ru_data, [
            'ru_title' => 'required',
            'ru_short_description' => 'required',
            'ru_text' => 'required',
            'ru_info' => 'required',
        ]);
        if ($validator->fails()) {
            return $validator->messages();
        }
    }

    public function us_sector_validate($us_data)
    {
        $validator = Validator::make($us_data, [
            'us_title' => 'required',
            'us_short_description' => 'required',
            'us_text' => 'required',
            'us_info' => 'required',
        ]);
        if ($validator->fails()) {
            return $validator->messages();
        }
    }
}
