<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User\Post;

class PostController extends Controller
{
    function get_posts(Request $request) {
        return Post::all();
    }

    function add_posts(Request $request) {
        $post = Post::create($request->all('data')['data']);
        return response()->json($post, 201);
    }

    function edit_post(Request $request) {
        $post = Post::find($request->id);
        if (!$post) {
            return response()->json(['error' => 'Not found'], 404);
        }
        $post->update($request->all('data')['data']);
        return response()->json($post);
    }

    function get_editing_post(Request $request) {
        return Post::find($request->id);
    }

    function get_activ_post(Request $request) {
        return Post::find($request->id);
    }

    function del_post(Request $request) {
        $deleted = Post::where('id', $request->id)->delete();
        return response()->json(['deleted' => $deleted > 0], $deleted ? 200 : 404);
    }
}
