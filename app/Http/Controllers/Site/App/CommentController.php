<?php

namespace App\Http\Controllers\Site\App;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Comment;

class CommentController extends Controller
{
    public function get_comments()
    {
        return Comment::all();
    }

    public function add_comment(Request $request)
    {
        Comment::create($request->all());
        return (['message' => "tenk you for comment"]);
    }
}
