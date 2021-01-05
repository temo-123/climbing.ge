<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class Article extends Model
{
    public $table = 'articles';

    protected $fillable = [
	    'url_title',

	    'fb_link', 
		'google_link', 
		'inst_link', 
		'twit_link', 

		'start_data_day', 
		'start_data_month', 
		'and_data_day', 
		'and_data_month', 

		'image',
		'working_time',
		'price_from',
		
		'meta_keyword',

	    'published',
	    'completed',
	];
}
