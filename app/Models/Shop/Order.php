<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;
use App\Models\Shop\Product;
use App\Models\Shop\CustomOrderAddress;
use App\Models\User;

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

    public function orderProducts()
    {
        return $this->hasMany(Order_products::class, 'order_id');
    }

    public function buyerAddress()
    {
        return $this->belongsToMany(CustomOrderAddress::class, 'custom_order_addresses_order', 'order_id', 'custom_order_address_id');
    }

    public function relatedUsers()
    {
        return $this->belongsToMany(User::class, 'orders_user', 'order_id', 'user_id');
    }
}
