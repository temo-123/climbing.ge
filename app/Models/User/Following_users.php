<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Following_users extends Model
{
    use HasFactory;

    public $table = 'service_followers';
}
