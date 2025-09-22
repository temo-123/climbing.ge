<?php

namespace App\Models\Blog;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        // Add all column names from your posts table, for example:
        'name', 'image', 'published'
    ];

    protected $table = 'posts';
}
