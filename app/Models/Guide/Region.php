<?php

namespace App\Models\Guide;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;

    protected $fillable = [
		'us_name',
		// 'ru_name',
		'ka_name',

		'us_text',
		// 'ru_text',
		'ka_text',

		'map',
	];

	public function articles()
	{
		return $this->belongsToMany(Article::class, 'article_region', 'region_id', 'article_id');
	}
}
