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
        return Post::all();
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
        return Post::find($request->id);
    }

    function del_post(Request $request) {
        $global_id=$request->id;

        $global_post = Post::where('id',strip_tags($global_id))->first();

        PostService::del_content($global_id, Post::class, Locale_post::class, '_post', 'image', 'images/blog_img/');

    }
}
