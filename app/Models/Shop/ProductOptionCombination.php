<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;

class ProductOptionCombination extends Model
{
    protected $table = 'product_option_combinations';

    protected $fillable = [
        'product_id',
        'name',
        'price',
        'currency',
        'discount',
        'barcode',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function options()
    {
        return $this->belongsToMany(
            Product_option::class,
            'product_option_combination_items',
            'combination_id',
            'product_option_id'
        );
    }

    public function images()
    {
        return $this->hasMany(CombinateProductOptionImage::class, 'combination_id');
    }
}
