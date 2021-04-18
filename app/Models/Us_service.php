<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Us_service extends Model
{
    public $table = 'us_services';

    protected $fillable = [
        'title',
        'short_description',
        'text',
        'meta_keyword'
    ];
}
