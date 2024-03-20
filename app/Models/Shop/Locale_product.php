<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;

class Locale_product extends Model
{
    public $table = 'locale_products';

    protected $fillable = [
        'title',
        'short_description',
        'text',
        'meta_keyword'
    ];


	public function global_product_us()
	{
		return $this->hasOne(Product::class, 'us_product_id');
	}

	public function global_product_ka()
	{
		return $this->hasOne(Product::class, 'ka_product_id');
	}

	public function global_product_ru()
	{
		return $this->hasOne(Product::class, 'ru_product_id');
	}
}
