<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public $table = 'article_comments';

    protected $fillable = [
        'id',
        'name',
        'surname',
        'email',
        'text',
        'article_id'
    ];

    public function articles()
    {
		return $this->hasMany(Article::class, 'id');
    }
}
