<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Toure_image extends Model
{
    use HasFactory;

    public $table = 'toure_images';

    public function toure()
    {
		  return $this->hasMany(Service::class,);
    }
}
