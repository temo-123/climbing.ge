<?php

namespace App\Models\Guide;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class PartnerPost extends Model
{
    const DISCIPLINES = ['sport', 'boulder', 'multipitch', 'trad', 'ice', 'alpine', 'indoor'];

    // a climber can have this many upcoming posts at once
    const MAX_OPEN_PER_USER = 5;

    protected $fillable = [
        'user_id',
        'article_id',
        'place',
        'climb_date',
        'discipline',
        'grade_from',
        'grade_to',
        'note',
        'contact',
        'admin_hidden',
    ];

    protected $casts = [
        'climb_date' => 'date:Y-m-d',
        'admin_hidden' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function article()
    {
        return $this->belongsTo(Article::class, 'article_id');
    }
}
