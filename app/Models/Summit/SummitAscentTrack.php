<?php

namespace App\Models\Summit;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class SummitAscentTrack extends Model
{
    protected $fillable = [
        'ascent_id', 'user_id', 'source', 'strava_activity_id', 'verified', 'closest_m',
        'distance_m', 'elevation_gain_m', 'max_elevation_m', 'duration_s', 'started_at', 'points',
    ];

    protected $casts = [
        'verified'   => 'boolean',
        'points'     => 'array',
        'started_at' => 'datetime',
    ];

    // never sent along by accident (only the owner's track endpoint returns it)
    protected $hidden = ['points'];

    public function ascent()
    {
        return $this->belongsTo(SummitAscent::class, 'ascent_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // what anyone may see next to an ascent
    public function summary(): array
    {
        return [
            'source'           => $this->source,
            'verified'         => $this->verified,
            'distance_m'       => $this->distance_m,
            'elevation_gain_m' => $this->elevation_gain_m,
            'max_elevation_m'  => $this->max_elevation_m,
            'duration_s'       => $this->duration_s,
        ];
    }
}
