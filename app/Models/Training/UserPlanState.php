<?php

namespace App\Models\Training;

use Illuminate\Database\Eloquent\Model;

class UserPlanState extends Model
{
    protected $guarded = [];

    protected $table = 'user_plan_states';

    public $timestamps = false;

    protected $casts = [
        'notification_ids' => 'array',
        'calendar_event_ids' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}
