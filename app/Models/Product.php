<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'title', 
        'url_title',
        'category',
	    'text', 
        'description_short',
        'price',
        'currency', 
        
        'us_product',
        'ru_product',
        'ka_product',

        'general_image'
	];
}
