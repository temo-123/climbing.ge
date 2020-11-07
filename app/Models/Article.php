<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class Article extends Model
{
    protected $fillable = [
	    'title', 
	    'article_title',
	    'text', 
	    'description_short', 
	    'image', 
	    'map', 
	    'info', 

	    'fb_link', 
		'google_link', 
		'inst_link', 
		'twit_link', 

		'start_data_day', 
		'start_data_month', 
		'and_data_day', 
		'and_data_month', 

		'meta_title', 
		'meta_description', 
		'meta_keyword',
	    
	    'tags',

	    'published',
	    'completed',
	];
}
