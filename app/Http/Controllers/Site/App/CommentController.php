<?php

namespace App\Http\Controllers\Site\App;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Comment;

use Validate;
use auth;

class CommentController extends Controller
{
    public function get_comments(Request $request)
    {
        return Comment::where("article_id", '=', $request->article_id)->get();
    }

    public function add_comment(Request $request)
    {
        $this->comment_validate($request);

        $is_verify_isset = $request->input('is_verify_isset');
        $user_id = 0;

        if($is_verify_isset){
            if (Auth::user()) {
                Auth::user() -> id == $user_id;
            }
            var_dump($user_id);
            dd($user_id);
            Comment::create($request->all());
            return (['message' => "Tenk you for comment ".$request->name]);
        }
        elseif($is_verify_isset == false){
            return (['message' => "Varificate reCaptcha"]);
        }
        else{
            return (['message' => "Please update page and add comment after pdating!"]);
        }
    }

    public function del_comment(Request $request)
    {
        if ($request->isMethod('post')) {
            $id=$request->id;
            $comment = Comment::where('id',strip_tags($id))->first();
            $comment ->delete();
        }
    }

    public function comment_validate($request)
    {
        $request->validate([
            'name' => 'required|max:25',
            'surname' => 'required|max:55',
            'email' => 'required|email',
            'text' => 'required',
            // 'is_verify_isset' => 'required|boolean|in:true',
        ]);
    }
}
