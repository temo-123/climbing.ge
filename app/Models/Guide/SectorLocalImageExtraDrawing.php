<?php

namespace App\Models\Guide;

use Illuminate\Database\Eloquent\Model;

// Same idea as SectorImageExtraDrawing, for the sector_local_image ("local
// approach photo") side of the guide instead — a general annotation layer
// tied only to the image, deliberately NOT per-sector like
// SectorLocalImagesJson is (that one varies by which sector is viewing a
// shared image; this one is the same for everyone viewing the image).
class SectorLocalImageExtraDrawing extends Model
{
    protected $fillable = [
        'json',
        'canvas_width',
        'canvas_height',
        'bg_left',
        'bg_top',
        'bg_width',
        'bg_height',
        'sector_local_image_id',
    ];

    public function sector_local_image()
    {
        return $this->belongsTo(Sector_local_image::class, 'sector_local_image_id');
    }
}
