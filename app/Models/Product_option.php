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


	public function images()
	{
		return $this->hasMany(Option_image::class, 'option_id');
	}

    public function product()
    {
        return $this->belongsTo(Product::class, 'id');
    }
}
