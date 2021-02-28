<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public $table = 'comments';

    protected $fillable = [
        'id',
        'name',
        'surname',
        'email',
        'text',
        'article_id'
    ];
}
