<?php

namespace App\Models\Guide;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sector_local_image extends Model
{
    use HasFactory;

    public function sectors()
    {
      return $this->belongsToMany(Sector::class, 'sector_local_image_sector', 'image_id', 'sector_id')->where('published', '=', 1);
    }

    public function image_sector()
    {
		return $this->hasMany(Sector_local_image_sector::class, 'image_id', 'id');
    }

    public function jsons()
    {
        return $this->hasMany(SectorLocalImagesJson::class, 'sector_local_image_id');
    }
}
