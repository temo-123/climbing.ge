<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Comment extends Model
{
  use Notifiable;

    public $table = 'article_comments';

    protected $fillable = [
        'id',
        'name',
        'surname',
        'email',
        'text',

        'user_id',
        'article_id',

        'comment_deleted_reason'
    ];

    public function article()
    {
		  return $this->hasOne(Article::class, 'id');
    }

    public function user()
    {
      return $this->hasOne(Article_comment_user::class, 'coment_id');
      // return $this->belongsTo(Article_comment_user::class, 'coment_id');
    }
}
