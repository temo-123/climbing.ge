<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class Permission extends Model
{
    use HasFactory;

    public $table = 'permissions';

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permissions', 'permission_id', 'role_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
