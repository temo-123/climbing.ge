<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ka_article extends Model
{
    public $table = 'ka_articles';

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
