<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_site extends Model
{
    use HasFactory;

    protected $fillable = [
        'url',
    ];
}
