<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User_role extends Model
{
    public $table = 'user_role';

    protected $fillable = [
        'user_id',
        "role_id"
    ];
}
