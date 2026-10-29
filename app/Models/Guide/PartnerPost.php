<?php

namespace App\Models\Guide;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class PartnerPost extends Model
{
    const DISCIPLINES = ['sport', 'boulder', 'multipitch', 'trad', 'ice', 'alpine', 'indoor'];

    // a climber can have this many upcoming posts at once
    const MAX_OPEN_PER_USER = 5;

    // a trip is at most this many days long
    const MAX_TRIP_DAYS = 30;

    // languages a poster can say they speak (frontend labels: guide.partners.languages.*)
    const LANGUAGES = ['ka', 'en', 'ru', 'uk', 'de', 'fr', 'es', 'it', 'pl', 'tr', 'he', 'zh'];

    protected $fillable = [
        'user_id',
        'article_id',
        'place',
        'climb_date',
        'end_date',
        'discipline',
        'grade_from',
        'grade_to',
        'languages',
        'note',
        'contact',
        'admin_hidden',
    ];

    protected $casts = [
        'climb_date' => 'date:Y-m-d',
        'end_date' => 'date:Y-m-d',
        'languages' => 'array',
        'admin_hidden' => 'boolean',
    ];

    // still on the board: the trip's last day (end_date, or climb_date for a
    // one-day trip) is today or later
    public function scopeNotOver($query)
    {
        return $query->whereRaw('COALESCE(end_date, climb_date) >= ?', [now()->toDateString()]);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function article()
    {
        return $this->belongsTo(Article::class, 'article_id');
    }
}
