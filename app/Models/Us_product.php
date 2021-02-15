<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Us_product extends Model
{
    public $table = 'us_products';

    protected $fillable = [
        'title',
        'text',
    ];
}
