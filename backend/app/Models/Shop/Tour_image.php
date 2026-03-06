<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tour_image extends Model
{
    use HasFactory;

    public $table = 'tour_images';

    public function tour()
    {
		  return $this->hasMany(Toure::class,);
    }
}
