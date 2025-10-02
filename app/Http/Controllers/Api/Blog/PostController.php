<?php

namespace App\Http\Controllers\Api\Blog;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Blog\Post;
use App\Models\Blog\Locale_post;
use App\User;

class PostController extends Controller
{
    public function get_all_posts_for_blog()
    {
        $posts = Post::get();
        $resp = [];
        foreach ($posts as $post) {
            $user = User::where('id','=',$post->user_id)->get();
            array_push($resp, ["post"=>$post, "user"=>$user[0]]);
        }
        return $resp;
    }
}
