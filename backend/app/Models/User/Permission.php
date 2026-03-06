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
        return $this->belongsToMany(Role::class, 'roles_permissions', 'role_id', 'permission_id');
    }

    public function user()
    {
        return $this->belongsToOne(User::class, 'user_role', 'user_id', 'role_id');
    }
}
