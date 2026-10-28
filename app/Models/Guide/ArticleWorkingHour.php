<?php

namespace App\Models\Guide;

use Illuminate\Database\Eloquent\Model;

// One weekday of an indoor gym's opening hours (weekday 1 = Monday … 7 = Sunday)
class ArticleWorkingHour extends Model
{
    protected $table = 'article_working_hours';

    protected $fillable = ['article_id', 'weekday', 'opens_at', 'closes_at', 'is_closed'];

    protected $casts = ['is_closed' => 'boolean', 'weekday' => 'integer'];

    public function article()
    {
        return $this->belongsTo(Article::class, 'article_id');
    }
}
