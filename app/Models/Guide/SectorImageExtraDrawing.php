<?php

namespace App\Models\Guide;

use Illuminate\Database\Eloquent\Model;

// A general-purpose annotation layer on a sector topo photo — unlike
// ClimbingRoutesJson (tied to a specific route) or MtpPitchJson (tied to a
// specific multi-pitch pitch), this ties only to the image itself, for
// markings that aren't about any one route (e.g. approach notes, hazards,
// landmarks) meant to make the photo easier to understand at a glance.
class SectorImageExtraDrawing extends Model
{
    protected $fillable = [
        'json',
        'canvas_width',
        'canvas_height',
        'bg_left',
        'bg_top',
        'bg_width',
        'bg_height',
        'sector_image_id',
    ];

    public function sector_image()
    {
        return $this->belongsTo(Sector_image::class, 'sector_image_id');
    }
}
