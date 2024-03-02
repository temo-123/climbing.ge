<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Locale_article;
use App\Models\General_info;
use App\Models\General_info_article;
use App\Models\Mount;

use Carbon\Carbon;
use App\Services\Abstract\LocaleContentService;
use App\Services\GeneralInfoService;
use App\Services\MountSystemService;

class ArticlesService extends LocaleContentService
{
    public static function get_locale_article_use_locale($global_article, $locale='en'){
        $localed_articles = (new static)->get_locale_content_use_locale($global_article, Locale_article::class, '_article_id', $locale);

        return $articles = (new static)->get_article_additional_content($localed_articles, $locale);
        // return $articles;
    }

    private static function get_article_additional_content($localed_articles, $locale = 'en') {
        $new_arr = [];
        $looped = 0;
        foreach ($localed_articles as $article) {
            if($article['global_data']['category'] == "mount_route"){
                $looped++;
                $act_article = Article::where('id', '=', $article['global_data']['id'])->first();

                $m_system = $act_article->mount_masiv;

                $local_mount = MountSystemService::get_local_mounts_use_locale($m_system, $locale);
                
                array_push($new_arr, [
                    "locale_data"=>$article['locale_data'], 
                    "global_data"=>$article['global_data'], 
                    'mount_masive'=>$local_mount[0]['locale_data']['title']
                ]);
            }
        }

        if($looped > 0){
            return $new_arr;
        }
        else{
            return $localed_articles;
        }
    }

    public static function get_locale_article_in_page($article, $locale = 'en')
    {
        $locale_article = (new static)->get_locale_content_in_page($article, Locale_article::class, '_article_id', $locale);

        $general_data = GeneralInfoService::getGeneralInfoForArticle($article);
        
        $art_array = [
            "locale_data"=>$locale_article['locale_data'], 
            "global_data"=>$locale_article['global_data'], 

            "gallery_images"=>$article->gallery_images, 

            "general_info"=>[
                "info_block" => [],
                "what_need_info" => [],
                "best_time" => [],
                "routes_info" => [],
            ]
        ];

        if(isset($general_data['info_block'])){
            $art_array['general_info']['info_block'] = $general_data['info_block'];
        }
        if(isset($general_data['what_need_info'])){
            $art_array['general_info']['what_need_info'] = $general_data['what_need_info'];
        }
        if(isset($general_data['best_time'])){
            $art_array['general_info']['best_time'] = $general_data['best_time'];
        }
        if(isset($general_data['routes_info'])){
            $art_array['general_info']['routes_info'] = $general_data['routes_info'];
        }

        return $art_array;
    }
}