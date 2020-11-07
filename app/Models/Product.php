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
        'image_1',
        'image_2',
        'image_3',
        'image_4',
        'general_image'
	];
}
