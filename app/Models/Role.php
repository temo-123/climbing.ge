<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\User;

class Role extends Model
{
    public function users()
    {
        // return $this->belongsToMany(User::class);
        return $this->belongsToMany(User::class, 'user_role', 'role_id', 'user_id');
    }

    // public function users()
    // {
    //     return $this->hasMany(User::class);
    // }

    public function permissions()
    {
        // return $this->belongsToMany(Permission::class,'roles_permissions');
        return $this->belongsToMany(Permission::class, 'role_permission', 'role_id', 'permission_id');
    }
}
