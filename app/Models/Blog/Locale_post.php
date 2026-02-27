<?php

namespace App\Models\Blog;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Locale_post extends Model
{
    use HasFactory;

    protected $fillable = [
        'locale',
        'title',
        'short_description',
        'text',
    ];

    protected $table = 'locale_posts';
}
