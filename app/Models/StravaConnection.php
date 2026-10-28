<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StravaConnection extends Model
{
    protected $fillable = ['user_id', 'athlete_id', 'athlete_name', 'access_token', 'refresh_token', 'expires_at', 'scope'];

    protected $casts = [
        'access_token'  => 'encrypted',
        'refresh_token' => 'encrypted',
        'expires_at'    => 'datetime',
    ];

    protected $hidden = ['access_token', 'refresh_token'];
}
