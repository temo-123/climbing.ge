<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product_option extends Model
{
    use HasFactory;


    public function global_product()
    {
        return $this->hasMany(Product::class, 'id');
    }
}
