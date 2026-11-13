<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

/** docs/FUNCTIONS_DOCS/user/seasonal_campaigns.md */
class SeasonalCampaign extends Model
{
    protected $table = 'seasonal_campaigns';

    protected $fillable = [
        'name', 'content_type', 'category', 'sun_filter', 'start_md', 'end_md', 'every_days',
        'items_per_mail', 'pick', 'audience', 'inactive_days', 'max_per_run',
        'subject_us', 'subject_ka', 'intro_us', 'intro_ka', 'enabled',
    ];

    protected $casts = [
        'enabled'        => 'boolean',
        'every_days'     => 'integer',
        'items_per_mail' => 'integer',
        'inactive_days'  => 'integer',
        'max_per_run'    => 'integer',
        'last_run_sent'  => 'integer',
        'last_run_at'    => 'datetime',
    ];

    /** content_notification_logs.notification_key for this campaign's sends */
    public function logKey(): string
    {
        return 'seasonal_' . $this->id;
    }
}
