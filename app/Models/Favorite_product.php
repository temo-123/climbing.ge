<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite_product extends Model
{
    public $table = 'favorite_products';

    protected $fillable = [
        "user_id",
        "product_id"
    ];

    public function products()
    {
		return $this->hasMany(Product::class, 'id');
    }
}
