# Climber points & active-climbers list

Guidebook → **Climbers** (`/climbers`, `ClimberListPageComponent.vue`) and every climber profile (`/climber/:id`, `ClimberProfileContentComponent.vue`). Technical reference: [CLIMBER_PROFILE.md](../../CLIMBER_PROFILE.md#the-points-system). Weights: [COEFFICIENTS.md](../../COEFFICIENTS.md#climber-profile-points).

## Design concept

Points are a simple "how much does this climber give to the site" score. Every public contribution earns points; climbing (ascents) is worth more than writing (reviews, comments). There is no ledger: the total is calculated on every request from the activity that exists right now, so deleting or hiding something takes its points away at once, and changing a weight re-scores everyone.

The climbers list is a directory of **active** climbers, not of all accounts. Registered users who never did anything are left out, so the list isn't full of empty cards.

## What earns points

| Activity | Counted from | Coefficient | Default |
|---|---|---|---|
| Summit ascent | `summit_ascent_users` | `points_ascent` | 10 |
| MTP ascent | `mtp_ascents` | `points_mtp_ascent` | 8 |
| Route ascent (logbook tick, any style) | `route_ascents` | `points_route_ascent` | 3 |
| Route review | `sport_route_reviews` | `points_route_review` | 5 |
| MTP review | `mtp_reviews` | `points_mtp_review` | 5 |
| Article comment | `article_comment_user` → `article_comments` | `points_comment` | 2 |

`total = Σ count × coefficient`. Admins change the weights in Site Options → Coefficients; whole numbers only.

## Rules

- **Only visible activity counts.** Reviews and comments need `published = 1` and `admin_hidden = 0`. Ascents always count.
- **Active climber = points > 0.** `/climbers` (`GET get_climber_profile/list`) keeps only users with a positive total (`HAVING` on the points expression), on top of the existing rules: verified email, has a name, not banned, not the logged-in user.
- A climber with 0 points still has a working profile URL; the profile then shows the "No public activity yet" text.
- The profile's counts (`*_count` fields) and the radar chart use the exact numbers the points come from, so they always add up.
- Sort "Top active" orders by the same total.

## States

| Where | State | Shows |
|---|---|---|
| `/climbers` | no active climbers match the search | empty grid |
| Profile | `points_total = 0` | "No public activity yet." |
| Profile | has activity | points badge, 6-axis radar (route reviews, MTP reviews, summit ascents, route ascents, MTP ascents, comments), recent sections for each type that has rows |
| "My stats" card (`CurrentUserClimberCardComponent`) | logged in | own points + the same 6-axis radar |

## Code

- `App\Models\User::pointsCategories()` is the single list of categories. `withPointsCounts()`, `pointsFromCounts()`, `pointsOrderByExpression()` and `pointsTotal()` are all built from it. To add a category, add one entry there plus its coefficient default in `config/coefficients.php`, a `sync_*` migration for the coefficient row, and a radar label in `resources/lang/i18n/{en,ka}.json` (`global.follow.*`).
- The route/MTP ascent coefficient rows come from migration `2026_11_06_120000_sync_route_and_mtp_ascent_points_coefficients.php`.
- The admin users table shows the same total (`UsersController`).
