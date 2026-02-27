<?php

namespace App\Models\Guide;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SectorLocalImagesJsonSector extends Model
{
    use HasFactory;

    protected $table = 'sector_local_images_json_sectors';

    protected $fillable = ['sect_loc_img_json_id', 'sector_id'];

    public function sector_local_images_json()
    {
        return $this->belongsTo(SectorLocalImagesJson::class, 'sect_loc_img_json_id');
    }
}
