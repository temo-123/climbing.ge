<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;
use App\Models\Shop\Product;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'confirm',
        'adres_id',
        // 'order_status',
        'order_payment',

        'status',
        'status_updating_data',
	];
	
	public function products()
	{
		return $this->belongsToMany(Product::class, 'order_products', 'order_id', 'product_id');
	}
}
