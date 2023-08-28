<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ice_sector extends Model
{
    use HasFactory;

    public function article()
    {
        return $this->hasOne(Article::class, "id", "article_id");
    }

    public function routes()
    {
        return $this->hasMany(Ice_route::class)->orderBy('num');
    }

    public function images()
    {
        return $this->hasMany(Ice_sector_image::class)->orderBy('num');
    }
}
