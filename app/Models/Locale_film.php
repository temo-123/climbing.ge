<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Locale_film extends Model
{
    use HasFactory;

    protected $fillable = [
	    'title',
      'short_description',
      'text',
    ];

    public function global_film_us()
    {
      return $this->hasOne(Film::class, 'us_film_id');
    }
  
    public function global_film_ka()
    {
      return $this->hasOne(Film::class, 'ka_film_id');
    }
  
    public function global_film_ru()
    {
      return $this->hasOne(Film::class, 'ru_film_id');
    }

    // public function global_film()
    // {
		//   return $this->hasOne(Film::class, 'id');
    // }
}
