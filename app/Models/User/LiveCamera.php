<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiveCamera extends Model
{
    use HasFactory;

    protected $fillable = [
        // Add all column names from your live_cameras table, for example:
        'name', 'link', 'published', 'article_id'
    ];
}
