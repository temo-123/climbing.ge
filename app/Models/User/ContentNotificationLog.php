<?php

namespace App\Models\User;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ContentNotificationLog extends Model
{
    protected $fillable = [
        'user_id',
        'notifiable_type',
        'notifiable_id',
        'notification_key',
        'sent_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
