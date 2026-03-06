<?php

namespace App\Models\Guide;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SectorLocalImagesJson extends Model
{
    use HasFactory;

    protected $fillable = ['json', 'sector_local_image_id'];

    public function sector_local_image()
    {
        return $this->belongsTo(Sector_local_image::class, 'sector_local_image_id');
    }

    public function sectors()
    {
        return $this->belongsToMany(Sector::class, 'sector_local_images_json_sectors', 'sect_loc_img_json_id', 'sector_id');
    }
}
