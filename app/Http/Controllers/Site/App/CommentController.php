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
        // dd($request->article_id);
        return Comment::where("article_id", '=', $request->article_id)->get();
    }

    public function add_comment(Request $request)
    {
        $request->validate([
            'name' => 'required|max:25',
            'surname' => 'required|max:55',
            'email' => 'required|email',
            'text' => 'required',
        ]);
        // $this->validate($request,[
        //     "name" => "request|max:25"
        // ]);

        Comment::create($request->all());
        return (['message' => "tenk you for comment"]);
    }

    private function coment_validate($request)
    {
        $request->validate([
            'name' => 'required|max:25',
            'surname' => 'required|max:55',
            'email' => 'required|email',
            'text' => 'required',
        ]);
    }
}
