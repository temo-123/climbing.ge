<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ru_service extends Model
{
    public $table = 'ru_services';

    protected $fillable = [
        'title',
        'short_description',
        'text',
        'meta_keyword'
    ];
}
