<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_permission extends Model
{
    use HasFactory;

    public $table = 'user_permissions';
}
