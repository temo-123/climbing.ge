# Admin user-info modal

User panel → **Users** (`/users_list`, `user › show`) → click a user's **name**. Read-only; editing stays in the [Manage-user modal](admin_user_manage.md).

## Design concept

Name = *look*, gear = *change*. The info modal shows everything the database holds about one user, across every subdomain, in one place. Its **Edit user** button opens the Manage modal stacked on top of it. When a Manage tab saves, the page reloads the table and calls `reload()` on the info modal, so it stays live without closing.

## Files

- Backend: `app/Services/AdminUserOverviewService.php`, `UsersController::get_user_overview` → `GET set_user/get_user_overview/{user_id}` (`user › show`).
- Frontend: `resources/js/components/user/items/modal/tab_modals/user_info/UserInfoModalComponent.vue` + `InfoBlock.vue` (one titled list: total count, the latest rows, and a column config with `fmt: date | bool | stars | state | person | text`).
- Page: `usersListPageComponent.vue` puts `<UserInfoModal>` **before** `<UserManageModal>`. All StackModal overlays use the same z-index, so DOM order decides which one is on top.
- i18n: `admin.user_info.*` in `en.json` / `ka.json`.

## Response shape

Every list is `{ count, items }`, where `count` is the real total and `items` holds the latest `LIMIT` (15) rows. Each top-level section is built inside its own try/catch, so a missing table (for example, training tables) only returns `null` for that section.

| Section | Contents (source tables) |
|---|---|
| `user` | All `users` columns except `password` / `remember_token`, plus `is_banned` |
| `account` | roles, extra permissions, partner org (+discount), warehouse, social logins, Strava (athlete name / scope / dates only), Sanctum sessions (`name`, `last_used_at`, `created_at` — never the token hash), notification-preference summary |
| `points` | Per-category count × coefficient = points, same formula as `User::pointsFromCounts()` |
| `activity` | route / MTP reviews, route / MTP / summit ascents, article comments, partner posts, condition reports, film ratings, blog posts |
| `shop` | orders (`orders.user_id` ∪ `orders_user`) with item counts + total amount, cart, addresses, product feedback, stock alerts, tour reservations, products / tours the user owns (`user_products` / `user_tours`) |
| `favorites` | outdoor areas, products, films, interested events |
| `social` | followers / following (`id, name, surname, image` only), `user_sites` |
| `donations` | list + totals per currency |
| `training` | custom workouts, plan states, training history, boards, board route / ascent counts |
| `system` | complaints filed / received, tasks for / from, user requests, content-notification log |

## UI

- **Header:** avatar, name, `#id`, email · phone, badges (verified / banned / team / roles / organization), points, last seen, registered. Next to the "Not verified" badge, with `user › edit`: **Verify User** and **Resend verification e-mail** (same endpoints as the Manage modal; verifying reloads the users table) — see [global/email_verification.md](../global/email_verification.md).
- **Tabs:** Overview (clickable counters, profile fields, account & access, points breakdown, sessions) · Activity · Shop · Social & favorites (including donations) · Training app · Moderation & system. Each tab label shows its total row count.
- Names in the follower / following / complaint lists open that user's info in the same modal.
- **Footer:** Public profile (`{APP_SSH}{GUIDBOOK_URL}/climber/{id}`, new tab) · Close · Edit user.

## Rules

- Never add secret columns to the service: tokens, password hashes, Strava `access_token` / `refresh_token`.
- When a new table gets a `user_id`, add it to the matching section here and in the service.
