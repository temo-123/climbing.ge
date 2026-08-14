<?php

namespace App\Models\Training;

use Illuminate\Database\Eloquent\Model;

class UserWorkout extends Model
{
    protected $guarded = [];

    protected $table = 'user_workouts';

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}
