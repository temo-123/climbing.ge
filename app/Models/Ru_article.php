<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ru_article extends Model
{
    public $table = 'ru_articles';

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
