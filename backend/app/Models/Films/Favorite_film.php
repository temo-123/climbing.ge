<?php

namespace App\Models\Films;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite_film extends Model
{
    use HasFactory;

    protected $fillable = [
	    'user_id',
	    'film_id',
	];

    public function film()
    {
		return $this->hasOne(Film::class, 'id', 'film_id');
    }
}
