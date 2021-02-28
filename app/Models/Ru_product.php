<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ru_product extends Model
{
    public $table = 'ru_products';

    protected $fillable = [
        'title',
        'short_description',
        'text',
        'meta_keyword'
    ];
}
