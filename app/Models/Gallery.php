<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    public $table = 'galleries';

    protected $fillable = [
        'category',
        'title',
        'text',
        'link',
        'filter',
        'article_id',
        'published',
        'article_id',
    ];
}
