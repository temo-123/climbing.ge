<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class Warehouse_user extends Model
{
    public $table = 'warehouse_user';

    protected $fillable = [
        'warehouse_id',
        'user_id',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function warehouse()
    {
        return $this->hasOne(Warehouse::class, 'id', 'warehouse_id');
    }
}
