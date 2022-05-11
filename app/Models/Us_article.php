<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Us_article extends Model
{
    public $table = 'us_articles';

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

	public function global_article()
	{
		return $this->hasOne(Article::class, 'us_article_id');
	}
}
