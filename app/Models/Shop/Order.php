<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;
use App\Models\Shop\Product;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'adres_id',
        'confirm',
        'payment',
        'discount',
        'shiping',
        'is_custom',
        'status',
        'status_updating_data',
    ];
	
	public function products()
	{
		return $this->belongsToMany(Product::class, 'order_products', 'order_id', 'product_id');
	}
}
