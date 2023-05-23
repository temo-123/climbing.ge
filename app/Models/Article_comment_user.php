<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article_comment_user extends Model
{
    use HasFactory;

    public $table = 'article_comment_user';

    protected $fillable = [
        'user_id',
        'comment_id',
	];

	public function comment()
	{
		return $this->hasOne(Comment::class, 'id');
	}

	public function user()
	{
		return $this->hasOne(User::class, 'id');
	}
}
