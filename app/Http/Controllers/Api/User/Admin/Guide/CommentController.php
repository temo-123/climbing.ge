<?php

namespace App\Http\Controllers\Api\User\Admin\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use auth;
use Notification;

use App\Models\User;
use App\Models\Guide\Comment;
use App\Models\Guide\Article;
use App\Models\Guide\Article_comment_answer;
use App\Models\Guide\Locale_article;
use App\Models\Guide\Article_comment_user;
use App\Models\Guide\Article_comment_complaint;

use App\Services\CommentService;
use App\Services\PermissionService;

use App\Notifications\comments\CommentAnswerNotification;

class CommentController extends Controller
{
    public function get_user_comments()
    {
        $auth = PermissionService::authorize('comment', 'view');
        // if ($auth) return $auth;
        $user_id = auth()->user()->id;

        if(user::where("id", "=", $user_id)->count() > 0){
            $user = user::where("id", "=", $user_id)->latest()->first();

            if($user->product_feedbacks){
                $user_comments = $user->article_comments;

                $comments = [];
                foreach ($user_comments as $comment) {

                    array_push($comments, [
                        'comment' => $comment, 
                        'locale_article' => $comment->article->us_article,
                        'global_article' => $comment->article
                    ]);
                }
                
                return $comments;
            }
        }
    }

    public function del_comment($id)
    {
        $auth = PermissionService::authorize('comment', 'del');
        // if ($auth) return $auth;
        $comment = Comment::where("id", '=', $id)->first();
        $answers = $comment->answers;

        $comment_deliting = CommentService::del_comment($id, Comment::class, Article_comment_user::class, 'comment');

        if($answers->count() > 0){
            foreach ($answers as $answer) {
                $answer_deliting = CommentService::del_comment($answer->id, Comment::class, Article_comment_user::class, 'comment');
            }
        }

        // return $comment_deliting;
    }

    public function hide_comment(Request $request)
    {
        $auth = PermissionService::authorize('comment', 'edit');
        // if ($auth) return $auth;
        $data = $request['data'];

        $actyve_comment = Comment::where("id", '=', $data['comment_id'])->first();
        
        return CommentService::comment_hide($data['complaint'], date("Y-m-d H:I:s"), $data['email'], $actyve_comment->id, $data['comment_id'], Comment::class, Article::class, 'article', 'comment');
    }

    public function get_actyve_comment($comment_id)
    {
        $auth = PermissionService::authorize('comment', 'view');
        // if ($auth) return $auth;
        return Comment::where('id',strip_tags($comment_id))->first();
    }

    // public function add_comment_complaint(Request $request)
    // {
    //     return CommentService::add_complaint($request, Article_comment_complaint::class, 'article', 'comment');
    // }

    public function get_comments_complaints(Request $request)
    {
        $auth = PermissionService::authorize('comment', 'view');
        // if ($auth) return $auth;
        return Article_comment_complaint::get();
    }

    public function make_decision(Request $request)
    {
        $auth = PermissionService::authorize('comment', 'edit');
        // if ($auth) return $auth;
        return CommentService::make_decision($request, Comment::class, Article_comment_complaint::class, Article::class, 'article', 'comment');
    }

    // public function confirm_email(Request $request) 
    // {        
    //     return CommentService::confirm_email($request->email);
    // }
}