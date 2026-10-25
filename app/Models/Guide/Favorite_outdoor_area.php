<?php

namespace App\Models\Guide;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Favorite_outdoor_area extends Model
{
    // use HasFactory;

    use HasApiTokens, HasFactory, Notifiable;

    // Despite the table name this holds favourites for every guide article
    // type except news / special articles (blog posts and films have their own)
    const CATEGORIES = ['outdoor', 'ice', 'indoor', 'mount_route', 'other', 'tech_tip', 'partners', 'spot_projects'];

    protected $fillable = [
	    'user_id',
	    'article_id',
	];

    public function outdoor_area()
    {
		return $this->hasOne(Article::class, 'id', 'article_id');
    }
}
