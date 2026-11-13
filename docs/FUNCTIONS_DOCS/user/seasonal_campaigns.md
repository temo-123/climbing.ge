# Seasonal campaigns

Seasonal campaigns send each person content picked for **this time of year**: ice in early winter, shady sectors on hot summer days, big peaks in mid-summer, sunny sectors in autumn. Admins set up each campaign once. A daily job then mails every person who is due, with 1–3 items they haven't seen yet.

Admin page: **user.climbing.ge/seasonal_campaigns** (menu: Site options → Seasonal campaigns, permission `seasonal_campaign`).

## Design concept

- **Right moment, not more mail.** A campaign only runs inside its season window. A person gets at most one mail per campaign every `every_days` days, and never two seasonal mails (from any campaigns) within **3 days** (`GLOBAL_GAP_DAYS`).
- **Always something new.** Each item goes to a person at most once per campaign, and favourited areas are skipped. When a person has seen everything, the campaign stops writing to them.
- **Not the same mail for everyone.** "Popular" picks randomly among the 10 most popular items, so two people rarely get the same mail.
- **Easy to switch off.** Every mail has a "Choose which emails you get" link and a `List-Unsubscribe` header. People switch these mails off under Options → Notifications → **Seasonal picks** (`seasonal_picks`, on by default).

## A campaign

Table `seasonal_campaigns` (model `App\Models\User\SeasonalCampaign`):

