<?php

namespace App\Services;

use App\Models\Us_article;
use App\Models\Ru_article;
use App\Models\Ka_article;

use Carbon\Carbon;

class GetArticlesService
{
    public static function get_locale_article($global_article)
    {
        $locale = request()->segment(1, '');

        $articles = array();

        if($locale == "ru"){
            foreach ($global_article as $article) {
                $ru_articles = Ru_article::where('id', '=', $article->ru_article_id,)->get();
                foreach ($ru_articles as $us_article) {
                    if ($us_article->id == $article->ru_article_id) {
                        array_push($articles, [$ru_articles,    "url_title"=>$article->url_title, 
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
                                                                ]);
                    }
                }
            }
        }
        elseif ($locale == "ka") {
            foreach ($global_article as $article) {
                $ka_articles = Ka_article::where('id', '=', $article->ka_article_id,)->get();
                foreach ($ka_articles as $us_article) {
                    if ($us_article->id == $article->ka_article_id) {
                        array_push($articles, [$ka_articles,    "url_title"=>$article->url_title, 
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
                                                                ]);
                    }
                }
            }
        } else {
            foreach ($global_article as $article) {
                $us_articles = Us_article::where('id', '=', $article->us_article_id)->get();
                foreach ($us_articles as $us_article) {
                    if ($us_article->id == $article->us_article_id) {
                        array_push($articles, [$us_articles,    "url_title"=>$article->url_title, 
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
                                                                ]);
                    }
                }
            }
        }
        // dd($articles);
        return $articles;
    }

    public static function get_locale_article_for_sitemap($global_article, $locale)
    {
        $articles = array();

        if($locale == "ru"){
            foreach ($global_article as $article) {
                $ru_articles = Ru_article::where('id', '=', $article->ru_article_id,)->get();
                foreach ($ru_articles as $us_article) {
                    if ($us_article->id == $article->ru_article_id) {
                        array_push($articles, [$ru_articles,    "url_title"=>$article->url_title, 
                                                                "image" => $article->image,
                                                                ]);
                    }
                }
            }
        }
        elseif ($locale == "ka") {
            foreach ($global_article as $article) {
                $ka_articles = Ka_article::where('id', '=', $article->ka_article_id,)->get();
                foreach ($ka_articles as $us_article) {
                    if ($us_article->id == $article->ka_article_id) {
                        array_push($articles, [$ka_articles,    "url_title"=>$article->url_title, 
                                                                "image" => $article->image,
                                                                ]);
                    }
                }
            }
        } else {
            foreach ($global_article as $article) {
                $us_articles = Us_article::where('id', '=', $article->us_article_id,)->get();
                foreach ($us_articles as $us_article) {
                    if ($us_article->id == $article->us_article_id) {
                        array_push($articles, [$us_articles,    "url_title"=>$article->url_title, 
                                                                "image" => $article->image,
                                                                ]);
                    }
                }
            }
        }
        return $articles;
    }


    public static function get_locale_article_in_page($article)
    {
        $locale = request()->segment(1, '');

        if($locale == "ru"){
            $ru_article_id = $article->ru_article_id;
            $loc_article = Ru_article::where('id', '=', $ru_article_id,)->get();$locale_article = array();
            array_push($locale_article, [$loc_article[0], 
                                                "id"=>$article->id, 
                                                "mount_id" => $article->mount_id,
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
                                                ]);
        }
        elseif ($locale == "ka") {
            $ka_article_id = $article->ka_article_id;
            $loc_article = Ka_article::where('id', '=', $ka_article_id,)->get();$locale_article = array();
            array_push($locale_article, [$loc_article[0], 
                                                "id"=>$article->id, 
                                                "mount_id" => $article->mount_id,
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
                                                ]);
                
        } else {
            $us_article_id = $article->us_article_id;
            $loc_article = Us_article::where('id', '=', $us_article_id,)->get();
            $locale_article = array();
            array_push($locale_article, [$loc_article[0], 
                                                "id"=>$article->id, 
                                                "mount_id" => $article->mount_id,
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
                                                ]);
            // dd($locale_article[0][0]['title']);
        }

        return $locale_article;
    }


    public static function get_new_article_pin($global_article)
    {
        $time_array = array();

        foreach ($global_article as $article) {
            if ($article[0][0]->created_at->lt(Carbon::now()->subDays(30))){
                $time = 0;
                array_push($time_array, ['id'=>$article[0][0]->id, 'name'=>$article['url_title'], 'time'=>$time]);
            } else {
                $time = 1;
                array_push($time_array, ['id'=>$article[0][0]->id, 'name'=>$article['url_title'], 'time'=>$time]);
            }
        }
        
        return $time_array;
    }
}