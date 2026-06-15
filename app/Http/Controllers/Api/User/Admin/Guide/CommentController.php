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
        $auth = PermissionService::authorize('comment', 'show');
        if ($auth) return $auth;
        $user_id = auth()->user()->id;

        $user = user::find($user_id);
        if (!$user) return [];

        $comments = [];
        foreach ($user->article_comments as $comment) {
            $comments[] = [
                'id' => $comment->id,
                'comment' => $comment,
                'locale_article' => $comment->article->us_article,
                'global_article' => $comment->article,
            ];
        }

        return $comments;
    }

    public function del_comment($id)
    {
        $auth = PermissionService::authorize('comment', 'del');
        if ($auth) return $auth;
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
        if ($auth) return $auth;
        $data = $request['data'];

        $actyve_comment = Comment::where("id", '=', $data['comment_id'])->first();

        return CommentService::comment_hide($data['complaint'], date("Y-m-d H:I:s"), $data['email'], $actyve_comment->id, $data['comment_id'], Comment::class, Article::class, 'article', 'comment');
    }

    public function user_hide_comment($comment_id)
    {
        $user = auth()->user();
        $comment = $user->article_comments()->find($comment_id);
        if (!$comment) return response()->json(['error' => 'Not found'], 404);
        $comment->published = 0;
        $comment->save();
        return response()->json(['success' => true]);
    }

    public function user_show_comment($comment_id)
    {
        $user = auth()->user();
        $comment = $user->article_comments()->find($comment_id);
        if (!$comment) return response()->json(['error' => 'Not found'], 404);
        if ($comment->admin_hidden) return response()->json(['error' => 'Hidden by admin', 'admin_hidden' => true], 403);
        $comment->published = 1;
        $comment->save();
        return response()->json(['success' => true]);
    }

    public function user_edit_comment(Request $request, $comment_id)
    {
        $user = auth()->user();
        $comment = $user->article_comments()->find($comment_id);
        if (!$comment) return response()->json(['error' => 'Not found'], 404);
        $comment->text = strip_tags($request->input('text'));
        $comment->save();
        return response()->json(['success' => true]);
    }

    public function user_del_comment($comment_id)
    {
        $user = auth()->user();
        $comment = $user->article_comments()->find($comment_id);
        if (!$comment) return response()->json(['error' => 'Not found'], 404);
        $comment->delete();
        return response()->json(['success' => true]);
    }

    public function admin_hide_comment(Request $request, $comment_id)
    {
        if ($auth = PermissionService::authorize('comment', 'edit')) return $auth;
        $comment = Comment::find(strip_tags($comment_id));
        if ($comment) {
            $comment->published = 0;
            $comment->admin_hidden = 1;
            $comment->hidden_reason = $request->input('hidden_reason');
            $comment->hidden_reason_text = $request->input('hidden_reason_text');
            $comment->deleted_reason = $request->input('hidden_reason');
            $comment->save();
        }
        return response()->json(['success' => true]);
    }

    public function get_actyve_comment($comment_id)
    {
        $auth = PermissionService::authorize('comment', 'show');
        if ($auth) return $auth;
        return Comment::where('id',strip_tags($comment_id))->first();
    }

    // public function add_comment_complaint(Request $request)
    // {
    //     return CommentService::add_complaint($request, Article_comment_complaint::class, 'article', 'comment');
    // }

    public function get_comments_complaints(Request $request)
    {
        $auth = PermissionService::authorize('comment', 'show');
        if ($auth) return $auth;
        return Article_comment_complaint::get();
    }

    public function make_decision(Request $request)
    {
        $auth = PermissionService::authorize('comment', 'edit');
        if ($auth) return $auth;
        return CommentService::make_decision($request, Comment::class, Article_comment_complaint::class, Article::class, 'article', 'comment');
    }

    // public function confirm_email(Request $request) 
    // {        
    //     return CommentService::confirm_email($request->email);
    // }
}