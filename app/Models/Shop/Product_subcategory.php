<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product_subcategory extends Model
{
    use HasFactory;

    public function products()
    {
        // return $this->belongsTo(Product::class, 'id');
        return $this->hasMany(Product::class, 'subcategory_id', 'id');

    }
}
