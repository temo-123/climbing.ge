<?php

namespace App\Http\Controllers\Api\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Validate;
use auth;
use Notification;

use App\Models\Comment;
use App\Models\Article_comment_complaint;
use App\Models\Article;
use App\Models\Article_comment_query;
use App\Models\User;
use App\Models\Site;
use App\Models\Article_comment_user;
use App\Notifications\DeleteCommentNotification;
use App\Notifications\CommentComplaintDecisionToDecisionerNotification;
use App\Notifications\AdminComplaintNotification;

use App\Services\CommentService;

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

    public function get_user_comments()
    {
        $user_id = auth()->user()->id;

        $user = user::where("id", "=", $user_id)->first();
        return $user->comments;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function get_article_comments($id)
    {
        $comments = Comment::where("article_id", '=', $id)->where("deleted_reason", '=', null)->latest()->get();
        $comment_array = [];

        foreach ($comments as $comment) {
            if($comment->user->first() != []){
                array_push($comment_array, ["comment" => $comment, "user" => $comment->user->first()]);
            }
            else {
                array_push($comment_array, ["comment" => $comment]);
            }
        }

        return $comment_array;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function create_comment(Request $request, $id)
    {
        CommentService::create_comment($request, $id, Comment::class, Article_comment_user::class, Article_comment_query::class);

        // // dd(Auth::user());
        // $this->comment_validate($request);

        // $is_verify_isset = $request->input('is_verify_isset');

        // // $user_id = 0;
        // //  dd(Auth::user()->id);

        // if($is_verify_isset){
        //     $comment = new Comment;
        //     $comment->name = $request->name;
        //     $comment->surname = $request->surname;
        //     $comment->email = $request->email;
        //     $comment->text = $request->text;
        //     $comment->article_id = $request->article_id;
        //     $comment->save();

        //     if (Auth::user()) {
        //         // $user_id = auth()->user() -> id;

        //         $comment_article = new Article_comment_user;
        //         $comment_article['comment_id'] = $comment->id;
        //         $comment_article['user_id'] = Auth::user()->id;
        //         $comment_article->save();
        //     }
        //     else if (!Auth::user()) {
        //         $users = User::get();
        //         foreach ($users as $user) {        
        //             if($request->email == $user->email){
        //                 $comment_query = new Article_comment_query;
        //                 $comment_query->comment_id = $comment->id;
        //                 $comment_query->user_id = $user->id;
        //                 $comment_query -> save();
        //             }
        //         }
        //     }

        //     return (['message' => "Tenk you for comment ".$request->name]);
        // }
        // elseif($is_verify_isset == false){
        //     return (['message' => "Varificate reCaptcha"]);
        // }
        // else{
        //     return (['message' => "Please update page and add comment after pdating!"]);
        // }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function del_comment($id)
    {
        // $comment = Comment::where('id',strip_tags($id))->first();
        // $comment_user_count = Article_comment_user::where('comment_id', '=', $comment->id)->count();
        // if($comment_user_count > 0){
        //     $comment_user = Article_comment_user::where('comment_id', '=', $comment->id)->first();
        //     $comment_user ->delete();
        // }

        // $comment ->delete();

        CommentService::del_comment($id, Comment::class, Article_comment_user::class);
    }

    // public function del_my_comment(Request $request)
    // {
    //     if ($request->isMethod('delete')) {
    //         $comment = Comment::where('id',strip_tags($request->comment_id))->first();
    //         $comment ->delete();
    //     }
    // }

    public function del_user_comment(Request $request)
    {
        if ($request->isMethod('post')) {
            $comment = Comment::where('id', '=', $request->comment_id)->first();
            $comment['deleted_reason'] = $request->cause;
            $comment['deleted_at'] =  date("Y-m-d H:I:s");
            $comment->save();

            (new static)->comment_delete_notification($request->cause, $comment['deleted_at'], $comment, $comment['article_id']);
        }
    }

    public function get_quick_comment(Request $request)
    {
        return Comment::where('id',strip_tags($request->comment_id))->first();
    }

    // public function comment_validate($request)
    // {
    //     $request->validate([
    //         'name' => 'required|max:25',
    //         'surname' => 'required|max:55',
    //         'email' => 'required|email',
    //         'text' => 'required',
    //         // 'is_verify_isset' => 'required|boolean|in:true',
    //     ]);
    // }

    public function add_comment_complaint(Request $request)
    {
        if (Auth::user()) {
            $user_complaint_count = Article_comment_complaint::where('user_id', '=', Auth::user())->where('comment_id', '=', $request->comment_id)->count();

            if($user_complaint_count){
                return('You olrede have complaint on this comment!');
            }
            else{
                $comment_complaint = new Article_comment_complaint;
        
                $comment_complaint->comment_id = $request->comment_id;
                $comment_complaint->complaint = $request->comment_complaint;
                // $comment_complaint->email = $request->email;
                $comment_complaint->user_id = Auth::user()->id;
                
                $comment_complaint->save();

                if($request->comment_complaint == "This is my comment"){
                    $query = Article_comment_query::where('comment_id', '=', $request->comment_id)->first();
                    $query -> delete();
                }

                (new static)->admin_complaint_notification();

                return('Thank you! We will look into your complaint and let you know!');
            }
        }
        else{
            return('Plees login');
        }
    }

    public function get_comments_complaints(Request $request)
    {
        return Article_comment_complaint::get();
    }

    public function get_user_queries(Request $request)
    {
        return Article_comment_query::where('user_id', '=', $request->user_id)->get();
    }

    public function make_decision(Request $request)
    {
        if($request->decision == 'approve_request'){
            $complaint = Article_comment_complaint::where('id', '=', $request->complaint_id)->first();

            $comment = Comment::where('id', '=', $request->comment_id)->first();
            $comment['deleted_reason'] = $complaint->complaint;
            $comment['deleted_at'] =  date("Y-m-d H:I:s");
            $comment->save();

            (new static)->comment_delete_notification($complaint->complaint, $comment['deleted_at'], $comment, $comment['article_id']);
            (new static)->comment_complaint_decision_to_decisione_notification($request->decision, $complaint, $request->decision);

            $complaint->delete();

            return 'approve_request';
        }
        else if($request->decision == 'reject_request'){
            $complaint = Article_comment_complaint::where('id', '=', $request->complaint_id)->first();

            (new static)->comment_complaint_decision_to_decisione_notification($request->decision, $complaint, $request->decision);

            $complaint->delete();

            return 'reject_request';
        }
    }

    public function query_response(Request $request)
    {
        if($request->response == true){
            $comment_article = new Article_comment_user;
            $comment_article['comment_id'] = $request->comment_id;
            $comment_article['user_id'] = Auth::user()->id;
            $comment_article->save();
        }

        $query = Article_comment_query::where('id', '=', $request->query_id)->first();
        $query -> delete();

    }

    public function comment_delete_notification($cause, $data_time, $email, $article_id)
    {   
        $article = Article::where('id', '=', $article_id)->first();
        $us_article = $article->us_article;
        
        $info = array(  
            'cause' => $cause,
            'data_time' => $data_time,
            'article_title' => $us_article->title,
        );
        Notification::send($email, new DeleteCommentNotification($info));
    }

    public function comment_complaint_decision_to_decisione_notification($cause, $email, $decision)
    {
        $info = array(  
            'cause' => $cause,
            'decision' => $decision,
        );
        Notification::send($email, new CommentComplaintDecisionToDecisionerNotification($info));
    }

    public function admin_complaint_notification()
    {
        $site_data = Site::first();
        Notification::send($site_data, new AdminComplaintNotification());
    }

}