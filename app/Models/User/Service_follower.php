<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Service_follower extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
}
