<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sector_local_image_sector extends Model
{
    use HasFactory;

    public $table = 'sector_local_image_sector';

    public function image()
    {
        return $this->hasOne(Sector_local_image::class, 'id', 'image_id');
    }
    public function sector()
    {
        // return $this->hasMany(Sector_local_image::class);
		return $this->hasOne(Sector::class, 'id', 'sector_id');
    }
}
