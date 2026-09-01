<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class LiveCameraPhoto extends Model
{
    protected $fillable = ['live_camera_id', 'image'];

    public function live_camera()
    {
        return $this->belongsTo(LiveCamera::class);
    }
}
