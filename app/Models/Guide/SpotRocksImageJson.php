<?php

namespace App\Models\Guide;

use Illuminate\Database\Eloquent\Model;

class SpotRocksImageJson extends Model
{
    protected $fillable = [
        'json', 'spot_rocks_image_id', 'sector_id',
        'canvas_width', 'canvas_height',
        'bg_left', 'bg_top', 'bg_width', 'bg_height',
    ];

    public function spot_rocks_image()
    {
        return $this->belongsTo(Spot_rocks_image::class, 'spot_rocks_image_id');
    }

    public function sector()
    {
        return $this->belongsTo(Sector::class, 'sector_id');
    }
}
