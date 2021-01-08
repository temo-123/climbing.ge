<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ka_article extends Model
{
    public $table = 'ka_articles';

    protected $fillable = [
	    'title',
		'short_description',
		'text',
		'route',
		'how_get',
		'best_time',
		'what_need',
		'info',
		'meta_keyword',
	];
}
