<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Locale_film extends Model
{
    use HasFactory;

    public function global_film()
    {
		return $this->hasOne(Film::class, 'id');
    }
}
