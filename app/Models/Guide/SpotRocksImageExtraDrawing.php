<?php

namespace App\Models\Guide;

use Illuminate\Database\Eloquent\Model;

// Same idea as SectorLocalImageExtraDrawing, for the spot_rocks_image side —
// a general annotation layer tied only to the image, deliberately NOT
// per-sector like SpotRocksImageJson is (that one varies by which sector is
// viewing a shared image; this one is the same for everyone viewing it).
class SpotRocksImageExtraDrawing extends Model
{
    protected $fillable = [
        'json',
        'canvas_width',
        'canvas_height',
        'bg_left',
        'bg_top',
        'bg_width',
        'bg_height',
        'spot_rocks_image_id',
    ];

    public function spot_rocks_image()
    {
        return $this->belongsTo(Spot_rocks_image::class, 'spot_rocks_image_id');
    }
}