| Field | Meaning |
|---|---|
| `content_type` | `article` (areas, routes, gyms, tips of a category) or `sector` (sectors of the category's areas) |
| `category` | `articles.category`: `outdoor`, `ice`, `mount_route`, `indoor`, `tech_tip`, `news`, `other` |
| `sun_filter` | sectors only. `shade` = all day in shade, shade in the afternoon, or shade after 15:00. `sun` = all day in sun. `any` |
| `start_md` / `end_md` | season window `MM-DD`. It may wrap the new year (`12-01` → `02-28`). App timezone (`Asia/Tbilisi`) |
| `every_days` | minimum days between two mails of this campaign to one person (3–365) |
| `items_per_mail` | 1–3 |
| `pick` | `popular` (random among the top 10 by popularity), `random`, `newest` |
| `audience` | `interested` = people with the category's new-content alert on (`add_new_ice_spot`, `add_new_outdoor_spot`, `add_new_mount`, `add_new_gym`, `add_new_techtip`, `news`; sectors also `add_new_sector`). `all` = everyone. `inactive` = not seen for `inactive_days` (win-back) |
| `max_per_run` | people per daily run (SMTP safety) |
| `subject_us/ka`, `intro_us/ka` | mail text. Georgian goes to people with `lang = ka`; an empty Georgian field falls back to English |
| `enabled` | the daily job only runs enabled campaigns |
| `last_run_at`, `last_run_sent` | set by each real run |

Popularity: articles = 3 × favourites + comments. Sectors = number of routes. Sectors without routes, or with a placeholder name ("-"), are skipped.

**Every audience also needs:** a confirmed e-mail, not banned, not under 13, `seasonal_picks` not switched off (missing counts as on), and the master switch `all_off` not on ([notification_mute.md](notification_mute.md)).

## Flow

1. `app:send-seasonal-campaigns`, **daily at 10:30** (`app/Console/Kernel.php`, `withoutOverlapping`). Options: `--campaign=ID` (that one, even when disabled) and `--dry` (count only).
2. For each enabled campaign in season: build the candidate list once, then go through the audience by id (`lazy(200)`), up to `max_per_run` sends.
3. Per person: skip when under 13, when this campaign mailed them within `every_days`, or when any seasonal mail went out within 3 days. Then `pick_for()`: drop items already sent by this campaign and favourited areas, then pick by `pick`.
4. `send()` writes one `content_notification_logs` row per item (`notification_key = seasonal_<id>`, the unique key = dedup), then queues `App\Mail\SeasonalCampaignMessage` on the `emails` queue.

Links carry `utm_source=climbing_email&utm_medium=email&utm_campaign=seasonal_<id>`, so clicks show in Google Analytics under that campaign. Sector links open the area page at the sector (`?sector=<id>`).

## Mail

`resources/views/emails/seasonal/seasonal_campaign.blade.php`, `climbing` theme: the subject as the title, "Hi {name}", the intro, then one panel per item (area photo, linked title, facts + description, "Take a look →"). For sectors the facts line comes first, e.g. "14 routes · 5 min walk · shade in the afternoon · family friendly". Template strings follow the person's language.

## Dashboard

`resources/js/components/user/pages/notifications/SeasonalCampaignsPage.vue` + `SeasonalCampaignFormModal.vue`:

- **Tiles:** campaigns running now · mails in the last 30 days (and all time) · next campaign to start.
- **Year at a glance:** one bar per campaign across Jan–Dec (striped = off, light = on but out of season, solid = running), a dashed line for today. Click a row to edit.
- **Campaign cards:** status, season with "ends in / starts in N days", content (and how many items there are to pick from; red at 0), audience (with today's size), cadence, sent stats (30 days · all time · people), last run, and these actions:
  - **Preview**: what *you* would get today (ignores season, shows whether you're due).
  - **Send test to me**: queues that mail to you. It isn't logged, so it doesn't count and doesn't block you.
  - **Run now**: counts first (`dry`), asks "This will email N people now", then runs with the same rules as the daily job. Only in season.
  - Edit / Delete. Deleting keeps the log rows, so analytics history stays.

## API (`routes/api/admin/set_user_routes.php`, `SeasonalCampaignController`, all under `auth:sanctum`)

| Method | Path | Permission |
|---|---|---|
| GET | `set_seasonal_campaign/list` | show: campaigns + `in_season`, `next_start`, `season_end`, `audience_count`, `content_count`, `stats`, plus `options` and `can` |
| POST | `set_seasonal_campaign/store` | add |
| POST | `set_seasonal_campaign/update/{id}` | edit |
| POST | `set_seasonal_campaign/toggle/{id}` | edit |
| DELETE | `set_seasonal_campaign/delete/{id}` | del |
| GET | `set_seasonal_campaign/preview/{id}` | show |
| POST | `set_seasonal_campaign/send_test/{id}` | edit, `throttle:10,1` |
| POST | `set_seasonal_campaign/run/{id}` (`dry=1` to count) | edit, `throttle:10,1` |

Permissions come from migration `2026_11_13_120100_sync_seasonal_campaign_permissions_with_admin_role.php` (granted to `admin`).

## Default campaigns

`docs/BACKEND/seasonal_campaigns_defaults.sql` (idempotent by name, all **disabled**): Ice season opens (Dec–Feb) · Spring crags (20 Mar–May) · Shady sectors for hot days (15 Jun–Aug) · Mountaineering season (Jul–Aug) · Sunny sectors for autumn (15 Sep–15 Nov) · Winter training indoors (15 Nov–15 Mar) · Tech tip of the month (all year, everyone) · We miss you (all year, inactive 45+ days, newest outdoor areas). Review each with Preview / Send test before switching it on.

## Rules

- The daily job and Run now need the queue worker ([BACKEND/QUEUE_AND_SCHEDULER_UBUNTU.md](../../BACKEND/QUEUE_AND_SCHEDULER_UBUNTU.md)) and the scheduler cron.
- `update_user_notification_data` keeps `seasonal_picks` as it was when a client doesn't send the key (older app builds), so saving other settings can't switch it off by accident.

## Code

- `app/Services/SeasonalCampaignService.php`: season maths, candidates, audience, `due_for`, `pick_for`, `run`, `send`, `stats`
- `app/Console/Commands/SendSeasonalCampaigns.php`, `app/Mail/SeasonalCampaignMessage.php`
- Tests: `tests/Feature/SeasonalCampaignTest.php`
