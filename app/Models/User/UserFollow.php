<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class UserFollow extends Model
{
    protected $table = 'user_follows';

    protected $fillable = ['follower_id', 'followed_id'];
}
