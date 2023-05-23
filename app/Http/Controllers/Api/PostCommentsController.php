<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Post_comment;

class PostCommentsController extends Controller
{
    public function get_post_comment(Request $request){
        return Post_comment::where("post_id", '=', $request->id)->get();
    }

    public function add_post_comment(Request $request)
    {
        # code...
    }

    public function edit_post_comment(Request $request)
    {
        # code...
    }

    public function del_post_comment(Request $request)
    {
        # code...
    }
}
