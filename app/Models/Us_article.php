<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Us_article extends Model
{
    public $table = 'us_articles';

    protected $fillable = [
	    'title', 
	    'text', 
	    'description_short', 
	    'image', 
	    'map', 
	    'info',
		'meta_keyword',
	];
}
