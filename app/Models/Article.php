<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;
use Illuminate\Notifications\Notifiable;

class Article extends Model
{
    use Notifiable;

    public $table = 'articles';

    protected $fillable = [
	    'published',
		'url_title',
		'map',
		'weather',

		// 'completed',
		// 'start_data',
		// 'end_data',

		// 'fb_link',
		// 'twit_link',
		// 'google_link',
		// 'inst_link',
		// 'web_link',
	
		'image',

		'mount_route',

		'price_from',
		
		'open_timen',
		'closed_time'
	];


	public function us_article()
	{
		return $this->hasOne(Locale_article::class, 'id', 'us_article_id');
	}

	public function ka_article()
	{
		// return $this->hasOne(Locale_article::class, 'id');
		return $this->hasOne(Locale_article::class, 'id', 'ka_article_id');
	}

	public function ru_article()
	{
		// return $this->hasOne(Locale_article::class, 'id');
		return $this->hasOne(Locale_article::class, 'id', 'ru_article_id');
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
		return $this->hasOne(Suport_local_bisnes::class, 'article_id', 'id');
	}



	public function general_info()
	{
        return $this->belongsToMany(General_info::class, 'general_info_article', 'article_id', 'info_id');
	}

	public function gallery_images()
	{
        return $this->belongsToMany(Gallery_image::class, 'gallery_image_article', 'article_id', 'image_id');
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
