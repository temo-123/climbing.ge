<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;

class Order_products extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'product_option_id',
        'quantity',
	];
}
