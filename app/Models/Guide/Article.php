<?php

namespace App\Models\Guide;

use Illuminate\Database\Eloquent\Model;

use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;
use Illuminate\Notifications\Notifiable;

// use App\Models\Guide\Article_image;
// use App\Models\Guide\Suport_local_bisnes;

class Article extends Model
{
    use Notifiable;

    public $table = 'articles';

    protected $fillable = [
	    'published',
		'url_title',
		'map',
		'weather',
	
		'image',

		'mount_route',

		'price_from',
		
		'open_timen',
		'closed_time'
	];


	public function global_article_us()
	{
		return $this->hasOne(Locale_article::class, 'us_article_id', 'id');
	}

	public function global_article_ka()
	{
		return $this->hasOne(Locale_article::class, 'ka_article_id', 'id');
	}

	public function global_article_ru()
	{
		return $this->hasOne(Locale_article::class, 'ru_article_id', 'id');
	}


	public function comments()
	{
		return $this->hasMany(Comments::class, 'article_id');
	}

	public function sectors()
	{
		return $this->hasMany(Sector::class, 'article_id');
	}

	public function spot_rock_images()
	{
		return $this->hasMany(Spots_rocks_image::class, 'article_id');
	}


	public function bisnes()
	{
        return $this->belongsToMany(Suport_local_bisnes::class, 'suport_local_bisnes_articles', 'article_id', 'bisnes_id');
	}


	public function general_info()
	{
        return $this->belongsToMany(General_info::class, 'general_info_article', 'article_id', 'info_id');
	}

	public function gallery_images()
	{
		return $this->hasMany(Article_image::class, 'article_id', 'id');
	}


	public function mount_masiv()
	{
        return $this->belongsToMany(Mount::class, 'article_mount', 'article_id', 'mount_id');
	}

	public function outdoor_region()
	{
        return $this->belongsToMany(Region::class, 'article_region', 'article_id', 'region_id');
	}
}
