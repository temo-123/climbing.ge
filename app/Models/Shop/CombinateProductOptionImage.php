<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;

class CombinateProductOptionImage extends Model
{
    protected $table = 'combinate_product_option_images';

    protected $fillable = [
        'combination_id',
        'image',
        'title',
    ];

    public function combination()
    {
        return $this->belongsTo(ProductOptionCombination::class, 'combination_id');
    }
}
