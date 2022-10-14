<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Film extends Model
{
    protected $fillable = [
	    'published',
	];

    public function files()
	{
		return $this->hasMany(Film_files::class, 'film_id');
	}

    public function category()
    {
        return $this->hasOne(Film_category::class, 'film_id');
    }

    
    public function us_film()
    {
        return $this->hasOne(Locale_films::class, 'us_film_id');
    }

    public function ka_film()
    {
        return $this->hasOne(Locale_films::class, 'ka_film_id');
    }

    public function ru_film()
    {
        return $this->hasOne(Locale_films::class, 'ru_film_id');
    }
}
