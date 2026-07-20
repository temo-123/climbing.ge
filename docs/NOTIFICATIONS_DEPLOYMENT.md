# Notification System — Ubuntu Server Setup Guide

Full, from-scratch checklist for making every email notification in this app (`docs/NOTIFICATIONS.md`) actually send automatically on an Ubuntu server, with no manual intervention. This ties together `docs/QUEUE_WORKER.md` and `docs/CRON_SETUP.md` into one ordered walkthrough — read those two for deep troubleshooting on their respective pieces; this doc is the "set it up from zero, in order" version.

---

## Which services does each notification type need?

There are exactly two *kinds* of notification in this app, and they need different services:

| Notification type | Examples | Triggered by | Services required |
|---|---|---|---|
| **"Just" notifications** — real-time, fired the instant something happens | New article/product/sector published, a followed user comments/logs an ascent/rates a route, "you have a new follower", admin manual re-send | An Eloquent observer or controller action, immediately, in the same request | 1) **Mail transport**, 2) **Queue worker** |
| **Event notifications** — fired on a timer, not by any user action | "Your favorited event starts in 1 month / 2 weeks / 1 week / 1 day" | Server clock reaching a scheduled time, nobody has to do anything | Everything the row above needs, **PLUS** 3) **Cron scheduler** |

The reason event notifications need *all three*: the cron-triggered command (`send_event_notificatione:users`) still calls `NotificationDispatchService::sendOnce()` internally — the exact same function every other notification uses — which still just queues a job onto the `emails` queue. Cron only solves "how does the check-for-upcoming-events code get run at all"; it doesn't send anything itself. If cron is set up but the queue worker isn't, event reminders will queue correctly and then sit forever, exactly like every other notification type does without a worker.

**In short: services #1 and #2 are non-negotiable for the notification system to do anything at all. Service #3 (cron) is *additionally* required only because one specific feature (event reminders) needs to wake up on a schedule instead of being triggered by a user.**

---

## Prerequisites

- Ubuntu server (any recent LTS) with this app already deployed under `/var/www/html`, `composer install` run, `.env` present.
- PHP 8.2+ (`composer.json` requires `^8.2.0`) already installed and on `PATH` (`php -v` to confirm).
- A MySQL/MariaDB database already migrated for the app's normal tables.

Everything below assumes those are already true and focuses only on what's specific to getting notifications flowing.

---

## Step 1 — Mail transport (`.env`)

Needed by **both** notification types — nothing sends without this regardless of queue/cron state.

```
MAIL_MAILER=smtp
MAIL_HOST=<your SMTP host>
MAIL_PORT=<your SMTP port>
MAIL_USERNAME=<smtp username>
MAIL_PASSWORD=<smtp password>
MAIL_ENCRYPTION=<tls, ssl, or blank>
MAIL_FROM_ADDRESS=<address emails should appear to come from>
MAIL_FROM_NAME="${APP_NAME}"
```

Template with the dev/staging values (Mailtrap sandbox) is at `docs/BACKEND/examples/_.env .exemple`. **Mailtrap never delivers to a real inbox** — it's a sandbox for inspecting outgoing mail during development. A production Ubuntu deployment must point these at a real SMTP provider (transactional mail service, or your own mail server) or every notification will "succeed" while no real user ever receives anything.

Verify: `php artisan tinker --execute="Mail::raw('test', fn(\$m) => \$m->to('you@example.com')->subject('test'));"` — check the target inbox (or Mailtrap dashboard if still on the sandbox).

---

## Step 2 — Database + queue tables

```bash
cd /var/www/html
php artisan migrate
```

