# Cron Setup (Scheduled Notification Commands)

Two of the commands behind `docs/NOTIFICATIONS.md` only ever run on a schedule — they are never triggered by a user action, an API call, or a queue job. Both are registered in `app/Console/Kernel.php`:

```php
protected function schedule(Schedule $schedule)
{
    $schedule->command('send_event_notificatione:users')->daily();  // event reminder emails
    $schedule->command('app:generate-sitemap')->daily();
}
```

- **`send_event_notificatione:users`** (`App\Console\Commands\UserAutoNotificatione`) — finds events approaching in the next 1 month / 2 weeks / 1 week / 1 day and emails users who favorited them (see [Event Reminder Cron](NOTIFICATIONS.md#event-reminder-cron)). If this never runs, event reminder emails never go out — not a mail or queue problem, the notification is simply never dispatched in the first place.
- **`app:generate-sitemap`** — regenerates the sitemap. Unrelated to notifications, but scheduled the same way, so it's covered by the same setup below.

---

## The one thing that has to exist: a crontab entry

Laravel's scheduler (`artisan schedule:run`) is what actually decides "is anything due right now" — but nothing calls it on its own. The **entire** server-side requirement is one crontab line, running every minute, as whichever user owns the deployment (not necessarily root):

```
* * * * * cd /var/www/html && php artisan schedule:run >> /dev/null 2>&1
```

That's it — no supervisor program, no separate service, unlike the queue worker (`docs/QUEUE_WORKER.md`). `schedule:run` itself is cheap: on a minute where nothing is due, it does nothing and exits immediately. On this server it's installed under the deploying user's own crontab (`crontab -e`, not `/etc/cron.d/`), using the absolute `php` path:

```
* * * * * cd /var/www/html && /usr/bin/php artisan schedule:run >> /dev/null 2>&1
```

## Verifying it's actually working

```bash
crontab -l                                  # confirm the line is present
php artisan schedule:list                   # shows both commands + when each is next due
php artisan schedule:run -v                 # run the scheduler once by hand — safe, only fires commands that are actually due
grep CRON /var/log/syslog | tail -5         # proof cron itself has been invoking schedule:run every minute
```

`schedule:list` output looks like:
```
0 0 * * *  php artisan send_event_notificatione:users  Next Due: X hours from now
0 0 * * *  php artisan app:generate-sitemap ..... Next Due: X hours from now
```

Both are `daily()`, which fires at **midnight in the app's configured timezone** (`config('app.timezone')`, currently `env('TIMEZONE', 'Asia/Tbilisi')`) — **not** necessarily the server's OS timezone or UTC. If the server clock/timezone and `TIMEZONE` in `.env` disagree, "daily" still fires correctly (Laravel computes due-time in the app timezone regardless of server TZ), but manually reasoning about "why hasn't it run yet" needs to account for which timezone you're checking against.

## Troubleshooting

- **Nothing in `schedule:list`** — the command classes aren't registered/autoloaded; check `app/Console/Kernel.php` and that `app/Console/Commands/` files exist.
- **`schedule:list` looks right but nothing happens at midnight** — the crontab line itself is missing or using the wrong PHP binary/working directory. Confirm with `crontab -l` and `grep CRON /var/log/syslog`; if syslog shows no entries at all, cron isn't invoking anything for this user (check `crontab -l` was saved for the correct user, and that the user's cron access isn't restricted via `/etc/cron.allow`/`/etc/cron.deny`).
- **Commands run but the email itself never arrives** — this is no longer a cron problem; the command dispatched a job correctly, so switch to `docs/QUEUE_WORKER.md` (is a worker actually running?) and the mail-transport checks in `docs/NOTIFICATIONS.md#server-requirements`.
- **Silent errors inside the command** — by default `schedule:run` output goes to `/dev/null` per the crontab line above, so a command that throws will still show up in `storage/logs/laravel.log`, just not in any cron-specific log. To capture scheduler output separately instead of relying on the Laravel log, chain `->appendOutputTo(storage_path('logs/schedule.log'))` on either command in `Kernel.php`.
