<?php

namespace App\Services;

use App\Models\Guide\Article;
use App\Models\Guide\Locale_article;
use App\Models\Guide\General_info;
use App\Models\Guide\General_info_article;
use App\Models\Guide\Mount;
use App\Models\Guide\Sector;
use App\Models\Guide\Route;
use App\Models\Guide\Mtp;

use Carbon\Carbon;
use App\Services\Abstract\LocaleContentService;
use App\Services\GeneralInfoService;
use App\Services\MountSystemService;

class ArticlesService extends LocaleContentService
{
    /**
     * Sector/route/multi-pitch counts per outdoor Article, for the
     * "X Sectors / Y Routes / Z Multi-pitch" badge shown on outdoor-area
     * cards. Returns one entry per article: ['article_id', 'sectors',
     * 'routes', 'mtps'].
     *
     * $publicOnly restricts to Sectors flagged `published` — Route/Mtp have
     * no publish flag of their own, so an unpublished Sector's routes/mtps
     * are implicitly hidden from guests right along with it, even though the
     * containing Article itself is published. Public/guest-facing callers
     * (Api\Guide\OutdoorController) must pass true; the admin-only caller
     * (Api\User\Admin\Guide\OutdoorController, permission-gated) passes
     * false to see the real totals regardless of each Sector's publish
     * state.
     *
     * Replaces a version of this loop that used to be duplicated 4x across
     * those two controllers and also had a real counting bug: it compared a
     * `$route_num` counter against the current article's `$sector_count`,
     * but `$route_num` was declared OUTSIDE the outer per-article loop and
     * so kept accumulating across every previous article in the batch — the
     * comparison only ever came out true "by accident" for the first article
     * processed, silently dropping every other article's quantity from the
     * result.
     */
    public static function get_route_quantity_for_outdoors($global_outdoors, bool $publicOnly = false)
    {
        $route_quantity = [];

        foreach ($global_outdoors as $outdoor) {
            $sectors_query = Sector::where('article_id', '=', $outdoor->id);
            if ($publicOnly) {
                $sectors_query->where('published', '=', 1);
            }
            $sector_ids = $sectors_query->pluck('id');

            $route_quantity[] = [
                "article_id" => $outdoor->id,
                "sectors" => $sector_ids->count(),
                "routes" => Route::whereIn('sector_id', $sector_ids)->count(),
                "mtps" => Mtp::whereIn('sector_id', $sector_ids)->count(),
            ];
        }

        return $route_quantity;
    }

    public static function get_locale_article_use_locale($global_article, $locale='en'){
        $localed_articles = (new static)->get_locale_content_use_locale($global_article, Locale_article::class, '_article_id', $locale);

        $articles = (new static)->get_article_additional_content($localed_articles, $locale);
        
        return $articles;
    }



    private static function get_article_additional_content($localed_articles, $locale = 'en') {
        $new_arr = [];
        $looped = 0;
        
        foreach ($localed_articles as $article) {
            if(isset($article['global_data']) && $article['global_data']['category'] == "mount_route"){
                $looped++;
                $act_article = Article::where('id', '=', $article['global_data']['id'])->first();

                $m_system = $act_article->mount_masiv;

                $local_mount = MountSystemService::get_local_mounts_use_locale($m_system, $locale);
                // dd($local_mount);
                if($local_mount){
                    array_push($new_arr, [
                        "locale_data"=>$article['locale_data'], 
                        "global_data"=>$article['global_data'], 
                        // 'mount_masive'=>$local_mount['locale_data']['title']
                        'mount_masive'=>$local_mount[0]['locale_data']['title']
                    ]);
                }
                else{
                    array_push($new_arr, [
                        "locale_data"=>$article['locale_data'], 
                        "global_data"=>$article['global_data'], 
                        // 'mount_masive'=>$local_mount['locale_data']['title']
                        // 'mount_masive'=>$local_mount[0]['locale_data']['title']
                    ]);
                }
            } else {
                // For non-mount_route articles, ensure they have the proper structure
                array_push($new_arr, [
                    "locale_data"=>$article['locale_data'] ?? null, 
                    "global_data"=>$article['global_data'] ?? $article
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

        $general_data = GeneralInfoService::getGeneralInfoForArticle($article, $locale);
        
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