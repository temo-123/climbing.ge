<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ka_product extends Model
{
    public $table = 'ka_products';

    protected $fillable = [
        'title',
        'text',
    ];
}
