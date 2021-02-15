<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Interested_Event extends Model
{
    public $table = 'interested_events';

    protected $fillable = [
        "user_id",
        "article_id",
    ];
}
