<?php

namespace App\Http\Controllers\Site\App;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Comment;

use Validate;

class CommentController extends Controller
{
    public function get_comments(Request $request)
    {
        return Comment::where("article_id", '=', $request->article_id)->get();
    }

    public function add_comment(Request $request)
    {
        $this->comment_validate($request);

        Comment::create($request->all());
        return (['message' => "tenk you for comment"]);
    }

    public function comment_validate($request)
    {
        $request->validate([
            'name' => 'required|max:25',
            'surname' => 'required|max:55',
            'email' => 'required|email',
            'text' => 'required',
        ]);
    }
}
