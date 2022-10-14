<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Post;
use App\Models\Post_comment;
use App\Models\PostsTopics;
use App\Models\Post_like;
use App\User;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::get();
        $resp = [];
        foreach ($posts as $post) {
            $user = User::where('id','=',$post->user_id)->get();
            array_push($resp, ["post"=>$post, "user"=>$user[0]]);
        }
        return $resp;
    }

    public function add_post_comment(Request $request)
    {
        dd($request);
        if (Auth::user()) {
            $new_post_comment = new Post_comment;
            $new_post_comment->user_id = Auth::user();
            $new_post_comment->post_id = $request->post_id;
            $new_post_comment->text = $request->text;
            dd($new_post_comment);
            // $is_verify_isset = $request->input('is_verify_isset');
            // if($is_verify_isset){
            //     $request['topic_id'] = $request->topic;
            //     $request['user_id'] = auth()->user()->id;

            //     $this->post_validate($request);

            //     Post::create($request->all());
            //     return (['message' => "Tenk you for post ".$request->name]);
            // }
            // elseif($is_verify_isset == false){
            //     return (['message' => "Varificate reCaptcha"]);
            // }
            // else{
            //     return (['message' => "Please update page and add post after pdating!"]);
            // }
        }
        else{
            return (['message' => "Please login!"]);
        }
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



    public function add_post(Request $request)
    {
        if (auth()->user()->id != null) {
            $is_verify_isset = $request->input('is_verify_isset');
            if($is_verify_isset){
                $request['topic_id'] = $request->topic;
                $request['user_id'] = auth()->user()->id;

                $this->post_validate($request);

                Post::create($request->all());
                return (['message' => "Tenk you for post ".$request->name]);
            }
            elseif($is_verify_isset == false){
                return (['message' => "Varificate reCaptcha"]);
            }
            else{
                return (['message' => "Please update page and add post after pdating!"]);
            }
        }
        else{
            return (['message' => "Please login!"]);
        }
    }

    public function post_validate($request)
    {
        $request->validate([
            'text' => 'required',
            'topic_id' => 'required',
            'user_id' => 'required',
        ]);
    }


    public function get_likes($post_id)
    {
        // return Post_like::where("post_id", "=", $post_id)->count();
        return Post_like::where("post_id", "=", $post_id)->count();
        // dd($likes_count);
        //  ;
    }

    public function get_route_posts($route_id)
    {
        $posts =  Post::where('route_id','=', $route_id)->take(4)->get();
        $resp = [];
        foreach ($posts as $post) {
            $user = User::where('id','=',$post->user_id)->get();
            array_push($resp, ["post"=>$post, "user"=>$user[0]]);
        }
        return $resp;
    }

    public function get_mtp_posts($mtp_id)
    {
        $posts =  Post::where('mtp_id','=', $mtp_id)->take(4)->get();
        $resp = [];
        foreach ($posts as $post) {
            $user = User::where('id','=',$post->user_id)->get();
            array_push($resp, ["post"=>$post, "user"=>$user[0]]);
        }
        return $resp;
    }

    public function get_posts_for_outdoor_region()
    {
        dd('test post');
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
        dd('show');
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
