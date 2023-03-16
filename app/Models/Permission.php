<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    // public $table = 'permissions';

    public function roles()
    {
        // return $this->belongsToMany(Role::class, 'roles_permissions');
        
        return $this->belongsToMany(Role::class, 'roles_permissions', 'role_id', 'permission_id');
    }

    // public function roles()
    // {
    //     return $this->belongsToMany(Role::class, 'roles_permissions');
    // }
}
