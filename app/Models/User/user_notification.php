<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class user_notification extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'notification_type'];

    protected $casts = [
        'notification_type' => 'array',
    ];

	public function user()
	{
		return $this->hasOne(User::class, 'id', 'user_id');
	}
}
