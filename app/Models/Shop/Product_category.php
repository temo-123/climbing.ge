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
}
