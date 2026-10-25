<?php

namespace App\Models\Guide;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class AreaConditionReport extends Model
{
    // dry / wet / … describe the rock or ice; hazard and closure are warnings
    // shown as a banner on the area page while they're recent
    const CONDITIONS = ['dry', 'wet', 'ice_good', 'ice_poor', 'crowded', 'hazard', 'closure', 'info'];
    const WARNINGS = ['hazard', 'closure'];

    // public feed window; warnings stay in the banner for WARNING_DAYS
    const FEED_DAYS = 60;
    const WARNING_DAYS = 30;

    protected $fillable = [
        'article_id',
        'user_id',
        'condition',
        'note',
        'admin_hidden',
    ];

    protected $casts = [
        'admin_hidden' => 'boolean',
    ];

    public function article()
    {
        return $this->belongsTo(Article::class, 'article_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
