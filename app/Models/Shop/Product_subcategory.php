<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Shop\Product_category;

class Product_subcategory extends Model
{
    use HasFactory;

    public function products()
    {
        return $this->hasMany(Product::class, 'subcategory_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(Product_category::class, 'category_id');
    }
}
