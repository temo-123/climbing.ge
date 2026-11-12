# Users dashboard

User panel → **Users** (`/users_list`, `user › show`) → **Dashboard** tab (the default).

## Page

`pages/usersListPageComponent.vue` is one page with four `cg-segmented` tabs. The open tab is in `?view=`:

| Tab | `?view=` | Needs | Component |
|---|---|---|---|
| **Dashboard** (default) | — | `user › show` | `items/dashboards/UsersOverviewComponent.vue` |
| **Users & roles** | `list` | `user › show` | the users / roles / permissions tables (as before) |
| **Guest commenters** | `commenters` | `commenter › show` | `items/users/GuestCommentersComponent.vue` (was `/non_registered_commenter_list`) |
| **Notifications** | `notifications` | `user_notification › show` | `items/users/NotificationAnalyticsComponent.vue` (was `/notification_analytics`) |

- A tab shows only with its permission. If `?view=` names a tab the user can't open, the first allowed tab opens. The route (`usersList`) and the left-menu entry accept any of the three permissions, so commenter / notification staff still reach their tab. The users tables load only with `user › show`.
- Switching tabs changes only the query. The route watcher reloads the tables only when the path changes, and each tab mounts when opened.
- The old URLs redirect to their tab (`/non_registered_commenter_list` → `?view=commenters`, `/notification_analytics` → `?view=notifications`). The two old pages and their left-menu entries are removed. The home page "mail alerts" button opens the Notifications tab.
- The dashboard's quick buttons (*Users list*, *Guest commenters*, *Notification analytics*) switch tabs (`open-view` event), they don't leave the page. *Sync admin permissions* shows on the Users & roles tab only. On phones the tabs show icons only.

## Dashboard

`items/dashboards/UsersOverviewComponent.vue`, data `GET set_user/get_user_stats` (`UsersController::get_user_stats` → `App\Services\UserStatsService::build()`, `user › show`). Only counts; the newest-sign-ups list returns id, name, image and dates.

- Header: title, one-line lead, quick buttons *Users list*, *Guest commenters*, *Notification analytics* (switch to that tab; each only with its permission), refresh.
- Headline: total users (% verified), new this week (today · 30 days), active this week (`last_seen_at`; today · 30 days), not verified (rope colour when > 0; banned · team members).
- **Sign-ups per month**: 12 CSS columns, last 12 months. **Activity**: today / 7 days / 30 days / never seen as share of all users.
- **Profile completeness**: date of birth, photo, phone, city & country, welcome questions — % bars (green ≥ 75, yellow ≥ 40, orange below).
- **Roles**: users per role (top 6, "Show all N roles"); admin bold, ban red.
- **Age groups** from `birthday` (<18, 18–24, 25–34, 35–44, 45+) with "N without"; empty state until sign-ups add birthdays.
- **Languages & sign-in**: EN / KA split bar; e-mail & password vs Google vs Facebook (`social_accounts`), climber follows (`user_follows`).
- **Newest sign-ups**: 8 cards (avatar / initials, joined …, verified badge). Click → the same User info modal as the Users list; its *Edit user* stacks the Manage modal, and a save reloads the stats.


i18n: `admin.user_stats.*` (`view_*` for the tabs, `commenters_hint`).
