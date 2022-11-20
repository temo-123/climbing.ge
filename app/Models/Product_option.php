<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product_option extends Model
{
    use HasFactory;

    protected $fillable = [
	    'published',
	];


	public function us_article()
	{
		return $this->hasOne(Locale_article::class, 'id');
	}

    public function global_product()
    {
        return $this->hasMany(Product::class, 'id');
    }
}
