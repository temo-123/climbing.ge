<?php

namespace App\Console\Commands;

use App\Models\User\SeasonalCampaign;
use App\Services\SeasonalCampaignService;
use Illuminate\Console\Command;

/** Daily: every enabled seasonal campaign that is in season mails its due users. docs/FUNCTIONS_DOCS/user/seasonal_campaigns.md */
class SendSeasonalCampaigns extends Command
{
    protected $signature = 'app:send-seasonal-campaigns {--campaign= : only this campaign id (also when disabled)} {--dry : count only, send nothing}';

    protected $description = 'Send seasonal content picks (ice in winter, crags in summer, …) to due users';

    public function handle(): int
    {
        $query = SeasonalCampaign::query();
        $this->option('campaign') ? $query->whereKey($this->option('campaign')) : $query->where('enabled', true);

        foreach ($query->orderBy('id')->get() as $campaign) {
            $r = SeasonalCampaignService::run($campaign, (bool) $this->option('dry'));
            $this->line(sprintf(
                '#%d %s: %s sent=%d checked=%d throttled=%d nothing_left=%d',
                $campaign->id, $campaign->name, $r['skipped'] ?? 'ran', $r['sent'], $r['checked'], $r['throttled'], $r['nothing_left']
            ));
        }
        return self::SUCCESS;
    }
}
