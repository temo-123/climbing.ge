<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Blog\Post;
use App\Models\Blog\Locale_post;

use App\Services\PostService;

class PostController extends Controller
{
    function get_posts(Request $request) {
        $posts = Post::with(['us_post', 'ka_post'])->orderBy('created_at', 'desc')->get();
        $resp = [];
        foreach ($posts as $post) {
            $user = User::where('id', $post->user_id)->first();
            array_push($resp, [
                "id" => $post->id,
                "title" => $post->us_post->title ?? $post->ka_post->title ?? '',
                "content" => $post->us_post->text ?? $post->ka_post->text ?? '',
                "short_description" => $post->us_post->short_description ?? $post->ka_post->short_description ?? '',
                "image" => $post->image,
                "published" => $post->published,
                "created_at" => $post->created_at,
                "user" => $user
            ]);
        }
        return $resp;
    }

    function add_post(Request $request) {
        $data = json_decode($request->data, true );
        
        $data['global_post']['user_id'] = auth()->user()->id;
        
        $post_adding = PostService::add_content($data, Post::class, Locale_post::class, '_post', $request, 'images/blog_img/');

        return $post_adding;
    }

    function edit_post(Request $request) {
        $data = json_decode($request->data, true );

        $post_editing = PostService::edit_content(Post::class, Locale_post::class, '_post', $request, 'images/blog_img/');

        return $post_editing;
    }

    function get_editing_post(Request $request) {
        $post = Post::where('id', strip_tags($request->id))->first();
        $us_post = Locale_post::where('id', $post->us_post_id ?? null)->first();
        $ka_post = Locale_post::where('id', $post->ka_post_id ?? null)->first();

        $data = [
            "global_post" => $post,
            "us_post" => $us_post,
            "ka_post" => $ka_post,
        ];
        return $data;
    }

    function get_activ_post(Request $request) {
        $post = Post::with(['us_post', 'ka_post'])->find($request->id);
        if (!$post) {
            return null;
        }
        $user = User::where('id', $post->user_id)->first();
        return [
            "id" => $post->id,
            "title" => $post->us_post->title ?? $post->ka_post->title ?? '',
            "content" => $post->us_post->text ?? $post->ka_post->text ?? '',
            "short_description" => $post->us_post->short_description ?? $post->ka_post->short_description ?? '',
            "image" => $post->image,
            "published" => $post->published,
            "created_at" => $post->created_at,
            "user" => $user
        ];
    }

    function del_post(Request $request) {
        $global_id=$request->id;

        $global_post = Post::where('id',strip_tags($global_id))->first();

        PostService::del_content($global_id, Post::class, Locale_post::class, '_post', 'image', 'images/blog_img/');

    }
}