This creates (among the app's normal tables) `jobs` and `failed_jobs` — required because `QUEUE_CONNECTION=database` (already set in `.env`) stores queued jobs as database rows rather than in Redis/SQS/etc. Confirm both tables exist:

```bash
php artisan tinker --execute="echo Schema::hasTable('jobs') ? 'jobs OK' : 'MISSING'; echo Schema::hasTable('failed_jobs') ? ' failed_jobs OK' : ' MISSING';"
```

---

## Step 3 — Queue worker (Supervisor)

**Required for every notification type.** Dispatching a job (whatever triggered it) only writes a row to `jobs` — nothing sends until a `php artisan queue:work` process consumes that row. Supervisor is what keeps that process alive permanently and restarts it if it ever dies or the server reboots.

```bash
# Install supervisor if not already present
sudo apt update
sudo apt install -y supervisor

# Make sure it starts on boot
sudo systemctl enable supervisor
sudo systemctl start supervisor
```

Create `/etc/supervisor/conf.d/laravel-worker.conf`:

```ini
[program:laravel-worker]
command=php /var/www/html/artisan queue:work --queue=emails --sleep=3 --tries=3
autostart=true
autorestart=true
user=root
numprocs=1
redirect_stderr=true
stdout_logfile=/var/www/html/storage/logs/worker.log
```

**Double-check every path in that file character-by-character** — `/var/www/html`, not `/var/www/http` or any other typo. A wrong path here (e.g. a nonexistent log directory) can prevent `supervisord` itself from starting, not just this one program — this exact mistake was the root cause of notifications silently not sending during this project's development (see `docs/QUEUE_WORKER.md` for the full incident writeup).

Load the new config and start the worker:

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl status               # should show laravel-worker    RUNNING
```

Full management commands, log-tailing, and how to confirm supervisor itself (not just the worker) is healthy: `docs/QUEUE_WORKER.md`.

---

## Step 4 — Cron scheduler

**Required only for event reminder notifications** (`send_event_notificatione:users`) — and for the unrelated daily sitemap regeneration (`app:generate-sitemap`), which rides the same schedule. Skip this step if the deployment genuinely never needs event reminders; every other notification type in the app works fully without it.

```bash
# cron is preinstalled on virtually all Ubuntu server images, but confirm:
sudo systemctl status cron
# if missing:
sudo apt install -y cron
sudo systemctl enable cron
sudo systemctl start cron
```

Add the scheduler entry to the crontab of whichever user should own it (does **not** need to be root):

```bash
crontab -e
```

```
* * * * * cd /var/www/html && php artisan schedule:run >> /dev/null 2>&1
```

Laravel's scheduler checks every minute what's actually due and only runs commands whose time has come — this single line is the entire cron footprint needed, no matter how many `->daily()`/`->hourly()`/etc. commands exist in `app/Console/Kernel.php`. Full verification steps, the app-timezone-vs-server-timezone gotcha for `->daily()`, and troubleshooting: `docs/CRON_SETUP.md`.

---

## Step 5 — End-to-end verification

Do these **in order** — each rules out one layer before testing the next.

**5a. Queue worker alone** (proves services #1 + #2 work, independent of cron):
```bash
php artisan tinker --execute="
App\Jobs\UserNotifications::dispatch('https://example.com','test text','test subject','you@example.com')->onQueue('emails');
echo 'queued: '.DB::table('jobs')->count();
"
sleep 5
php artisan tinker --execute="echo 'still queued: '.DB::table('jobs')->count().' | failed: '.DB::table('failed_jobs')->count();"
```
Expect the job count to drop back to whatever it was before (worker picked it up) and `failed_jobs` to stay unchanged. Check the destination inbox/Mailtrap.

**5b. A real trigger** (proves an actual observer/controller path works, e.g. follow someone in the app, or publish a piece of content with an active subscriber) — confirm a row appears in `content_notification_logs` and the job drains from `jobs` within a few seconds.

**5c. Cron** (only if Step 4 was done):
```bash
crontab -l                            # line is present
php artisan schedule:list             # both daily commands show a "Next Due" time
grep CRON /var/log/syslog | tail -5   # proof cron has actually been invoking schedule:run every minute
```

---

## Quick reference: symptom → which service to check

| Symptom | Check |
|---|---|
| Nothing ever sends, for anything | Step 1 (mail creds correct?) then Step 3 (`supervisorctl status` — is it RUNNING?) |
| Jobs pile up in `jobs` table and never drain | Step 3 — worker isn't running or crash-looping; `docs/QUEUE_WORKER.md` |
| Jobs appear in `failed_jobs` | Mail transport is reachable but rejecting — check `MAIL_*` creds/host, read the `exception` column |
| Everything else works, but event reminders specifically never arrive | Step 4 — cron not installed/enabled, or the crontab line is missing/wrong; `docs/CRON_SETUP.md` |
| Event reminders' cron command runs (per `schedule:list`) but the email still never shows up | Not a cron problem — the command dispatched correctly, fall back to the queue-worker checks above |
