<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        // $schedule->command('inspire')->hourly();

        $schedule->command('send_event_notificatione:users')->daily();
        $schedule->command('app:generate-sitemap')->daily();
        $schedule->command('app:generate-merchant-feed')->daily();
        $schedule->command('app:send-back-in-stock')->hourly();
        // seasonal content picks (docs/FUNCTIONS_DOCS/user/seasonal_campaigns.md) — late morning, when people read mail
        $schedule->command('app:send-seasonal-campaigns')->dailyAt('10:30')->withoutOverlapping();
        // admin SEO dashboard — Search Console data (skips itself when not configured)
        $schedule->command('app:sync-search-console')->dailyAt('05:30')->withoutOverlapping();
        // ->daily();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
