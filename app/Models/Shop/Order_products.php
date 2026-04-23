<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;
use App\Models\Shop\Product_option;

class Order_products extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'product_option_id',
        'quantity',
    ];

    public function option()
    {
        return $this->belongsTo(Product_option::class, 'product_option_id');
    }
}
