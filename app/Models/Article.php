<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class Article extends Model
{
    public $table = 'articles';

    protected $fillable = [
	    'published',
		'completed',
		'map',
		'weather',

		'start_data',
		'end_data',

		'fb_link',
		'twit_link',
		'google_link',
		'inst_link',
		'web_link',
	
		'image',

		'mount_route',

		'price_from',
		
		'open_timen',
		'closed_time'
	];


	public function us_article()
	{
		return $this->hasOne(Locale_article::class, 'id');
	}

	public function ka_article()
	{
		return $this->hasOne(Locale_article::class, 'id');
	}

	public function ru_article()
	{
		return $this->hasOne(Locale_article::class, 'id');
	}


	public function sectors()
	{
		return $this->hasMany(Sector::class, 'article_id');
	}

	public function spot_rock_images()
	{
		return $this->hasMany(Spots_rocks_image::class, 'article_id');
	}


	public function general_info()
	{
		return $this->hasMany(General_info_article::class, 'article_id');
	}


	public function mount_masiv()
	{
		return $this->hasMany(Article_mount::class, 'article_id');
	}
}
