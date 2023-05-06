<?php

namespace App\Services;

use App\Models\Locale_article;
use App\Models\General_info;
use App\Models\General_info_article;
use App\Models\Mount;

use Carbon\Carbon;

class GetArticlesService
{
    public static function get_locale_article_use_locale($global_article, $locale = 'en')
    {
        $articles = array();

        if($locale == "ru"){
            $articles = (new static)->get_locale_article($global_article, 'ru');
        }
        elseif ($locale == "ka") {
            $articles = (new static)->get_locale_article($global_article, 'ka');
        } 
        else {
            $articles = (new static)->get_locale_article($global_article, );
        }

        // dd($articles);
        return $articles;
    }

    public static function get_locale_article($global_article, $locale = 'us', )
    {
        $response = array();

        foreach ($global_article as $article) {
            
            $localzatione = $locale.'_article_id';
            $locale_articles = Locale_article::where('id', '=', $article->$localzatione)->get();
            
            foreach ($locale_articles as $us_article) {
                if ($us_article->id == $article->$localzatione) {
                    $new_article = false;

                    $new_flag = (new static)->get_new_article_pin($article);

                    $mount_system = '';
                    if(count($article->mount_masiv) > 0){
                        $m_system = $article->mount_masiv;
                    }
                    else{
                        $mount_system = '';
                    }

                    array_push($response, [$locale_articles,    "url_title"=>$article->url_title, 
                                                            "id" => $article->id,
                                                            "mount_id" => $article->mount_id,
                                                            "category" => $article->category,
                                                            "climbing_area_image" => $article->climbing_area_image,
                                                            "image" => $article->image,
                                                            "closed" => $article->closed,
                                                            "price_from" => $article->price_from,
                                                            "open_time" => $article->open_time,
                                                            "closed_time"=>$article->closed_time,
                                                            "start_data" => $article->start_data,
                                                            "region_id"=>$article->region_id,
                                                            "end_data" => $article->end_data,
                                                            "published" => $article->published,

                                                            "new_flag"=>$new_flag,

                                                            "mount_system" => $mount_system,
                                                            ]);
                }
            }
        }

        return $response;
    }

    public function get_list_array($global_article, $local_article)
    {
        $new_article = false;

        $new_flag = (new static)->get_new_article_pin($article);

        $mount_system = '';

        if(count($article->mount_masiv) > 0){
            $masiv_id = $article->mount_masiv[0]->mount_id;
            $m_system = Mount::where('id', '=', $masiv_id)->first();
            $m = $m_system->us_mount;

            $mount_system = $m->title;
        }
        else{
            $mount_system = '';
        }

        // dd($article);
        // array_push($articles, [$us_articles,  $article[0]]);
        $articles = [$us_articles,    "url_title"=>$article->url_title, 
                                                "id" => $article->id,
                                                "mount_id" => $article->mount_id,
                                                "category" => $article->category,
                                                "climbing_area_image" => $article->climbing_area_image,
                                                "image" => $article->image,
                                                "closed" => $article->closed,
                                                "price_from" => $article->price_from,
                                                "open_time" => $article->open_time,
                                                "closed_time"=>$article->closed_time,
                                                "start_data" => $article->start_data,
                                                "region_id"=>$article->region_id,
                                                "end_data" => $article->end_data,

                                                "new_flag"=>$new_flag,

                                                "mount_system" => $mount_system,
                                                ];
        return $articles;
    }


    public static function get_new_article_pin($article){
        // dd($article);
        $time = false;
        if($article->created_at){
            if ($article->created_at->lt(Carbon::now()->subDays(30))){
                $time = false;
            } else {
                $time = true;
            }
            return $time;
        }
    }


    public static function get_locale_article_in_page($article, $locale = 'en')
    {

        if($locale == "ru"){
            $ru_article_id = $article->ru_article_id;
            $loc_article = Locale_article::where('id', '=', $ru_article_id,)->first();
            
            return (new static)->create_page_arr($loc_article, $article);
        }
        elseif ($locale == "ka") {
            $ka_article_id = $article->ka_article_id;
            $loc_article = Locale_article::where('id', '=', $ka_article_id,)->first();
            
            return (new static)->create_page_arr($loc_article, $article);
        } else {
            $us_article_id = $article->us_article_id;
            $loc_article = Locale_article::where('id', '=', $us_article_id,)->first();
            
            return (new static)->create_page_arr($loc_article, $article);
        }

        // return $locale_article;
    }


    public function create_page_arr($loc_article, $article)
    {
        $locale_article = array();

        // dd($article->general_info);

        array_push($locale_article, 
            [
                $loc_article, 

                "id"=>$article->id,
                "category" => $article->category,
                "start_data"=>$article->start_data, 
                "end_data"=>$article->end_data,
                "fb_link"=>$article->fb_link, 
                "inst_link"=>$article->inst_link, 
                "google_link"=>$article->google_link, 
                "twit_link"=>$article->twit_link, 
                "web_link"=>$article->web_link, 
                "star"=>$article->star, 
                "weather"=>$article->weather, 
                "price_from"=>$article->price_from, 
                "open_timen"=>$article->open_time, 
                "closed_time"=>$article->closed_time,
                "region_id"=>$article->region_id,
                "map"=>$article->map,
                "climbing_area_image"=>$article->climbing_area_image,
                "mount_route_image"=>$article->mount_route_image,
                "image"=>$article->image, 
                
                "global_info"=>[
                    "info_block" => [],
                    "what_need_info" => [],
                    "best_time" => [],
                    "routes_info" => [],
                ]
            ]
        );
        
        foreach($article->general_info as $info){
            // dd($info);
            $info_article_relatione = General_info_article::where('article_id', '=', $article->id)->where('info_id', '=', $info->id)->first();

            if($info_article_relatione->block == 'info_block'){
                $locale_article[0]['global_info']['info_block'] = [
                    "block_action" => $info_article_relatione->block_action,
                    "text"=>General_info::where('id', '=', $info_article_relatione->info_id)->first('text_us')->text_us
                ];
            }
            if($info_article_relatione->block == 'what_need_info'){
                $locale_article[0]['global_info']['what_need_info'] = [
                    "block_action" => $info_article_relatione->block_action,
                    "text"=>General_info::where('id', '=', $info_article_relatione->info_id)->first('text_us')->text_us
                ];
            }
            if($info_article_relatione->block == 'best_time'){
                $locale_article[0]['global_info']['best_time'] = [
                    "block_action" => $info_article_relatione->block_action,
                    "text"=>General_info::where('id', '=', $info_article_relatione->info_id)->first('text_us')->text_us
                ];
            }
            if($info_article_relatione->block == 'routes_info'){
                $locale_article[0]['global_info']['routes_info'] = [
                    "block_action" => $info_article_relatione->block_action,
                    "text"=>General_info::where('id', '=', $info_article_relatione->info_id)->first('text_us')->text_us
                ];
            }
        }
        // dd($locale_article);
        return $locale_article;
    }
}