<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;

class Favorite_product extends Model
{
    public $table = 'favorite_products';

    protected $fillable = [
        "user_id",
        "product_id"
    ];

    public function product()
    {
		return $this->hasMany(Product::class, 'id');
    }
}
