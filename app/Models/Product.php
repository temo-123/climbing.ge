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
		return $this->hasOne(Locale_product::class, 'id', 'us_product_id');
	}

	public function ka_product()
	{
		return $this->hasOne(Locale_product::class, 'id', 'ka_product_id');
	}

	public function ru_product()
	{
		return $this->hasOne(Locale_product::class, 'id', 'ru_product_id');
	}

  public function product_category()
  {
    return $this->hasOne(Product_category::class, 'id', 'category_id');
  }

  public function product_options()
  {
    return $this->hasMany(Product_option::class, 'product_id');
  }
}
