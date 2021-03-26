<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    public $table = 'services';

    protected $fillable = [
        'url_title',
    ];
}
