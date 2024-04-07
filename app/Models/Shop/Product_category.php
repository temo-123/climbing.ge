<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;

class Product_category extends Model
{
    public $table = 'product_categories';

    protected $fillable = [
        "us_name",
        "ka_name",
        "ru_name",
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id', 'id');
    }
}
