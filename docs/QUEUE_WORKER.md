# Queue Worker (Automating Email Delivery)

Every notification email in this app (`docs/NOTIFICATIONS.md`) goes through `App\Jobs\UserNotifications`, dispatched onto the `emails` queue with `QUEUE_CONNECTION=database` (see `.env`). Dispatching a job only **writes a row to the `jobs` table** — nothing sends the actual email until a **queue worker** process picks that row up and runs the job. If no worker is running, jobs just accumulate silently: no error, no email, nothing in `failed_jobs` either, because nothing ever attempted them.

This is exactly what was happening before this fix — no worker was running, so every dispatched notification just sat in `jobs` forever.

---

## The permanent fix: supervisor

A supervisor program is already defined for this, at `/etc/supervisor/conf.d/laravel-worker.conf`:

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

`autostart=true` + `autorestart=true` means: once supervisor itself is running, it starts this worker automatically and restarts it forever if it ever crashes or the server reboots. This is the standard way to keep a Laravel queue worker alive in production — no manual `nohup`/background process needed.

**What was actually broken:** the config file had a typo — `stdout_logfile=/var/www/http/storage/logs/worker.log` (**http**, not **html**). Since `/var/www/http/` doesn't exist, `supervisord` refused to start at all — not just the worker, the whole supervisor daemon — and `systemd` had been silently crash-looping it every ~50 seconds for a very long time (restart counter was over 2000). Fixed by correcting the path to `/var/www/html/`.

### Checking / managing it

```bash
sudo supervisorctl status                    # is laravel-worker RUNNING?
sudo supervisorctl restart laravel-worker    # restart just the worker
sudo supervisorctl reread && sudo supervisorctl update   # after editing the .conf file
tail -f /var/www/html/storage/logs/worker.log            # watch it process jobs live
```

If you ever change the command (e.g. add `--queue=` for another queue, adjust `--tries`), edit `/etc/supervisor/conf.d/laravel-worker.conf`, then run `reread && update` (or just `sudo systemctl restart supervisor`) to apply it.

### Confirming supervisor itself is healthy

```bash
sudo systemctl status supervisor    # should say "active (running)", not "activating (auto-restart)"
journalctl -u supervisor -n 20 --no-pager   # crash-loop errors show up here (like the typo above did)
```

---

## Quick manual test (no supervisor needed)

Useful for a one-off check without touching the service:

```bash
php artisan queue:work --queue=emails --sleep=3 --tries=3 --once   # process exactly one pending job then exit
```

Or to watch the queue continuously in a foreground terminal (Ctrl+C to stop):

```bash
php artisan queue:work --queue=emails --sleep=3 --tries=3
```

## Verifying end-to-end

```bash
php artisan tinker --execute="
App\Jobs\UserNotifications::dispatch('https://example.com','test text','test subject','you@example.com')->onQueue('emails');
echo DB::table('jobs')->count();   // 1 right after dispatch
"
# wait a few seconds (SMTP handshake to Mailtrap takes ~3s), then:
php artisan tinker --execute="echo DB::table('jobs')->count();"        // should be back to 0
php artisan tinker --execute="echo DB::table('failed_jobs')->count();" // should still be 0
```

Check the Mailtrap inbox configured in `.env` (`MAIL_HOST=smtp.mailtrap.io`) for the result — this is a sandbox, not a real mailbox, so nothing gets delivered to actual users while testing.

## Alternative: Laravel Horizon

`CLAUDE.md` lists `php artisan horizon` as an available command — Horizon is a dashboard + supervisor-like process manager built specifically for Laravel queues (metrics, retry UI, per-queue throughput). It's not currently configured to run persistently here (no supervisor entry for it), but if the manual `queue:work` approach ever becomes hard to observe, wiring Horizon in behind its own supervisor program (same pattern as `laravel-worker` above, just running `php artisan horizon` instead) is the natural upgrade path.
