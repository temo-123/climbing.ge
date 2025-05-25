<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product_brand extends Model
{
    use HasFactory;

    public function us_brand()
    {
        return $this->hasOne(Locale_brand::class, 'id', 'us_brand_id');
    }

    public function ka_brand()
    {
        return $this->hasOne(Locale_brand::class, 'id', 'ka_brand_id');
    }
}
