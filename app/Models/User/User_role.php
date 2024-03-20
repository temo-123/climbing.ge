<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class User_role extends Model
{
    public $table = 'user_role';

    protected $fillable = [
        'user_id',
        "role_id"
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function role()
    {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }
}
