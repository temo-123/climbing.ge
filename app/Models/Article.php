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
}
