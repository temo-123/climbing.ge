<?php

namespace App\Models\Blog;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        // Add all column names from your posts table, for example:
        'name', 'image', 'published', 'user_id', 'us_post_id', 'ka_post_id'
    ];

    protected $table = 'posts';

    public function us_post()
    {
        return $this->belongsTo(Locale_post::class, 'us_post_id');
    }

    public function ka_post()
    {
        return $this->belongsTo(Locale_post::class, 'ka_post_id');
    }
}
