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
		// return $this->hasOne(Locale_article::class, 'id');
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
		// return $this->hasMany(General_info_article::class, 'article_id');
        return $this->belongsToMany(General_info::class, 'general_info_article', 'article_id', 'info_id');
	}


	public function mount_masiv()
	{
		// return $this->hasMany(Article_mount::class, 'article_id');
        // return $this->belongsToMany(Comment::class, 'article_comment_user', 'user_id', 'comment_id');
        return $this->belongsToMany(Mount::class, 'article_mount', 'article_id', 'mount_id');
        // return $this->belongsTo(Article_mount::class, 'article_mount', 'user_id', 'comment_id');
	}
}
