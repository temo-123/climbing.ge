<?php

namespace App\Models\Guide;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SectorLocalImagesJson extends Model
{
    use HasFactory;

    protected $fillable = ['json', 'sector_local_image_id', 'sector_id', 'canvas_width', 'canvas_height'];

    public function sector_local_image()
    {
        return $this->belongsTo(Sector_local_image::class, 'sector_local_image_id');
    }

    public function sector()
    {
        return $this->belongsTo(Sector::class, 'sector_id');
    }
}
