<?php

namespace App\Console\Commands;

use App\Services\Seo\SearchConsoleClient;
use App\Services\Seo\SearchConsoleSync;
use Illuminate\Console\Command;

class SyncSearchConsole extends Command
{
    protected $signature = 'app:sync-search-console {--days=5 : days to (re)read; 480 = full 16-month back-fill} {--check : only test the credentials and list the properties}';

    protected $description = 'Import Google Search Console clicks / impressions / positions for the admin SEO dashboard';

    public function handle(): int
    {
        if (! SearchConsoleClient::configured()) {
            $this->warn('Search Console is not configured (services.search_console) — see docs/FUNCTIONS_DOCS/user/seo_dashboard.md');
            return self::SUCCESS;
        }

        try {
            $client = app(SearchConsoleClient::class);
            if ($this->option('check')) {
                $this->info('Service account: ' . $client->serviceAccountEmail());
                foreach ($client->sites() as $site => $level) {
                    $this->line("  {$site}  ({$level})");
                }
                return self::SUCCESS;
            }
            $status = app(SearchConsoleSync::class)->run((int) $this->option('days'));
            $this->info("Synced {$status['from']} … {$status['to']}: {$status['pages']} page-days, {$status['queries']} page queries");
            return self::SUCCESS;
        } catch (\Throwable $e) {
            SearchConsoleSync::recordFailure($e);
            $this->error($e->getMessage());
            return self::FAILURE;
        }
    }
}
