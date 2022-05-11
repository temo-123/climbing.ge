<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\User;

class Role extends Model
{
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class,'roles_permissions');
    }
}
