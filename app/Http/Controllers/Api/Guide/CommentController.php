<?php

namespace App\Http\Controllers\Api\Guide;

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

use App\Notifications\comments\CommentAnswerNotification;

class CommentController extends Controller
{
    public function get_all_comments()
    {
        if(Comment::count() > 0){
            $all_comments = Comment::latest()->get();

            $comments = [];
            foreach ($all_comments as $comment) {
                
                array_push($comments, [
                    'comment' => $comment, 
                    'global_article' => $comment->article,
                ]);
            }
            
            return $comments;
        }
    }

    public function get_user_comments()
    {
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

    public static function get_article_comments(Request $request)
    {
        $comments_without_answers = Comment::where("article_id", '=', $request->article_id)->where("deleted_reason", '=', null)->where("published", '=', 1)->latest('id')->get();
        $comments_with_answers = Comment::where("article_id", '=', $request->article_id)->where("deleted_reason", '=', null)->where("published", '=', 1)->latest('id')->get();

        $comment_array = [];

        foreach ($comments_with_answers as $comment) {
            if($comment->answers->count() > 0){
                foreach ($comment->answers as $ans) {
                    $comments_without_answers = $comments_without_answers->filter(fn ($item) => $item->id !== $ans->id);
                }
            }
        }

        foreach ($comments_without_answers as $comment) {
            $answers_array = [];

            if($comment->answers->count() > 0){
                foreach ($comment->answers as $ans) {
                    array_push($answers_array, [
                        'answer' => $ans,
                        'user' => $ans->user ? $ans->user->first() : null,
                    ]);
                }
            }

            array_push($comment_array, [
                'comment' => $comment,
                'user' => $comment->user ? $comment->user->first() : null,
                'answers' => $answers_array
            ]);
        }

        return $comment_array;
    }
    

    public function create_comment(Request $request)
    {
        $return = CommentService::create_comment($request, Comment::class, Article_comment_user::class, 'article', 'comment');

        if($request->answer_array['answer']){
            $new_answer = new Article_comment_answer;
            $new_answer['answer_id'] = $return['new_comment_id'];
            $new_answer['comment_id'] = $request->answer_array['comment_id'];
            $new_answer->save();

            $comment = Comment::where("id", '=', $new_answer['comment_id'])->first();
            $article = Article::where("id", '=', $comment['article_id'])->first();
            $us_article = $article->global_article_us;
            
            $info = [
                'us_article_title' => $us_article['title'],
                'url_title' => $article->url_title,
                'category' => $article->category
            ];
            Notification::route('mail', $comment->email)->notify(new CommentAnswerNotification($info));
        }

        return $return['message'];
    }

    // public function del_comment($id)
    // {
    //     $comment = Comment::where("id", '=', $id)->first();
    //     $answers = $comment->answers;

    //     $comment_deliting = CommentService::del_comment($id, Comment::class, Article_comment_user::class, 'comment');

    //     if($answers->count() > 0){
    //         foreach ($answers as $answer) {
    //             $answer_deliting = CommentService::del_comment($answer->id, Comment::class, Article_comment_user::class, 'comment');
    //         }
    //     }

    //     // return $comment_deliting;
    // }

    // public function hide_comment(Request $request)
    // {
    //     $data = $request['data'];

    //     $actyve_comment = Comment::where("id", '=', $data['comment_id'])->first();
        
    //     return CommentService::comment_hide($data['complaint'], date("Y-m-d H:I:s"), $data['email'], $actyve_comment->id, $data['comment_id'], Comment::class, Article::class, 'article', 'comment');
    // }

    public function get_actyve_comment($comment_id)
    {
        return Comment::where('id',strip_tags($comment_id))->first();
    }

    public function add_comment_complaint(Request $request)
    {
        return CommentService::add_complaint($request, Article_comment_complaint::class, 'article', 'comment');
    }

    // public function get_comments_complaints(Request $request)
    // {
    //     return Article_comment_complaint::get();
    // }

    // public function make_decision(Request $request)
    // {
    //     return CommentService::make_decision($request, Comment::class, Article_comment_complaint::class, Article::class, 'article', 'comment');
    // }

    public function confirm_email(Request $request) 
    {        
        return CommentService::confirm_email($request->email);
    }
}