<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    public $table = 'carts';

    protected $fillable = [
        'user_id',
        'opton_id',
        'quantity',
    ];
}
