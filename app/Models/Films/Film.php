<?php

namespace App\Models\Films;

use Illuminate\Database\Eloquent\Model;

class Film extends Model
{
    // Real PHP property (not an Eloquent attribute) so setting it doesn't get
    // picked up by getDirty()/the UPDATE query. Read by FilmObserver::updated()
    // to decide whether/how the admin opted in to a notification for this save:
    // 'none' (default), 'update', or 'new' (re-announce as if newly published).
    public $notifyMode = 'none';

    protected $fillable = [
	    'published',
	];

    public function files()
	{
		return $this->hasMany(Film_file::class, 'film_id');
	}

    public function tags()
    {
        return $this->hasMany(Film_tag::class, 'film_id');
    }

    
    public function us_film()
    {
        return $this->hasOne(Locale_film::class, 'id', 'us_film_id');
    }

    public function ka_film()
    {
        return $this->hasOne(Locale_film::class, 'id', 'ka_film_id');
    }

    // public function ru_film()
    // {
    //     return $this->hasOne(Locale_films::class, 'ru_film_id');
    // }
}
