<?php

namespace App\Http\Controllers\Api\Blog;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Blog\Post;
use App\Models\Blog\Locale_post;
use App\Models\Guide\Article;
use App\Models\User;
use App\Services\ArticlesService;

class PostController extends Controller
{
    public function get_all_posts_and_news_for_blog(Request $request)
    {
        $lang = $request->lang ?? 'en'; // Default to 'en' if no lang provided
        
        $posts = Post::latest()->get();
        $news = Article::where('category', '=', 'news')->where('published', '=', 1)->latest()->get();

        // Localize news using ArticlesService
        $localized_news = ArticlesService::get_locale_article_use_locale($news, $lang);

        $resp = [];

        // Add posts with type
        foreach ($posts as $post) {
            $user = User::where('id', '=', $post->user_id)->first();
            if ($user) {
                $locale_post = null;
                if ($lang === 'en') {
                    $locale_post = $post->us_post;
                } elseif ($lang === 'ka') {
                    $locale_post = $post->ka_post;
                }
                if ($locale_post) {
                    array_push($resp, ["data" => ["global_data" => $post, "locale_data" => $locale_post], "user" => $user, "type" => "post"]);
                }
            }
        }

        // Add localized news with type
        foreach ($localized_news as $news_item) {
            array_push($resp, ["data" => $news_item, "type" => "news"]);
        }

        // Sort by creation date descending
        usort($resp, function($a, $b) {
            $aTime = strtotime($a['data']->created_at ?? now());
            $bTime = strtotime($b['data']->created_at ?? now());
            return $bTime - $aTime;
        });

        return $resp;
    }
}
