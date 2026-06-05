<?php

namespace App\Models\Summit;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Guide\Article;

class SummitAscentRoute extends Model
{
    use HasFactory;

    protected $fillable = [
        'ascent_id',
        'article_id',
        'other_route_name',
    ];

    public function ascent()
    {
        return $this->belongsTo(SummitAscent::class, 'ascent_id');
    }

    public function article()
    {
        return $this->belongsTo(Article::class, 'article_id');
    }
}
