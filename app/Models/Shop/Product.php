<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class Product extends Model
{
    public $table = 'products';

    protected $fillable = [
	    'id',
	    'published',
      'url_title',

      'discount',
      // 'material',
    
      'mead_in_georgia',

      'sale_type',

      'subcategory_id',
      'brand_id',
      
      'us_product_id',
      'ka_product_id'
      // 'ru_product_id'
	];

	public function us_product()
	{
		return $this->hasOne(Locale_product::class, 'id', 'us_product_id');
	}

	public function ka_product()
	{
		return $this->hasOne(Locale_product::class, 'id', 'ka_product_id');
	}

	// public function ru_product()
	// {
	// 	return $this->hasOne(Locale_product::class, 'id', 'ru_product_id');
	// }

  public function product_subcategory()
  {
    return $this->hasOne(Product_subcategory::class, 'id', 'subcategory_id');
  }

  public function product_options()
  {
    return $this->hasMany(Product_option::class, 'product_id');
  }

  public function feedbacks()
  {
    return $this->hasMany(Product_feedback::class, 'product_id', 'id');
  }

  public function orders()
  {
        return $this->belongsToMany(Order::class, 'order_products', 'order_id', 'product_id');
  }

  public function user()
  {
        return $this->belongsToMany(User::class, 'user_products', 'product_id', 'user_id');
  }

}
