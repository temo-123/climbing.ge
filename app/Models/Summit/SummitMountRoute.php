<?php

namespace App\Models\Summit;

use Illuminate\Database\Eloquent\Model;
use App\Models\Guide\Article;

class SummitMountRoute extends Model
{
    protected $table = 'summit_mount_routes';

    protected $fillable = ['summit_id', 'article_id'];

    public function summit()
    {
        return $this->belongsTo(Summit::class, 'summit_id');
    }

    public function article()
    {
        return $this->belongsTo(Article::class, 'article_id');
    }
}
