<?php

namespace App\Models\Films;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Film_file extends Model
{
    use HasFactory;

    protected $fillable = [
	    'published',
	];

    public function film()
	{
		return $this->hasMany(Film::class, 'film_id');
	}
}
