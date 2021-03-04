<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public $table = 'products';

    protected $fillable = [
        'title', 
        'published',
        'url_title',
        'category_id',
	    'text', 
        'description_short',
        'price',
        'currency',

        'material',
        'color',
        'discount',
        'quantity',
        'size',
        
        'us_product',
        'ru_product',
        'ka_product',

        'general_image'
	];
}
