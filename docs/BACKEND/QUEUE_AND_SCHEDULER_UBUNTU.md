# Queue & Scheduler on an Ubuntu Server — step-by-step

How to make climbing.ge's background work run by itself on a fresh (or existing) Ubuntu 22.04 / 24.04 server: queued e-mails, scheduled commands (event reminders, back-in-stock, sitemap, merchant feed, Search Console sync). Do it once per server; after that only [Step 4](#step-4--after-every-deploy) is repeated on each deploy.

Background and history: [QUEUE_WORKER.md](../QUEUE_WORKER.md) (worker), [CRON_SETUP.md](../CRON_SETUP.md) (scheduler), [NOTIFICATIONS.md](../NOTIFICATIONS.md) (what gets queued).

## Table of Contents
- [How it fits together](#how-it-fits-together)
- [Step 1 — Check the .env](#step-1--check-the-env)
- [Step 2 — Queue worker with Supervisor](#step-2--queue-worker-with-supervisor)
- [Step 3 — Scheduler with cron](#step-3--scheduler-with-cron)
- [Step 4 — After every deploy](#step-4--after-every-deploy)
- [Step 5 — Check it works](#step-5--check-it-works)
- [Troubleshooting](#troubleshooting)
- [Optional — switch to Horizon](#optional--switch-to-horizon)

---

## How it fits together

```
cron (every minute)
  └─ php artisan schedule:run          ← decides what is due (app/Console/Kernel.php)
       ├─ send_event_notificatione:users   daily     ─┐
       ├─ app:send-back-in-stock           hourly    ─┤ dispatch UserNotifications jobs
       ├─ app:send-seasonal-campaigns      10:30      │ (+ SeasonalCampaignMessage,
       ├─ app:generate-sitemap             daily      │  WelcomeEmailNotification —
       │                                              │  rows in the `jobs` table,
       ├─ app:generate-merchant-feed       daily      │  queue `emails`)
       └─ app:sync-search-console          05:30      │
                                                      │
web requests (new sector / product / follow / …) ─────┘
                                                      ▼
supervisor  →  php artisan queue:work --queue=emails,default   ← sends the mail (SMTP)
```

Two separate things must be running, and **either one missing fails silently**:

| Piece | Runs | If it's missing |
|---|---|---|
| **Cron → scheduler** | `schedule:run` every minute | Event reminders, back-in-stock mails, sitemap / feed refresh never happen |
| **Supervisor → worker** | `queue:work`, always on | Jobs pile up in the `jobs` table; no e-mail is sent, nothing lands in `failed_jobs` |

All notification mail is queued on the **`emails`** queue: `App\Jobs\UserNotifications` (content alerts), `SeasonalCampaignMessage` (seasonal campaigns) and `WelcomeEmailNotification`. The worker must listen on `emails` — a plain `queue:work` only listens on `default` and would never send them.

---

## Step 1 — Check the .env

```dotenv
QUEUE_CONNECTION=database     # jobs are rows in the `jobs` table (no Redis needed)
TIMEZONE=Asia/Tbilisi         # "daily" = midnight in this zone
MAIL_MAILER=smtp              # + MAIL_HOST / MAIL_PORT / MAIL_USERNAME / MAIL_PASSWORD / MAIL_FROM_ADDRESS
```

- `QUEUE_CONNECTION=sync` (the config default) sends mail **inside the web request** — slow pages, and a mail error breaks the request. Use `database`.
- The `jobs` and `failed_jobs` tables must exist: `php artisan migrate` creates them.

Apply after editing: `php artisan config:clear && php artisan config:cache`.

---

## Step 2 — Queue worker with Supervisor

Supervisor keeps the worker running: it starts it on boot and restarts it if it crashes.

**2.1 Install**

```bash
sudo apt update
sudo apt install -y supervisor
sudo systemctl enable --now supervisor
```

**2.2 Create the program** — `sudo nano /etc/supervisor/conf.d/laravel-worker.conf`:

```ini
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=/usr/bin/php /var/www/html/artisan queue:work database --queue=emails,default --sleep=3 --tries=3 --backoff=60 --max-time=3600
directory=/var/www/html
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/var/www/html/storage/logs/worker.log
stopwaitsecs=120
```

What the options do:

| Option | Why |
|---|---|
| `--queue=emails,default` | `emails` first (all notification mail), then anything on `default` |
| `--tries=3 --backoff=60` | A failed send (SMTP hiccup) is retried 3 times, 60 s apart, then goes to `failed_jobs` |
| `--max-time=3600` | Worker exits after 1 hour and Supervisor starts a fresh one — avoids memory leaks and stale code |
| `user=www-data` | Same user as PHP-FPM, so files it writes in `storage/` stay writable for the site. Running as `root` leaves root-owned log / cache files that the website then can't write |
| `stopwaitsecs=120` | Gives a job that's mid-send time to finish on restart |
| `numprocs=1` | Enough for this site's mail volume; raise to 2–3 if `jobs` regularly backs up |

> **Paths:** check every path in this file really exists (`/usr/bin/php` → `which php`, `/var/www/html`). One typo (`/var/www/http`) once stopped the whole Supervisor daemon for months — see QUEUE_WORKER.md.

**2.3 Permissions** (only needed once, or if the worker log says "Permission denied"):

```bash
sudo chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
sudo chmod -R ug+rwX /var/www/html/storage /var/www/html/bootstrap/cache
```

**2.4 Load and start it**

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl status          # laravel-worker:laravel-worker_00   RUNNING   pid 1234, uptime 0:00:05
```

---

## Step 3 — Scheduler with cron

Laravel's scheduler needs **one** cron line that calls it every minute. Put it in the crontab of the same user as the worker:

```bash
sudo crontab -u www-data -e
```

Add:

```cron
* * * * * cd /var/www/html && /usr/bin/php artisan schedule:run >> /dev/null 2>&1
```

Check:

```bash
sudo crontab -u www-data -l
php artisan schedule:list           # every scheduled command + "Next Due"
```

> If the existing server already has this line in another user's crontab (e.g. the deploy user's `crontab -e`), keep **only one** — two lines mean every command runs twice and users get duplicate reminders.

To log what the scheduler runs, add `->appendOutputTo(storage_path('logs/schedule.log'))` to a command in `app/Console/Kernel.php`.

---

## Step 4 — After every deploy

A running worker keeps the **old code in memory** until it restarts. After `git pull` / `composer install` / `php artisan migrate`:

```bash
cd /var/www/html
php artisan optimize:clear          # config, route, view, event caches
php artisan config:cache
php artisan route:cache
php artisan queue:restart           # workers finish their current job, exit, Supervisor starts fresh ones
```

`queue:restart` is graceful — no mail is lost. The cron line needs nothing; it picks up new code on the next minute.

If you changed `laravel-worker.conf` itself: `sudo supervisorctl reread && sudo supervisorctl update`.

---

## Step 5 — Check it works

```bash
sudo systemctl status supervisor                 # active (running)
sudo supervisorctl status                        # laravel-worker … RUNNING
php artisan schedule:list                        # commands listed with Next Due
grep CRON /var/log/syslog | tail -3              # cron is calling schedule:run every minute
php artisan tinker --execute="echo DB::table('jobs')->count(), ' waiting, ', DB::table('failed_jobs')->count(), ' failed';"
```

End-to-end test — queue one mail to yourself, then watch it leave:

```bash
php artisan tinker --execute="App\Jobs\UserNotifications::dispatch('https://climbing.ge','Queue test','Queue test','YOUR@EMAIL')->onQueue('emails');"
tail -f storage/logs/worker.log                  # … App\Jobs\UserNotifications … DONE
```

`jobs` should go back to 0 within a few seconds and the mail should arrive.

---

## Troubleshooting

| Symptom | Cause / fix |
|---|---|
| `jobs` count keeps growing | No worker, or it listens on the wrong queue. `supervisorctl status`; the command must contain `--queue=emails` |
| `supervisorctl status` → `FATAL` / `BACKOFF` | Read `storage/logs/worker.log` and `journalctl -u supervisor -n 30`. Usually a wrong path or PHP binary, or storage permissions |
| `systemctl status supervisor` → `activating (auto-restart)` | Supervisor itself can't start — a bad path in **any** `.conf` (e.g. a log path whose folder doesn't exist) |
| Mail sends only after a deploy / restart | Worker running old code or stuck; `php artisan queue:restart` |
| Jobs in `failed_jobs` | `php artisan queue:failed` shows the error (usually SMTP login / host). Fix, then `php artisan queue:retry all` |
| Event reminders never arrive but other mail does | Cron line missing / wrong user: `sudo crontab -u www-data -l`, `grep CRON /var/log/syslog` |
| Every reminder arrives twice | `schedule:run` is in two crontabs — keep one |
| "Permission denied" on `storage/logs/laravel.log` | A process ran as root and created root-owned files. Repeat step 2.3 |
| Mail sent but never received | Mail-side problem (SMTP / SPF / DKIM), not the queue — see NOTIFICATIONS.md#server-requirements |

Useful commands:

```bash
php artisan queue:failed               # list failed jobs
php artisan queue:retry all            # retry them
php artisan queue:flush                # delete all failed jobs
php artisan queue:work --queue=emails,default --once   # process one job by hand (debugging)
```

---

## Optional — switch to Horizon

`laravel/horizon` is installed (dashboard + process manager at `/horizon`, gated in `app/Providers/HorizonServiceProvider.php`). It needs **Redis**, and `config/horizon.php` currently only watches the `default` queue, so as configured it would **not** send any notification mail. To switch:

1. `sudo apt install -y redis-server && sudo systemctl enable --now redis-server`, and the PHP extension (`sudo apt install php8.2-redis`).
2. `.env`: `QUEUE_CONNECTION=redis` (+ `REDIS_HOST=127.0.0.1`).
3. `config/horizon.php` → `defaults.supervisor-1.queue` = `['emails', 'default']`, and `tries` = `3`.
4. Replace the Supervisor program's `command` with `/usr/bin/php /var/www/html/artisan horizon` (one process; Horizon starts its own workers).
5. On deploy use `php artisan horizon:terminate` instead of `queue:restart`.
6. Add `$schedule->command('horizon:snapshot')->everyFiveMinutes();` to `Kernel.php` for the dashboard's charts.

Jobs already waiting in the `jobs` table are **not** moved to Redis — let the database worker empty it first.

---

[Go back](../README.md)
