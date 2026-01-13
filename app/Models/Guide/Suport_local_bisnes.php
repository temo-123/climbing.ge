<?php

namespace App\Models\Guide;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// use App\Models\Article;

class Suport_local_bisnes extends Model
{
    public $table = 'suport_local_bisneses';

    use HasFactory;

    protected $fillable = [
		'id',
		'url_title',
		'for_article_category',

		'published',
		'published_data',
		'public_totaly',
	
		'us_bisnes_id',
		'ka_bisnes_id',
		// 'ru_bisnes_id',
	];

	public function us_bisnes()
	{
		return $this->hasOne(Locale_bisnes::class, 'id', 'us_bisnes_id');
	}

	public function ka_bisnes()
	{
		return $this->hasOne(Locale_bisnes::class, 'id', 'ka_bisnes_id');
	}

	// public function ru_bisnes()
	// {
	// 	return $this->hasOne(Locale_bisnes::class, 'id', 'ru_bisnes_id');
	// }

	public function bisnes_images()
	{
		return $this->hasMany(Suport_local_bisnes_image::class, 'bisnes_id', 'id');
	}


	public function articles()
	{
        return $this->belongsToMany(Article::class, 'suport_local_bisnes_articles', 'bisnes_id', 'article_id');
	}
}
