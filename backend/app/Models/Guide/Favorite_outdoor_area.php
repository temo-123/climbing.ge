<?php

namespace App\Models\Guide;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Favorite_outdoor_area extends Model
{
    // use HasFactory;

    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
	    'user_id',
	    'article_id',
	];

    public function outdoor_area()
    {
		return $this->hasOne(Article::class, 'id', 'article_id');
    }
}
