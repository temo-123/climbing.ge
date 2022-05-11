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
        'weight',
        
        'us_product',
        'ru_product',
        'ka_product',

        'general_image'
	];

	public function us_product()
	{
		return $this->hasOhe(Us_product::class, 'id');
	}

	public function ka_product()
	{
		return $this->hasOhe(Ka_product::class, 'id');
	}

	public function ru_product()
	{
		return $this->hasOhe(Ru_product::class, 'id');
	}

    public function products()
    {
		return $this->hasMany(Product::class, 'id');
    }
}
