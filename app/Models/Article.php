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

		'start_data_day',
		'and_data_day',
		'start_data_month',
		'and_data_month',

		'fb_link',
		'twit_link',
		'google_link',
		'inst_link',
		'web_link',
	
		'image',

		'mount_route',

		'price_from',
		'working_time'
	];
}
