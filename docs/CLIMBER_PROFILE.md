# Climber Profile — climbing.ge

A public, unauthenticated profile page for any verified climber: bio, social links, follower/following graph, an activity "points" score, and their five most recent comments, ascents, and route/MTP reviews. Lives on the main guidebook subdomain (`climbing.ge`), separate from the private account-settings page on `user.climbing.ge` (see [USER_PAGE.md](USER_PAGE.md#profile--settings)).

There is no admin management screen for this feature — it is a pure read/aggregation view built from data owned by other features (users, comments, summit ascents, route/MTP reviews) plus one new dedicated table for climber-to-climber following.

---

## Table of Contents

- [Overview](#overview)
- [Frontend Pages & Components](#frontend-pages--components)
- [Backend API](#backend-api)
- [The Follow System](#the-follow-system)
- [The Points System](#the-points-system)
- [Database](#database)
- [Where Profiles Are Linked From Elsewhere](#where-profiles-are-linked-from-elsewhere)
- [Known Inconsistencies](#known-inconsistencies)

---

## Overview

**Subdomain:** `climbing.ge` (guide SPA)
**Route file:** `resources/js/routes/SiteRoutes.js`
**API namespace:** `Api\Guide\ClimberProfileController` — fully public, no `auth` middleware

### Page Routes

| Path | Name | Component | Notes |
|---|---|---|---|
| `/:locale?/climber/:id` | `climberProfile` | `guide/pages/ClimberProfilePageComponent.vue` | `:id` is a raw numeric **user id**, not a username/slug, despite the "climber" branding — there is no slug system for profiles. |
| `/:locale?/climbers` | `climberList` | `guide/pages/ClimberListPageComponent.vue` | Directory/leaderboard of all climbers. |

---

## Frontend Pages & Components

### `ClimberProfilePageComponent.vue` — Full Profile Page

**`resources/js/components/guide/pages/ClimberProfilePageComponent.vue`**

Thin page wrapper. Reads `$route.params.id`, builds SEO `<metaData>` (title/description/image from the loaded profile's `my_bio`/`image` via `@unhead/vue`), and renders `<climber-profile-content :user-id="userId">` for the actual content.

### `ClimberProfileContentComponent.vue` — Shared Profile Content

**`resources/js/components/guide/items/climber/ClimberProfileContentComponent.vue`**

The component that actually fetches and renders profile data. Shared between the full page above and the modal below, so it has to work standalone on any subdomain.

- `axios.get('get_climber_profile/' + userId)` on mount and whenever `userId` changes.
- Renders: avatar, name, bio, social links (`facebook`/`instagram`/`youtube`/`website` read out of the `social_links` JSON object), the `user_sites` list (external links, hostname-only display via `new URL(url).hostname`), followers/following counts (click to expand an inline list — capped at the 30 rows the backend returns), a `<radar-stats-chart>` built from `route_reviews_count` / `mtp_reviews_count` / `ascents_count` / `comments_count`, a `<user-follow-button>`, and three "recent activity" sections (reviews, ascents, comments), each `v-if`-gated on having at least one row, with a final "no activity" fallback.
- `go_to_climber()`: checks `this.$router.hasRoute('climberProfile')` — if the component is mounted inside the guide SPA it does an in-app `router.push`; otherwise (e.g. embedded on another subdomain via `CurrentUserClimberCardComponent`) it falls back to `window.open()` across domains.

### `ClimberListPageComponent.vue` — Directory / Leaderboard

**`resources/js/components/guide/pages/ClimberListPageComponent.vue`**

Full page at `/climbers`. Renders `<current-user-climber-card>` (see below), a search box (350ms debounce), a sort toggle (`name` vs `top_active`), an infinite-scroll grid of `<climber-card>` (`global_components/ClimberCardComponent.vue` — avatar, name, `followers_count`, `points_total`, emits `open`), and a `<climber-profile-modal>` opened on card click.

**API:** `GET /get_climber_profile/list?page=&search=&sort=`

### `ClimberProfileModalComponent.vue` — Quick-View Modal

**`resources/js/components/guide/items/climber/ClimberProfileModalComponent.vue`**

Wraps `<StackModal>` (see [docs/FRONTEND/COMPONENTS/STACK_MODAL.md](FRONTEND/COMPONENTS/STACK_MODAL.md)) around `<climber-profile-content>`. Its "View full profile" action does a cross-domain `window.open(MIX_APP_SSH + MIX_GUIDBOOK_URL + '/climber/' + id)` rather than an in-app navigation, since the modal itself gets mounted on subdomains outside the guide SPA. Also handles the shared login-modal's `login-required` / `login-succeeded` events (close itself, reopen after login).

### `CurrentUserClimberCardComponent.vue` — "My Stats" Widget

**`resources/js/components/global_components/CurrentUserClimberCardComponent.vue`**

Globally registered, so it can be dropped on any subdomain's layout. `axios.get('/auth_user')` to get the logged-in user; if present, separately calls `axios.get('get_climber_profile/' + user.id)` **purely to read `followers_count` / `following_count` / `points_total` / the four activity counts** for its own mini radar chart and badges (i.e. it re-uses the public `show` endpoint rather than a dedicated "me" endpoint). Shows a guest CTA (login/register) when not authenticated. Clicking the avatar opens its own embedded `<climber-profile-modal>`.

### Supporting components

- `global_components/UserFollowButtonComponent.vue` — reads `localStorage.auth_token` / `x_xsrf_token` client-side to decide whether to show a follow button at all; on a `422` from the follow endpoint it assumes "that's me" and flips to a disabled/self state (this is how it distinguishes "can't follow, not logged in" from "can't follow, that's your own profile" without a dedicated check).
- `global_components/RadarStatsChartComponent.vue` — pure inline-SVG radar/spider chart, `<script setup>`, props `labels` / `values` / `size` / `color`. No charting library dependency.

---

## Backend API

**Controller:** `App\Http\Controllers\Api\Guide\ClimberProfileController`
**Routes:** `routes/api/get_guide_routes.php`

```php
Route::controller(ClimberProfileController::class)->prefix('get_climber_profile')->group(function() {
    Route::get('/list', 'list');
    Route::get('/{user_id}', 'show');
});
```

| Method | URI | Notes |
|---|---|---|
| GET | `/api/get_climber_profile/list` | Paginated directory. Public; if a Sanctum token is present the logged-in user is excluded from their own list. |
| GET | `/api/get_climber_profile/{user_id}` | Single profile. Fully public — **no auth check at all**, not even optional. |

### `GET /api/get_climber_profile/list`

Query params: `search` (matches `name` **or** `surname`, `LIKE %term%`), `sort` (`name` default, or `top_active`).

Base query excludes banned users (`whereDoesntHave('role', slug='ban')`) and requires both `name` and `email_verified_at` to be non-null — unverified accounts never appear in the directory. Returns a raw Laravel paginator (24/page) of:

```json
{
  "data": [
    { "id": 5, "name": "Nino", "surname": "Beridze", "image": "avatars/nino.jpg",
      "followers_count": 12, "route_review_count": 3, "mtp_review_count": 0,
      "ascent_count": 7, "comment_count": 21, "points_total": 127 }
  ],
  "current_page": 1, "last_page": 4, "per_page": 24, "total": 88
}
```

`top_active` sort orders by `User::pointsOrderByExpression()` (see [Points System](#the-points-system)); default sorts alphabetically by `name`.

### `GET /api/get_climber_profile/{user_id}`

```json
{
  "user": { "id": 5, "name": "Nino", "surname": "Beridze", "image": "avatars/nino.jpg",
            "my_bio": "...", "social_links": {"facebook": "...", "instagram": "..."},
            "created_at": "2025-03-01T10:00:00Z" },
  "points_total": 127,
  "followers_count": 12,
  "following_count": 8,
  "followers": [ { "id": 2, "name": "...", "surname": "...", "image": "..." } ],
  "following": [ { "id": 2, "name": "...", "surname": "...", "image": "..." } ],
  "user_sites": [ { "id": 1, "url": "https://..." } ],
  "recent_comments": [ { "id": 1, "text": "...", "created_at": "...", "article_url_title": "...", "article_category": "..." } ],
  "recent_ascents": [ { "id": 1, "ascent_date": "...", "summit_title": "...", "summit_url_title": "..." } ],
  "recent_route_reviews": [ { "id": 1, "stars": 4, "text": "...", "created_at": "...", "route_name": "..." } ],
  "recent_mtp_reviews": [ { "id": 1, "stars": 5, "text": "...", "created_at": "...", "mtp_name": "..." } ],
  "comments_count": 34,
  "ascents_count": 7,
  "route_reviews_count": 3,
  "mtp_reviews_count": 0
}
```

Every `recent_*` list is capped at 5, most-recent-first. `followers` / `following` are capped at 30 each (there's no "load more" for the social graph beyond that — see [Known Inconsistencies](#known-inconsistencies)). `route_reviews_count` and `mtp_reviews_count` are filtered to `published=1, admin_hidden=0` — same filter the `recent_*` lists use. `comments_count` and `ascents_count` are **not** filtered the same way (see below).

Returns `404 {"message": "User not found"}` if the id doesn't resolve.

---

## The Follow System

Climber-to-climber following is a separate, purpose-built feature from the pre-existing "service following" newsletter-style subscription system referenced in CLAUDE.md's `general.php` notes (`ServiceFollowing` controller, `service_followers` table) — the two are unrelated and easy to confuse by name (see [Known Inconsistencies](#known-inconsistencies)).

**Table:** `user_follows` (`follower_id`, `followed_id`, unique pair, both FKs cascade-delete) — `database/migrations/2026_07_19_120000_create_user_follows_table.php`
**Model:** `App\Models\User\UserFollow`
**On `User`:** `followers()` / `following()` (`belongsToMany(User::class, 'user_follows', ...)`), `isFollowing($userId)`

**Controller:** `App\Http\Controllers\Api\User\UserFollowController` — auth-gated, registered in `routes/api/get_user_routes.php` inside the `auth:sanctum` + `banned` group:

| Method | URI | Notes |
|---|---|---|
| POST | `/api/set_user_follow/follow/{user_id}` | 422 if following yourself; 404 if target doesn't exist; idempotent (`firstOrCreate`). |
| DELETE | `/api/set_user_follow/unfollow/{user_id}` | Idempotent. |
| GET | `/api/set_user_follow/follow_status/{user_id}` | Returns `{ following, is_self }`. |

### Follow-activity notifications

`App\Models\User\UserFollow::observe(App\Observers\UserFollowObserver::class)` (registered in `app/Providers/EventServiceProvider.php`) fires `FollowActivityNotificationService::notifyOfNewFollower()` on every new follow — emails the followed user (bilingual EN/KA, subject/body picked by `$user->lang`).

The same service also fans out **activity** emails to a user's followers whenever that user does something publicly visible, via four more model observers registered alongside the follow observer:

| Trigger | Observer | Service method |
|---|---|---|
| New article comment | `ArticleCommentUserObserver` | `notifyFollowersOfComment()` |
| New summit ascent | `SummitAscentUserObserver` | `notifyFollowersOfAscent()` |
| New route review | `SportRouteReviewObserver` | `notifyFollowersOfRouteReview()` |
| New MTP review | `MtpReviewObserver` | `notifyFollowersOfMtpReview()` |

Deliberately does **not** cover shop/purchase activity. Each notification links back to `{APP_SSH}{GUIDBOOK_URL}/climber/{follower_id}` or the relevant content URL, and is sent once per recipient via `NotificationDispatchService::sendOnce()`.

### Three similarly-named "follow" models — do not confuse

| Model | Table | Purpose |
|---|---|---|
| `App\Models\User\UserFollow` | `user_follows` | **The real climber-follow system**, documented above. |
| `App\Models\User\Following_users` | `service_followers` | Misleadingly named — actually an alias for the legacy service-following (newsletter) table below. Unrelated to climbers. |
| `App\Models\User\Service_follower` | `service_followers` | Legacy guest "follow the guide/shop for updates" subscription (has `HasApiTokens`/`Notifiable`, no password — these are anonymous subscribers, not full `User` accounts). |

---

## The Points System

Not a ledger table — computed on the fly from existing activity every time it's requested, weighted by `config/user_points.php`:

```php
// config/user_points.php
'route_review' => env('POINTS_ROUTE_REVIEW', 5),
'mtp_review'   => env('POINTS_MTP_REVIEW', 5),
'ascent'       => env('POINTS_ASCENT', 10),
'comment'      => env('POINTS_COMMENT', 2),
```

On `App\Models\User`:

- `pointsTotal(): int` — single-user total, four `->count()` queries.
- `scopeWithPointsCounts($query)` — adds `route_review_count` / `mtp_review_count` / `ascent_count` / `comment_count` via `withCount()` in one pass, for list endpoints. Pair with the static helper below rather than calling `pointsTotal()` per row (avoids N+1).
- `pointsFromCounts($user): int` (static) — combines the four `*_count` aggregates from `withPointsCounts()` into a weighted total.
- `pointsOrderByExpression(): string` (static) — the same weighted sum as a raw SQL fragment, for `orderByRaw()` on "top active climbers" sorting (a weighted sum across joined counts can't be expressed as a plain Eloquent column).

The four categories map exactly to the four axes of the profile's radar chart (`route_reviews_label` / `mtp_reviews_label` / `ascents_label` / `comments_label` in `resources/lang/i18n/en.json`, namespace `global.follow.*`).

A code comment in `config/user_points.php` references reading these values "via `App\Services\UserPointsService`" — **that class does not exist**; the logic lives directly on the `User` model as shown above. Stale comment, not a missing file to go looking for.

---

## Database

No new tables beyond `user_follows` (see [Follow System](#the-follow-system)) and `user_sites` (external links list, `url` + `user_id`, unrelated to the `social_links` JSON column). Everything else is existing columns/tables from other features, reused:

| Column | Table | Added by | Purpose |
|---|---|---|---|
| `my_bio` | `users` | `2026_07_15_175218_add_my_bio_to_users_table.php` | Free-text bio shown on the profile. |
| `social_links` | `users` | `2026_07_20_090000_add_social_links_to_users_table.php` | JSON, cast to array — `facebook`/`instagram`/`youtube`/`website` keys read by the frontend. |

Activity data is read straight from each owning feature's tables (no denormalization): `article_comment_user` / `comments` (Guide), `summit_ascent_users` / `summit_ascents` (Summit — see [SUMMIT.md](SUMMIT.md)), `sport_route_reviews`, `mtp_reviews`.

---

## Where Profiles Are Linked From Elsewhere

- **`RouteAllReviewsModal.vue`** / **`MtpAllReviewsModal.vue`** — reviewer names are `<router-link :to="{name:'climberProfile', params:{id: review.user.id}}">` (in-app navigation, since these modals already live inside the guide SPA), falling back to plain text for anonymous/guest reviews with no `user.id`.
- **`summit/pages/pages/SummitPage.vue`** — the ascents table links each row via a `climberProfileUrl(userId)` helper that builds a cross-domain URL (`window.open`, since Summit is a separate subdomain from the guide SPA). The "Top Climbers" sidebar and `uniqueClimbersCount` on the same page, however, key off concatenated `name + ' ' + surname` strings rather than `user_id` — see [Known Inconsistencies](#known-inconsistencies).

---

## Known Inconsistencies

Found during a documentation audit (August 2026) — noted here rather than silently fixed, since some may be intentional trade-offs:

1. ~~**`show()` has no ban check.**~~ **Fixed (September 2026).** `list()` explicitly excludes banned users; `show($user_id)` didn't, so a banned climber's profile page remained fully reachable by direct URL. `show()` now applies the same `whereDoesntHave('role', slug='ban')` filter as `list()` and returns 404 for a banned user's id. See [SECURITY_AND_PITFALLS.md](BACKEND/SECURITY_AND_PITFALLS.md) for the full audit this was found in.
2. **`comments_count` is unfiltered.** It counts *all* of a user's comments (`article_comments()->count()`), including unpublished/hidden ones, while `recent_comments`, `route_reviews_count`, and `mtp_reviews_count` all filter to `published=1, admin_hidden=0`. A user's profile can show a comment count higher than the number of comments anyone can actually see.
3. **Admin dashboard's "Following relationships" stat is not this feature.** `SiteDataCountListComponent.vue`'s `following_relationships_label` is populated from the legacy `service_followers` table (via `Following_users`), not `user_follows` — there is **no admin visibility at all** into real climber-follow data (no count, no list, no moderation).
4. **`SummitPage.vue`'s "Top Climbers" list doesn't use `user_id`.** It and `uniqueClimbersCount` key by concatenated `name + surname` strings, so (a) neither links to a profile, unlike the ascent table right above it which correctly uses `user_id`, and (b) two different climbers who happen to share a name get silently merged into one entry.
5. **The route param is a numeric id, not a slug.** `/climber/:id` takes a raw user id. There's no username/slug system backing "climber profile" branding — worth knowing before assuming a shareable vanity URL exists.
6. **`User::training_workouts()` / `training_plan_states()` / `training_history()` exist but nothing calls them.** Added alongside the Training feature (see [TRAINING.md](TRAINING.md)) but never referenced outside `User.php` and migrations — no training stats appear on the Climber Profile today. Reads as scaffolding for a training/profile integration that hasn't shipped.

---

[Go back](../README.md)
