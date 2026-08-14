# Training Mobile App — Account Sync

Cloud sync for the companion **climbing training mobile app** (see [TRAINING.md](TRAINING.md) for the content/CMS side of that app). Login/register/logout already work as-is against this backend's existing auth endpoints (`/api/login`, `/api/register`, `/api/auth_user`, `/api/logout` — see [AUTH.md](AUTH.md)); this doc covers the one additional endpoint that lets a logged-in user's custom workouts, plan activation state, and workout history follow them across devices instead of staying trapped in `AsyncStorage` on a single phone.

---

## Table of Contents

- [Overview](#overview)
- [Database Schema](#database-schema)
- [API](#api)
- [Conflict Resolution](#conflict-resolution)
- [Two Things Easy to Get Wrong](#two-things-easy-to-get-wrong)

---

## Overview

Until this endpoint exists, the app's `syncNow()` 404s and the failure is swallowed silently — the app just keeps working exactly like logged-out/local-only mode. Nothing on the mobile side needs to change for this to start working.

This is **not** a diff sync. Each `POST` carries the device's entire current set of workouts/plans/history for the logged-in user, and the response returns the server's entire canonical set back. The app unions local + remote by id (see [Conflict Resolution](#conflict-resolution)).

**Files:** `routes/api/get_user_routes.php`
**Controller:** `App\Http\Controllers\Api\User\UserTrainingSyncController`
**Migrations:** `database/migrations/2026_08_13_0900*`
**Models:** `App\Models\Training\{UserWorkout,UserPlanState,UserTrainingHistory}`
**Middleware:** `auth:sanctum`, `banned` (same group as `/api/mail/unread_count`, `/api/set_user_follow/*`, etc. — a per-user endpoint, not an admin/permission-gated one)

| App concept | AsyncStorage key | What syncs |
|---|---|---|
| Custom workouts (user-created, via "Create Workout") | `workouts` | Full row — these don't exist anywhere else, unlike preset trainings from the [training content API](TRAINING.md) |
| Training plan activation state | `plans` | Only the *activation* fields (`isActive`, `startDate`, notification/calendar ids, etc.) — the plan's actual content already lives in `training_plans` and is never duplicated here |
| Workout session history | `history` | Full row |

---

## Database Schema

This repo's house style uses an auto-increment `id` PK plus a unique index for per-user child tables (see `favorite_outdoor_areas`, `content_notification_logs`), rather than a composite primary key — every attempt at `$table->primary(['a','b'])` elsewhere in this codebase is commented out in favor of this pattern. All three tables below follow it. The app-facing identity column (`client_id` / `plan_id` / `date`) is what's echoed back in the API, never the internal `id` — same principle as `trainings.slug` being the public-facing id while `trainings.id` is an internal bigint (see [`2026_08_12_204631_convert_training_ids_to_integer_pk.php`](../database/migrations/2026_08_12_204631_convert_training_ids_to_integer_pk.php)).

All three tables are scoped to `user_id` (`ON DELETE CASCADE`).

### `user_workouts`

| Column | Type | Notes |
|---|---|---|
| `id` | bigint PK | Internal, auto-increment — never exposed in the API |
| `user_id` | FK → `users.id`, `ON DELETE CASCADE` | |
| `client_id` | `varchar(64)` | The app's client-generated id (`Date.now().toString()` today). Unique per user. **Never reassigned** — always echoed back as `id` in the response |
| `name` | `varchar(120)` | |
| `description` | `text`\|null | |
| `type` | enum | `fingerboard`, `campus`, `flexibility`, `strength`, `endurance` |
| `hang_time`, `rest_time`, `reps`, `sets`, `recover_time` | int | |
| `updated_at` | `varchar(32)`\|null | Plain opaque string, **not** a Laravel timestamp cast — see [Two Things Easy to Get Wrong](#two-things-easy-to-get-wrong) |
| `deleted_at` | `varchar(32)`\|null | Tombstone flag set by the client; a non-null value means "delete this on sync" (see [API](#api)) — not Eloquent `SoftDeletes` |

Model sets `public $timestamps = false;` so Eloquent never touches `updated_at`/`deleted_at` automatically.

### `user_plan_states`

One row per user per plan they've ever activated.

| Column | Type | Notes |
|---|---|---|
| `id` | bigint PK | Internal, auto-increment |
| `user_id` | FK → `users.id`, `ON DELETE CASCADE` | |
| `plan_id` | `varchar(64)` | Matches the training API's plan id (`training_plans.slug`). Unique per user |
| `is_active` | boolean | |
| `activated_at` | `varchar(32)`\|null | Conflict-resolution timestamp for this table |
| `start_date` | `varchar(32)`\|null | ISO date string |
| `notifications_enabled` | boolean | |
| `notification_time` | `varchar(5)`\|null | `"HH:MM"` |
| `notification_ids` | `json`\|null | Device-local Expo notification handles — stored/echoed verbatim, meaningless across devices |
| `calendar_enabled` | boolean | |
| `calendar_event_ids` | `json`\|null | Device-local calendar event handles — same as above |

### `user_training_history`

| Column | Type | Notes |
|---|---|---|
| `id` | bigint PK | Internal, auto-increment |
| `user_id` | FK → `users.id`, `ON DELETE CASCADE` | |
| `date` | `varchar(32)` | The app's completion timestamp — this is the row's identity client-side (no separate id). Unique per user |
| `workout_name` | `varchar(160)` | |
| `workout_type` | enum\|null | Same 5 values as `user_workouts.type` |
| `reps_completed`, `sets_completed` | int | |
| `status` | enum | `success`, `failed` |
| `plan_id` | `varchar(64)`\|null | |
| `updated_at` | `varchar(32)`\|null | |

---

## API

### `POST /api/user_training/sync`

Auth: `auth:sanctum` + `banned`. No CASL permission check — this is a self-service endpoint scoped to `$request->user()`, same as `/api/mail/unread_count` or `/api/set_faworite/*`.

Field names are camelCase, matching the app's TypeScript models directly.

**Request**

```json
{
  "workouts": [
    {
      "id": "1755000000000",
      "name": "7-3 Repeaters",
      "type": "fingerboard",
      "hangTime": 7, "restTime": 3, "reps": 6, "sets": 4, "recoverTime": 180,
      "updatedAt": "2026-08-13T10:00:00.000Z",
      "deletedAt": null
    }
  ],
  "plans": [
    {
      "id": "beginner-4-week",
      "isActive": true,
      "activatedAt": "2026-08-13T09:00:00.000Z",
      "startDate": "2026-08-13T00:00:00.000Z",
      "notificationsEnabled": true,
      "notificationIds": ["a1b2"],
      "calendarEnabled": false,
      "calendarEventIds": []
    }
  ],
  "history": [
    {
      "date": "2026-08-13T10:05:00.000Z",
      "workoutName": "7-3 Repeaters",
      "workoutType": "fingerboard",
      "repsCompleted": 6, "setsCompleted": 4,
      "status": "success",
      "updatedAt": "2026-08-13T10:05:00.000Z"
    }
  ]
}
```

**Response** — same three arrays, this user's full canonical state (post-write), plus `syncedAt`:

```json
{
  "workouts": [ /* same shape as request, tombstoned rows omitted */ ],
  "plans": [ /* same shape as request */ ],
  "history": [ /* same shape as request */ ],
  "syncedAt": "2026-08-13T10:05:01.000Z"
}
```

### Process (`UserTrainingSyncController::sync`)

For each of the three arrays, per row, keyed by its identity (`workouts`/`plans` by `id`, `history` by `date`):

1. **Upsert** — if the incoming row is newer (plain string-compare, see [Conflict Resolution](#conflict-resolution)) or doesn't exist yet for this user, write it.
2. **Workouts with `deletedAt` set** — the row is deleted outright for this user (hard delete; the response simply omits it).
3. After processing all rows, this user's full current set for all three tables is loaded and returned.

---

## Conflict Resolution

Per-row, last-write-wins on a plain string comparison (`UserTrainingSyncController::isNewer`) — never a parsed-date comparison:

| Table | Timestamp field compared |
|---|---|
| `user_workouts` | `updatedAt` |
| `user_plan_states` | `activatedAt` |
| `user_training_history` | `updatedAt` |

If the incoming row doesn't exist yet for this user, it's always written regardless of timestamp.

---

## Two Things Easy to Get Wrong

**Id stability is load-bearing, not cosmetic.** The app merges `local[]` and `remote[]` by matching on `id` (or `date` for history) and picking whichever side has the newer timestamp (`mergeById` in the app's `utils/sync.ts`). If this API ever returned a *different* id for a row than the one the client sent, the app would treat it as an unrelated new row, keep both forever, and duplicate it on every device from then on. That's exactly why `user_workouts.client_id` / `user_plan_states.plan_id` / `user_training_history.date` are separate columns from the internal auto-increment `id` — the internal id is used for joins/indexing only and must never leak into the JSON response as `id`.

**Timestamps are compared as plain strings, not parsed dates.** The app does `stampA >= stampB` on the raw ISO strings, which only equals chronological ordering if both strings share the same format (same precision, same `Z`/offset suffix). That's why `updated_at` / `activated_at` / `date` are plain `varchar` columns, not Eloquent `datetime` casts — a cast would silently reformat the value on save (dropping milliseconds, normalizing the offset) and break conflict resolution in edge cases. Store and echo these columns byte-for-byte in both directions.

---

## reCAPTCHA

No backend changes needed here — `/api/login` and `/api/register` already handle the mobile reCAPTCHA WebView bridge. The only action item for mobile logins to actually pass verification in production is making sure the backend's mobile-specific reCAPTCHA site/secret key env vars are set (see [AUTH.md](AUTH.md)) — without them there's only the web key to verify against, and a WebView-minted token generally scores too low to pass that threshold.

---

[Go back](../README.md)
