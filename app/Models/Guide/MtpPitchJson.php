<?php

namespace App\Models\Guide;

use Illuminate\Database\Eloquent\Model;

class MtpPitchJson extends Model
{
    public $table = 'mtp_pitch_jsons';

    protected $fillable = [
        'json',
        'mtp_pitch_id',
        'sector_image_id',
        'canvas_width',
        'canvas_height',
        'bg_left',
        'bg_top',
        'bg_width',
        'bg_height',
    ];

    public function pitch()
    {
        return $this->belongsTo(Mtp_pitch::class, 'mtp_pitch_id');
    }
}
