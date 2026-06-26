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
    ];

    public function pitch()
    {
        return $this->belongsTo(Mtp_pitch::class, 'mtp_pitch_id');
    }
}
