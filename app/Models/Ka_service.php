<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ka_service extends Model
{
    public $table = 'ka_services';

    protected $fillable = [
        'title',
        'short_description',
        'text',
        'meta_keyword'
    ];
}
