<?php

namespace App\Http\Controllers\Api\Blog;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

use App\Models\Blog\Post;
use App\Models\Blog\Locale_post;
use App\Models\Guide\Article;
use App\Models\User;
use App\Services\ArticlesService;

class PostController extends Controller
{
    public function get_all_posts_and_news_for_blog(Request $request)
    {
        $locale = $request->locale ?? 'en';
        $lang = $locale === 'us' ? 'en' : $locale;
        $perPage = $request->get('per_page', 5);
        $page = $request->get('page', 1);

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
            $aCreated = $a['type'] === 'post'
                ? ($a['data']['global_data']->created_at ?? '')
                : (is_object($a['data']) ? ($a['data']->created_at ?? '') : ($a['data']['created_at'] ?? ''));
            $bCreated = $b['type'] === 'post'
                ? ($b['data']['global_data']->created_at ?? '')
                : (is_object($b['data']) ? ($b['data']->created_at ?? '') : ($b['data']['created_at'] ?? ''));
            return strtotime($bCreated ?: '1970-01-01') - strtotime($aCreated ?: '1970-01-01');
        });

        // Paginate the array
        $total = count($resp);
        $offset = ($page - 1) * $perPage;
        $paginatedItems = array_slice($resp, $offset, $perPage);

        $paginator = new LengthAwarePaginator(
            $paginatedItems,
            $total,
            $perPage,
            $page,
            ['path' => $request->url(), 'pageName' => 'page']
        );

        return $paginator;
    }

    public function get_post(Request $request, $url_title)
    {
        $lang = $request->get('locale', 'en');

        $post = Post::where('url_title', $url_title)->first();
        if (!$post) {
            return response()->json(['error' => 'Post not found'], 404);
        }

        $user = User::find($post->user_id);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $locale_post = null;
        if ($lang === 'en') {
            $locale_post = $post->us_post;
        } elseif ($lang === 'ka') {
            $locale_post = $post->ka_post;
        }

        if (!$locale_post) {
            return response()->json(['error' => 'Localized post not found'], 404);
        }

        return response()->json([
            'title' => $locale_post->title,
            'content' => $locale_post->text,
            'created_at' => $post->created_at,
            'short_description' => $locale_post->short_description,
            'image' => $post->image,
            'user' => $user
        ]);
    }

    public function get_news(Request $request, $url_title)
    {
        $lang = $request->get('locale', 'en');

        $news = Article::where('url_title', $url_title)->where('category', 'news')->where('published', 1)->first();
        if (!$news) {
            return response()->json(['error' => 'News not found'], 404);
        }

        $localized_news = ArticlesService::get_locale_article_use_locale(collect([$news]), $lang);

        if (empty($localized_news)) {
            return response()->json(['error' => 'Localized news not found'], 404);
        }

        $localized = $localized_news[0]['locale_data'];

        return response()->json([
            'title' => $localized->title,
            'content' => $localized->text,
            'created_at' => $news->created_at,
            'short_description' => $localized->short_description,
            'image' => $news->image,
            'category' => $news->category
        ]);
    }

    public function get_all_posts(Request $request){
        $lang = $request->get('locale', 'en');

        $posts = Post::with(['us_post', 'ka_post'])->orderBy('created_at', 'desc')->get();
        $resp = [];
        foreach ($posts as $post) {
            $user = User::where('id', $post->user_id)->first();
            $locale_post = null;
            if ($lang === 'en') {
                $locale_post = $post->us_post;
            } elseif ($lang === 'ka') {
                $locale_post = $post->ka_post;
            }
            array_push($resp, [
                "id" => $post->id,
                "title" => $locale_post ? $locale_post->title : '',
                "url_title" => $post->url_title,
                "published" => $post->published,
                "created_at" => $post->created_at,
                "user" => $user
            ]);
        }
        return $resp;
    }
}
