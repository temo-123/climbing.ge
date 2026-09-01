<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiveCamera extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'link', 'published', 'article_id', 'source', 'ubia_device_uid'
    ];

    public function photos()
    {
        return $this->hasMany(LiveCameraPhoto::class)->orderByDesc('created_at');
    }
}
