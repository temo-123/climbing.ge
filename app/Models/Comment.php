<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Comment extends Model
{
  use Notifiable;

    public $table = 'article_comments';

    // protected $fillable = [
    //     'id',
    //     'name',
    //     'surname',
    //     'email',
    //     'text',

    //     // 'user_id',
    //     'article_id',

    //     'comment_deleted_reason'
    // ];

    public function article()
    {
      return $this->hasOne(Article::class, 'id', 'article_id');
    }

    public function user()
    {
      return $this->belongsToMany(User::class, 'article_comment_user', 'comment_id', 'user_id');
    }

    public function answers()
    {
      return $this->belongsToMany(Comment::class, 'article_comment_answers', 'comment_id', 'answer_id');
    }
}
