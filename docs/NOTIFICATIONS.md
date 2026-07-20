# Notification System

Automatic and manual email notifications for new content and events across the guide, shop, blog, summit, and films domains.

---

## Table of Contents

- [Overview](#overview)
- [User Preferences](#user-preferences)
- [Dedup Log](#dedup-log)
- [NotificationDispatchService](#notificationdispatchservice)
- [Content Resolvers](#content-resolvers)
- [Trigger Points](#trigger-points)
- [Event Reminder Cron](#event-reminder-cron)
- [User Following & Follow Activity Notifications](#user-following--follow-activity-notifications)
- [Manual Admin Trigger](#manual-admin-trigger)
- [Notification Analytics Dashboard](#notification-analytics-dashboard)
- [Admin Action Alerts](#admin-action-alerts)
- [Adding a New Content Type](#adding-a-new-content-type)

---

## Overview

Every user can opt into a set of email notification categories (e.g. "notify me when a new sector is added", "notify me about a new product"). When content is published in any of these domains, the system automatically finds subscribed users and queues an email — no admin action required. A manual admin trigger remains available as a fallback for re-sends and one-off announcements.

Two kinds of "you should hear about this" exist:
- **Broad/category subscriptions** — "notify me about *any* new sector" (`add_new_sector`, `add_new_product`, etc.)
- **Per-item favorites** — "notify me when *this specific* product/outdoor area/film I favorited changes" (`Favorite_product`, `Favorite_outdoor_area`, `Favorite_film`, `Interested_event`)

On top of that, each of those two also has an **add** variant (new item published) and, separately, an **update** variant (an already-published item gets edited) — see [Trigger Points](#trigger-points). The two are independent opt-ins with separate preference keys (e.g. `add_new_sector` vs `sector_updated`), so a user can subscribe to new sectors without also getting pinged on every edit to existing ones, or vice versa.

---

## User Preferences

**Table:** `user_notifications` (model `App\Models\User\user_notification`)
**Relation:** `User::notification_list()` → `hasOne(user_notification::class, 'user_id')`

The `notification_type` column is a native MySQL `json` column holding a flat object of boolean flags, cast to a PHP array via `protected $casts = ['notification_type' => 'array']`. There is no per-key schema — adding a new preference is just a new key in the JSON blob plus a checkbox in the UI.

Current keys:

| Key | Meaning |
|---|---|
| `news` | New "news" category articles |
| `add_new_sector` | New climbing sector |
| `add_new_outdoor_spot` | New outdoor article (category `outdoor`) |
| `add_new_ice_spot` | New ice climbing article (category `ice`) |
| `add_new_gym` | New indoor gym article (category `indoor`) |
| `add_new_techtip` | New tech tip article (category `tech_tip`) |
| `add_new_mount` | New mountain/peak |
| `add_new_product` | New shop product |
| `add_new_service` | New shop service |
| `add_new_tour` | New shop tour |
| `add_new_summit` | New summit |
| `add_new_blog_post` | New blog post |
| `add_new_film` | New film |
| `favorite_outdoor` | Updates to favorited outdoor articles |
| `favorite_product` | Updates to favorited products |
| `favorite_film` | Updates to favorited films |
| `interested_event` | Reminders for favorited events |
| `sector_updated` | Edits to already-published sectors |
| `outdoor_spot_updated` | Edits to already-published outdoor articles |
| `ice_spot_updated` | Edits to already-published ice climbing articles |
| `gym_updated` | Edits to already-published gym articles |
| `techtip_updated` | Edits to already-published tech tip articles |
| `news_updated` | Edits to already-published news articles |
| `mount_updated` | Edits to already-published mountains/peaks |
| `product_updated` | Edits to already-published shop products |
| `service_updated` | Edits to already-published shop services |
| `tour_updated` | Edits to already-published shop tours |
| `summit_updated` | Edits to already-published summits |
| `blog_post_updated` | Edits to already-published blog posts |
| `film_updated` | Edits to already-published films |

**API:** `GET/POST /api/get_options/get_user_notification_data` / `update_user_notification_data` (`App\Http\Controllers\Api\User\UserOptionController`).
**UI:** `resources/js/components/user/items/user_options/UserNotificationsListComponent.vue`.

---

## Dedup Log

**Table:** `content_notification_logs` (model `App\Models\User\ContentNotificationLog`)

Every send goes through a log row with a DB-level unique constraint on `(user_id, notifiable_type, notifiable_id, notification_key)`. This is what makes the whole system safe to re-trigger: publishing the same item twice, or a cron running on an overlapping window, can never double-email the same user about the same thing for the same reason.

`notification_key` values:
- `new_content` — broad category subscription send (new item published)
- `favorite_publish` — a specific favorited item was published
- `content_updated` — broad category subscription send (already-published item edited, admin opted in — see [Trigger Points](#trigger-points))
- `favorite_updated` — a specific favorited item was edited (not the publish itself), admin opted in
- `event_reminder` — legacy key, no longer written by the cron (kept so historical log rows still resolve to a readable label in analytics); superseded by the four keys below
- `event_reminder_1month` / `event_reminder_2weeks` / `event_reminder_1week` / `event_reminder_1day` — one per reminder checkpoint (see [Event Reminder Cron](#event-reminder-cron)); distinct keys are required so all four can fire for the same user/event as the date approaches — a single shared key would let only the first checkpoint ever send, per the once-ever-per-`(item, key)` rule below

The unique constraint normally caps a user to **at most one** email ever per `(item, key)` — `sendOnce()`'s default behavior. Any send triggered by the admin's `NotifySubscribersComponent` choice on an edit form passes `$bypassDedup = true` (whether that's `notifyContentUpdated` for "notify as an update", or `notifyNewContent`/`notifyFavoritersOfPublish` for "notify as new content"), so it sends every time the admin picks it, even for the same item across multiple edits (a log row still gets written best-effort via `insertOrIgnore`, but a repeat trigger for the same item won't get a second logged row — only the email itself is guaranteed to go out every time). Only the *automatic*, unforced new-content trigger (the draft→published transition) relies on the default once-ever dedup.

---

## NotificationDispatchService

**File:** `app/Services/NotificationDispatchService.php`

Central entry point for all automatic sends:

- `usersWithPreference(string $key): Collection` — users whose `notification_type` JSON has `$key === true`, queried in SQL (`notification_type->key`), not by loading every row into PHP.
- `sendOnce(User $user, Model $notifiable, string $key, string $url, string $subject, string $text, bool $bypassDedup = false): bool` — inserts the dedup log row (`insertOrIgnore`). By default, only dispatches `App\Jobs\UserNotifications` if a row was actually inserted (already-sent items are silently skipped). With `$bypassDedup = true`, the log insert is still attempted (best-effort, for analytics) but the email always dispatches regardless of whether the row was new.
- `notifyNewContent(string $modelClass, int $id, bool $force = false)` — resolves the content's preference key and locale text via its resolver, sends to every subscribed user, then also calls `notifyFavoritersOfPublish` if the content type supports per-item favorites. Called two ways: automatically (unforced) on the draft→published transition, or with `$force = true` when the admin picks "notify as new content" on an already-published item's edit form.
- `notifyFavoritersOfPublish(string $modelClass, int $id, bool $force = false)` — sends to users who favorited this specific item (separate `notification_key`, so it stacks with rather than blocks the broad send).
- `notifyContentUpdated(string $modelClass, int $id, bool $force = false)` — same shape as `notifyNewContent` but for edits to already-published items: resolves `updatePreferenceKey()` instead of `preferenceKey()`, sends to subscribers of the update key (passing `$force` through to `sendOnce()` as `$bypassDedup`), then also calls `notifyFavoritersOfUpdate` if favoritable. **There is no automatic caller of this method** — it only ever runs with `$force = true`, from the admin's explicit "notify as an update" choice (see [Trigger Points](#trigger-points)).
- `notifyFavoritersOfUpdate(string $modelClass, int $id, bool $force = false)` — same shape as `notifyFavoritersOfPublish`, but keyed `favorite_updated` instead of `favorite_publish` so it doesn't collide with (or get deduped against) the original publish notification.

Reuses the existing mail pipeline unchanged: `App\Jobs\UserNotifications` → `App\Mail\UserNotificationMessage` → `resources/views/emails/user_notifications/article_event_notification.blade.php`.

---

## Content Resolvers

**Directory:** `app/Services/Notifications/Resolvers/`, interface `App\Services\Notifications\ContentResolverInterface`

Locale text isn't uniform across content types — Article/Product/Service/Tour/Post/Mount/Film use a `us_X()`/`ka_X()` relation to a separate `Locale_*` model, Sector has flat `us_description`/`ka_description` columns, and Summit has flat `title`/`ka_title` columns. Each content type gets a small resolver instead of one large conditional:

Each resolver implements two preference-key methods: `preferenceKey($model)` for new-content sends and `updatePreferenceKey($model)` for edit-to-already-published sends (e.g. `SectorResolver::preferenceKey()` returns `add_new_sector`, `updatePreferenceKey()` returns `sector_updated`). `ArticleResolver` maps both per the article's `category`, same as its existing category → preference mapping.

| Content type | Resolver | Preference key | Favoritable via |
|---|---|---|---|
| `Article` (category-dependent) | `ArticleResolver` | `add_new_outdoor_spot` / `add_new_ice_spot` / `add_new_gym` / `add_new_techtip` / `news` (or `null` — out of scope for `special`/`other`/`partners`/`mount_route`) | `Favorite_outdoor_area` (outdoor only) |
| `Sector` | `SectorResolver` | `add_new_sector` | — |
| `Mount` | `MountResolver` | `add_new_mount` | — |
| `Product` | `ProductResolver` | `add_new_product` | `Favorite_product` |
| `Service` | `ServiceResolver` | `add_new_service` | — |
| `Tour` | `TourResolver` | `add_new_tour` | — |
| `Post` (blog) | `PostResolver` | `add_new_blog_post` | — |
| `Summit` | `SummitResolver` | `add_new_summit` | — |
| `Film` | `FilmResolver` | `add_new_film` | `Favorite_film` |

`NotificationDispatchService::resolverFor($modelClass)` maps a model class to its resolver.

---

## Trigger Points

A **new-content** notification fires automatically and unconditionally whenever content is actually **published** (`published == 1`) — either at creation time, or on the transition from draft/unlisted (`0`/`2`) to public (`1`) on edit. No admin action is needed for this one.

Once an item is already live, further edits do **not** automatically notify anyone. Instead, every admin edit form for a notifiable type has a **`NotifySubscribersComponent`** (`resources/js/components/user/items/form/parts/NotifySubscribersComponent.vue`) — a 3-option radio group, defaulting to "don't notify":
- **Don't notify** (default) — routine edits (typo fixes, etc.) that shouldn't bother subscribers.
- **Notify as an update** — fires `notifyContentUpdated($modelClass, $id, true)`: emails everyone subscribed to that type's *update* preference key (see [User Preferences](#user-preferences)).
- **Notify as new content** — fires `notifyNewContent($modelClass, $id, true)`: re-announces the item to everyone subscribed to the *new-content* preference key, as if it were just published — including users who already got the original new-content email.

The `true` in both calls is `$force`, which bypasses `sendOnce()`'s per-item dedup (see [Dedup Log](#dedup-log)) — a deliberate admin choice on a specific save should always go out, and should be repeatable across later edits, unlike the automatic new-content trigger which only ever needs to fire once (there's nothing to dedupe against the first time).

**Article, Product, Service, Tour, Post** are all created through the shared `App\Services\Abstract\LocaleContentControllService`. Its `addGlobalArticle()` uses `Model::insertGetId()` — a query-builder call that does **not** fire Eloquent's `created` event — so these five types are hooked directly inside that shared class (`addGlobalArticle()` for create, `edit_global_content()` for both the publish transition and later edits) rather than via Observers, which would silently never fire on create. `edit_global_content()` checks `$wasLive`/`$isLiveNow` plus `$request->input('notify_mode', 'none')`:
- `!$wasLive && $isLiveNow` → `notifyNewContent($global_model, $id)` (automatic, no admin choice involved)
- `$wasLive && $isLiveNow && $notifyMode === 'update'` → `notifyContentUpdated($global_model, $id, true)`
- `$wasLive && $isLiveNow && $notifyMode === 'new'` → `notifyNewContent($global_model, $id, true)`

`notify_mode` (string: `'none'`/`'update'`/`'new'`) is a plain top-level request field the frontend sends alongside the existing `data` payload (FormData for Article/Sector/Service/Tour/Post, a plain JSON object for Product/Mount) — it is **not** nested inside the `data` JSON blob.

**Sector, Mount, Summit, Film** are created via normal `Model::save()`/`create()`, so they use real Eloquent Observers. Since Observer callbacks only receive the model instance (no access to the HTTP request), each admin controller stamps a transient, non-persisted flag on the model right before saving:
```php
$edit_sector->notifyMode = $request->input('notify_mode', 'none');
$edit_sector->save();
```
This property is declared directly on the model class (`public $notifyMode = 'none';` on `Sector`/`Mount`/`Summit`/`Film`) rather than relying on Eloquent's magic `__set()` — a real declared PHP property is read/written directly by PHP, bypassing Eloquent's attribute-tracking entirely, so it never shows up in `getDirty()`/the UPDATE query (which would otherwise fail with an unknown-column error). The Observer then reads `$model->notifyMode` to decide which method (if any) to call.

| Model | Observer | Save site that stamps `notifyMode` | Notes |
|---|---|---|---|
| `Sector` | `App\Observers\SectorObserver` | `Admin\Guide\SectorController::edit_sector()` | `created()` gated on `published == 1`; `updated()` fires `notifyNewContent` on draft→published (automatic), or `notifyContentUpdated`/`notifyNewContent` (forced) on published→published per `notifyMode` |
| `Mount` | `App\Observers\MountObserver` | `Admin\Guide\MountController::edit_global_mount()` | `created()` fires unconditionally — the `mounts.published` column exists but is never set by the admin controller, so mounts are always treated as live; `updated()` has no draft state to check, so `notifyMode` is the sole gate |
| `Summit` | `App\Observers\SummitObserver` | `Admin\Summit\SummitController::update()` | same pattern as Sector, gated on `published == 1` (not `2` — unlisted) |
| `Film` | `App\Observers\FilmObserver` | *(none yet)* | same pattern as Sector, wired up for when an admin edit endpoint exists — `FilmsController` currently has no `update()` method and the admin edit page (`filmsEditComponent.vue`) is a stub, so this path is unreachable today |

Registered in `App\Providers\EventServiceProvider::boot()`.

**Caveat:** none of these checks are field-change-aware — choosing "update" or "new" notifies subscribers regardless of which fields were actually edited (e.g. a pure typo fix vs. a substantive content change look identical to the system). That's intentional: the radio choice itself *is* the admin's judgment call about whether/how the edit should be announced.

---

## Event Reminder Cron

**Command:** `php artisan send_event_notificatione:users` (`App\Console\Commands\UserAutoNotificatione`)
**Schedule:** daily, `app/Console/Kernel.php`

Four fixed reminder checkpoints, defined in `UserAutoNotificatione::PERIODS`:

| Checkpoint | Days before event | `notification_key` |
|---|---|---|
| 1 month  | 30 | `event_reminder_1month` |
| 2 weeks  | 14 | `event_reminder_2weeks` |
| 1 week   | 7  | `event_reminder_1week` |
| 1 day    | 1  | `event_reminder_1day` |

For each checkpoint, the command matches events whose `start_data` falls on that **exact calendar date** (`whereDate('start_data', now()->addDays($days)->toDateString())`) — not a fuzzy multi-day window like the original single-reminder version, since running once daily already guarantees each checkpoint is hit on exactly the right day. For each matching event, it notifies only the users who specifically favorited *that* event (via `App\Models\Guide\Interested_event`) — not every user with the `interested_event` preference on. Locale (`ka`/`us`) comes from `User::lang`.

Sends go through `NotificationDispatchService::sendOnce()` (unforced), so a user gets each checkpoint's email **at most once** per event — but all four checkpoints still fire independently as the event approaches, since each uses its own `notification_key` (see [Dedup Log](#dedup-log)).

---

## User Following & Follow Activity Notifications

A separate system from the preference-based content notifications above — this is user-to-user following (like a social "follow"), and it always emails followers when someone they follow does something publicly visible. There's no opt-in preference for it: choosing to follow someone *is* the opt-in.

**Table:** `user_follows` (`follower_id` = the follower, `followed_id` = the user being followed; unique + FK-cascade on both columns). Note: this is a purpose-built table, distinct from the pre-existing, unrelated `user_followers` table (which was left untouched).
**Model:** `App\Models\User\UserFollow`
**Relations:** `User::following()` / `User::followers()` (both `belongsToMany(User::class, 'user_follows', ...)`), `User::isFollowing($userId)`

**Follow/unfollow API** (auth required, `routes/api/get_user_routes.php`, prefix `set_user_follow`): `POST /follow/{user_id}`, `DELETE /unfollow/{user_id}`, `GET /follow_status/{user_id}`. Self-follow is rejected.

**Public profile** (`routes/api/get_guide_routes.php`, prefix `get_climber_profile`): `GET /list` (paginated directory of all non-banned users with a name, with `followers_count`) and `GET /{user_id}` (public info — name, surname, image, `my_bio` — follower/following counts, and up to 5 each of recent comments/ascents/route reviews/MTP reviews). The `/list` route is registered before the `/{user_id}` wildcard so it isn't swallowed by it.

Frontend: the profile display itself lives in one shared component, `resources/js/components/guide/items/climber/ClimberProfileContentComponent.vue`, rendered by two different shells:
- `resources/js/components/guide/pages/ClimberProfilePageComponent.vue` — full page, route `climberProfile`, path `/climber/:id`. Commenter/reviewer/climber names link here wherever a registered user is behind the item (`CommentFormComponent.vue`, `RouteAllReviewsModal.vue`, `MtpAllReviewsModal.vue`, `SummitPage.vue`'s ascent table).
- `resources/js/components/guide/items/climber/ClimberProfileModalComponent.vue` — quick-view modal, opened from `resources/js/components/guide/pages/ClimberListPageComponent.vue` (route `climberList`, path `/climbers` — a "load more"-paginated grid of small user cards backed by `GET /list`).

`resources/js/components/global_components/UserFollowButtonComponent.vue` is the reusable follow/unfollow button used inside the shared content component. If clicked while logged out, it emits `login-required`/`login-succeeded` (bubbled up through `ClimberProfileContentComponent`) so a modal-based caller can close itself, hand off to the shared login modal (`$bus.emit('open-login-modal', callback)`), and reopen once login completes — instead of stacking two modals. The full-page shell ignores these events since there's nothing to reopen there.

**Notifications:** `App\Services\FollowActivityNotificationService`, triggered via Eloquent observers (registered in `EventServiceProvider::boot()`) — **not** the content Resolvers above, since these are user actions, not published content:

| Trigger | Observer | Hooked on |
|---|---|---|
| Article comment (by a registered user) | `ArticleCommentUserObserver` | `Article_comment_user::created()` — the pivot, not `Comment`, since comment rows are written via raw `insertGetId()` and never fire Eloquent events |
| Summit ascent (by a registered user) | `SummitAscentUserObserver` | `SummitAscentUser::created()` — same reasoning, the pivot fires events even though `SummitAscent` itself also does |
| Sport route review | `SportRouteReviewObserver` | `Sport_route_review::created()` — direct `user_id` column, always set (route requires auth) |
| MTP review | `MtpReviewObserver` | `Mtp_review::created()` — same, direct `user_id` |
| New follower | `UserFollowObserver` | `UserFollow::created()` — fires once per genuinely new follow (`firstOrCreate` in `UserFollowController::follow()` doesn't re-fire on a repeat call) |

For the first four triggers, the service resolves the acting user, loads their followers, and calls `NotificationDispatchService::sendOnce($follower, $notifiable, 'follow_activity', $url, $subject, $text)` per follower — reusing the same dedup log (`content_notification_logs`) and queued mail job as the content-notification system, just with `notification_key = 'follow_activity'`. The link/title text points at the containing article (for comments, and for route/MTP reviews via `Route`/`Mtp` → `Sector` → `Article`) or the summit (for ascents), via the existing `ArticleResolver`/`SummitResolver`.

The "new follower" trigger is different in shape (`FollowActivityNotificationService::notifyOfNewFollower()`): a single email to the *followed* user (not their followers), `notification_key = 'new_follower'`, notifiable is the `UserFollow` row itself (so unfollow-then-refollow correctly sends a fresh notification, since a new row/id gets created), body: `New follower: "{name surname}" followed you.`, linking to the follower's own climber profile.

**Deliberately excluded:** shop/purchase activity (orders, product feedback) — only guide/summit actions and the follow action itself trigger these notifications.

---

## Manual Admin Trigger

**Endpoint:** `POST /api/set_user/set_notifications/send_user_favorites_notification/{action}`
**Controller:** `App\Http\Controllers\Api\User\Admin\User\UserNotificationsController@send_user_favorites_notification`
**Permission:** `user_notification` › `add`
**UI:** `resources/js/components/user/items/modal/notifications/UserMailNotificationModelComponent.vue`

Lets an admin manually re-send a notification about a specific item (event/product/outdoor area/film/latest news) to everyone with the matching preference, or blast the current "special" article to every user (`action=special_articles`). Bypasses the dedup log intentionally — a manual re-send is a deliberate admin action.

---

## Notification Analytics Dashboard

**Page:** `/notification_analytics` (`resources/js/components/user/pages/notifications/NotificationAnalyticsPage.vue`)
**Controller:** `App\Http\Controllers\Api\User\Admin\User\NotificationAnalyticsController`
**Permission:** `user_notification` › `show`

Read-only reporting over `content_notification_logs` and `user_notifications`, plus a management view over `service_followers` (guest email subscribers):

| Endpoint | Returns |
|---|---|
| `GET set_notification_analytics/summary` | total sent, sent in last 7/30 days, distinct users notified, users with preferences configured |
| `GET set_notification_analytics/volume_over_time/{period}` | Google Charts–shaped `[[label, count], ...]` time series (daily buckets for `30days`/`1month`, monthly for longer periods), same period convention as `OrderController::get_order_statistics` |
| `GET set_notification_analytics/breakdown_by_key` | counts grouped by `notification_key` (new content / favorite update / event reminder) |
| `GET set_notification_analytics/breakdown_by_content_type` | counts grouped by content model (Article, Product, Sector, …) |
| `GET set_notification_analytics/preference_adoption` | per preference key: how many users have it enabled, and as a percentage of total users |
| `GET set_notification_analytics/recent?limit=50` | most recent individual sends, with user email and content type, for the activity table |
| `GET set_notification_analytics/registered_subscribers` | every registered user with a `user_notifications` row, with `email_verified` and a `preferences` array (`{key, label, enabled}` per `PREFERENCE_KEYS`) for the detail modal |
| `GET set_notification_analytics/guest_followers` | every guest (non-registered) email subscriber from `service_followers`, with `service` resolved to a readable label (Guide/Shop/Blog/Summit/Films) |
| `DELETE set_notification_analytics/guest_followers/{id}` | removes a guest subscriber; requires `user_notification`›`del` (separate from `show`, granted to `admin` by `2026_07_18_101500_sync_notification_del_permission_with_admin_role.php`) |
| `POST set_notification_analytics/subscribers/{user_id}/verify` | sets `email_verified_at` if unset; requires `user_notification`›`manage` |
| `POST set_notification_analytics/subscribers/{user_id}/subscribe_all` | force-enables every key in `PREFERENCE_KEYS` for that user (`updateOrCreate` on `user_notifications`); requires `user_notification`›`manage` |

Charts use `vue-google-charts` (`GChart`), the tables use the shared `TabsComponent` in its multi-tab form (Recent Activity / Registered Subscribers / Guest Followers as three switchable tabs in one instance) — both are existing conventions used elsewhere in the admin panel (see `resources/js/components/user/items/dashboards/OrderDiogramComponent.vue` and `resources/js/components/user/pages/database_&_multimedia/DatabasePage.vue`), so no new frontend dependency was introduced.

This page replaces the old standalone "Site Followers" admin page (`/site_folloers`, `SiteFolloersListPageComponent.vue`), which has been removed. That page's underlying `GET get_follow/following_users_list` endpoint was a public, unauthenticated route in `routes/api/general.php` that returned every guest subscriber's email with no permission check at all — the `site_folloers` permission it was nominally gated behind in the frontend router/menu never actually existed in the `permissions` table, so the check was dead weight even for the UI, and the API itself had no auth layer whatsoever. `get_guest_followers` above is properly gated behind `auth:sanctum`+`banned`+`user_notification`›`show` instead. The public per-user `POST set_follow/{service}` (subscribe) and `DELETE set_follow/del_follower/{id}` (self-unsubscribe via the emailed link) endpoints in `ServiceFollowing.php` are unchanged and intentionally remain unauthenticated — those are the public-facing follow/unfollow flow, not the admin listing.

---

## Admin Action Alerts

A separate, unrelated system from everything above — this is not email, it's an in-app "you should look at this" widget for admins/moderators, distinct from the user-facing home page notifications (profile-completeness alerts) so the two are never mixed.

**Service:** `app/Services/AdminAlertsService.php`
**Controller:** `App\Http\Controllers\Api\User\Admin\User\AdminAlertsController` — `GET /api/set_admin_alerts/summary`, `POST /api/set_admin_alerts/mark_viewed` (body: `{ key }`)
**UI:** `resources/js/components/user/items/notificatione/AdminNotificationsListComponent.vue` (Guide Dashboard → "User mails" tab)
**User-facing (unrelated) counterpart:** `resources/js/components/user/items/notificatione/UserNotificationsListComponent.vue` on the Home page — only ever shows the logged-in user's own profile-completeness alerts (missing email verification/image/profile fields from `/auth_user`), never admin data.

Two kinds of alert:

- **Danger (always-on):** `db_relation_issues` — count of open integrity issues from `DatabaseController::detectIssues()` (now covers products/services/tours/films/mounts locale-FK orphans plus sectors/summits missing translation columns, not just articles/posts). Comment and product-feedback **complaints** are also danger-level but are rendered directly from their own existing endpoints (`get_comments_complaints`, `get_feedbacks_complaints`) rather than through the service, since the widget needs the individual records (not just a count) to drive the review/decision modal.
- **Info (dismissible, "new since last visit"):** `new_comments`, `new_product_feedback`, `new_mtp_reviews`, `new_route_reviews`, `new_ascents` — counts of rows created after the admin's `last_viewed_at` for that key, tracked per-admin in `admin_alert_views` (table `user_id` + `alert_key`, unique). First time an admin sees a given alert type, the baseline is set to "3 days ago" rather than the full historical backlog, so a brand-new admin isn't shown years of "new" content. Visiting the alert's linked admin page calls `mark_viewed`, resetting the counter.

Every alert type is gated by the **same permission subject/action already used by its underlying admin page** (`comment`/`show`, `product_feedback`/`show`, `routes_rewiew`/`show`, `summit`/`show`, `database`/`show`) — no new permission subject was introduced. `AdminAlertsService::getAlerts()` filters per-item, so an admin only sees alert types they already have access to.

---

## Adding a New Content Type

1. Add both a new-content preference key and an update preference key (e.g. `add_new_x` / `x_updated`) to the checkbox list in `UserNotificationsListComponent.vue` (and its `activate_all_notifications`/`cancel_all_notifications` methods) plus i18n strings, and to `NotificationAnalyticsController::PREFERENCE_KEYS`/`PREFERENCE_LABELS`.
2. Write a resolver in `app/Services/Notifications/Resolvers/` implementing `ContentResolverInterface` — both `preferenceKey()` and `updatePreferenceKey()` — and register it in `NotificationDispatchService::RESOLVERS`.
3. If the model is created via `Model::save()`/`create()`: declare `public $notifyMode = 'none';` directly on the model class (a real PHP property, not an Eloquent attribute — see [Trigger Points](#trigger-points) for why), add an Observer (see `app/Observers/`) with the `wasLive`/`isLiveNow` branching gated on that property (`'update'` → `notifyContentUpdated(..., true)`, `'new'` → `notifyNewContent(..., true)`), register it in `EventServiceProvider::boot()`, and stamp the property from `$request->input('notify_mode', 'none')` in the admin controller right before the save call. If it's created through `LocaleContentControllService`, no Observer/model property is needed — `edit_global_content()` already reads `$request->input('notify_mode', 'none')` directly.
4. If the content type supports per-item favorites, have the resolver's `favoriteModelClass()`/`favoriteForeignKey()` point at the favorite model — this covers both `notifyFavoritersOfPublish` and `notifyFavoritersOfUpdate` automatically.
5. Add `<notify_subscribers :notify_mode_prop="notify_mode" @item_data="notify_mode = $event" />` (`resources/js/components/user/items/form/parts/NotifySubscribersComponent.vue`, `notify_mode: 'none'` in `data()`) to the entity's admin edit form, wired into whatever shape that form's save payload already uses (FormData vs. plain JSON, nested `data` key or flat fields — check the existing save method first, and always send `notify_mode` as a top-level field, never nested inside `data`).
