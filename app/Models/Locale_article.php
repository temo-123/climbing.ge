<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Locale_article extends Model
{
    public $table = 'locale_articles';

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

	// public function global_article_us()
	// {
	// 	return $this->hasOne(Article::class, 'us_article_id');
	// }

	// public function global_article_ka()
	// {
	// 	return $this->hasOne(Article::class, 'ka_article_id');
	// }

	// public function global_article_ru()
	// {
	// 	return $this->hasOne(Article::class, 'ru_article_id');
	// }

	public function global_article()
	{
		return $this->hasOne(Article::class, 'id');
	}
}
