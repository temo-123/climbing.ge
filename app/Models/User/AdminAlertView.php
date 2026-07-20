<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class AdminAlertView extends Model
{
    protected $table = 'admin_alert_views';

    protected $fillable = ['user_id', 'alert_key', 'last_viewed_at'];

    protected $casts = [
        'last_viewed_at' => 'datetime',
    ];
}
