<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;

class Product_image extends Model
{
    public $table = 'product_images';

    protected $fillable = ['product_id', 'image'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
