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
    public function get_all_comments()
    {
        return Comment::get();
    }

    public function get_user_comments()
    {
        $user_id = auth()->user()->id;

        $user = user::where("id", "=", $user_id)->first();
        return $user->comments;
    }

    public function get_article_comments(Request $request)
    {
        $comments = Comment::where("article_id", '=', $request->article_id)->where("deleted_reason", '=', null)->latest()->get();
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

    public function create_comment(Request $request)
    {
        CommentService::create_comment($request, Comment::class, Article_comment_user::class, Article_comment_query::class, 'article');
    }

    public function del_comment($id)
    {
        CommentService::del_comment($id, Comment::class, Article_comment_user::class);
    }

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