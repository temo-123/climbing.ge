<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Comment;
use Validate;
use auth;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Comment::get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Comment::where("article_id", '=', $id)->get();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->comment_validate($request);

        $is_verify_isset = $request->input('is_verify_isset');
        $user_id = 0;

        if($is_verify_isset){
            if (Auth::user()) {
                Auth::user() -> id = $user_id;
                // var_dump($user_id);
            }
            // var_dump($user_id);
            // dd($user_id);
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ($id) {
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
