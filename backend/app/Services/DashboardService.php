<?php

namespace App\Services;

class DashboardService
{
    public static function check_relation_errors($us_title)
    {
        foreach (Locale_article::where("locale", "=", 'ka')->get() as $local_article) {
            $active_global_article = $local_article->global_article_ka;

            if (!$active_global_article) {
                array_push($conflict_ka_articles, $active_global_article);
            }
        }
    }

    public function relation_errors(Request $request)
    {
        foreach (Locale_article::where("locale", "=", 'ka')->get() as $local_article) {
            $active_global_article = $local_article->global_article_ka;

            if (!$active_global_article) {
                $local_article -> delete();
            }
        }
    }
}