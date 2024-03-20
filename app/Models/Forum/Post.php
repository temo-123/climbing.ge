<?php

namespace App\Models\Forum;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    public $table = "forum_posts";

    protected $fillable = [
        'text',
        'image',

        'sector_id',
		"route_id",
		"mtp_id",
        "region_id",
        "topic_id",
        "user_id",
    ];
}
