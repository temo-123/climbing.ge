<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite_outdoor_area extends Model
{
    use HasFactory;

    protected $fillable = [
	    'user_id',
	    'article_id',
	];

    public function outdoor_areas()
    {
		  return $this->hasOne(Article::class, 'id');
    }
}
