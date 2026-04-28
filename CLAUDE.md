# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

**climbing.ge** — Georgian rock climbing guidebook, social network, and e-commerce platform. Built with Laravel 11 (PHP 8.2) backend and Vue 3 SPA frontend, served across multiple subdomains from a single codebase.

## Commands

### Frontend
```bash
npm run dev          # development build
npm run watch        # watch mode (rebuild on change)
npm run build        # production build (versioned assets)
```
Output goes to `public/assets/js` and `public/assets/css`.

### Backend
```bash
php artisan serve                   # local dev server
php artisan migrate --seed          # fresh DB with default data
php artisan queue:work              # process queued jobs (mail etc.)
php artisan horizon                 # Laravel Horizon queue dashboard
```

### Tests
```bash
php artisan test                    # all tests
php artisan test --filter TestName  # single test
./vendor/bin/phpunit tests/Unit/    # PHPUnit directly (Unit suite)
./vendor/bin/phpunit tests/Feature/ # PHPUnit directly (Feature suite)
```
Tests run against an in-memory SQLite database (configured in `phpunit.xml`).

## Architecture

### Multi-Subdomain SPA

The entire Vue app is compiled into one bundle (`resources/js/app.js`). On load it checks `window.location.hostname` against `MIX_*_URL` env vars and activates the matching set of routes and root component:

| Subdomain | Root component | Route file |
|---|---|---|
| `climbing.ge` | `IndexComponent` (guidebook) | `SiteRoutes.js` |
| `shop.climbing.ge` | `MainWrapper` | `ShopRoutes.js` |
| `blog.climbing.ge` | `BlogMainComponent` | `BlogRoutes.js` |
| `summit.climbing.ge` | `SummitMainComponent` | `SummitRouter.js` |
| `films.climbing.ge` | `StudiaComponent` | `FilmsRoutes.js` |
| `user.climbing.ge` | `HomeComponent` (dashboard) | `UserRoutes.js` |

Each subdomain also sets `axios.defaults.baseURL` to its own API path.

### Backend API Structure

All HTTP routes in `routes/web.php` are domain-grouped catch-alls that serve the SPA blade view. All data goes through `routes/api/`:

- `auth.php` — login, social OAuth, password reset, Sanctum token
- `get_guide_routes.php`, `get_shop_routes.php`, `get_blog_routes.php`, `get_films_routes.php`, `get_user_routes.php` — domain-specific API routes
- `admin/set_*.php` — protected admin CRUD routes (all require `auth:sanctum` + `banned` middleware)
- `general.php` — search, site data, social links, CKEditor upload

Protected routes use `auth:sanctum` + custom `banned` middleware. The `AjaxHeader` middleware enforces `X-Requested-With: XMLHttpRequest` on API calls.

### Recently Added Routes

| Method | URI | Controller | Notes |
|---|---|---|---|
| POST | `api/set_user/team/edit_member_status/{id}` | `Admin\Guide\TeamMemberController@edit_member_status` | Edit user team member status & description. Requires `team › edit` permission. Defined in `routes/api/admin/set_user_routes.php`. |

### Authentication Flow

1. Frontend POSTs encrypted password to `/api/login` (JSEncrypt)
2. On success, Sanctum session cookie is set; `auth_token` (Bearer) and `x_xsrf_token` stored in `localStorage`
3. Social login (OAuth) sets `x_xsrf_token` then fetches Bearer token via `GET /api/token` and stores as `auth_token`
4. On 401/419 responses, the axios interceptor (in `app.js`) clears `auth_token` + `x_xsrf_token` and redirects to the `login` named route on the user subdomain
5. The `UserRoutes.js` router guard checks `auth_token || x_xsrf_token` to decide if a user is authenticated

### Permission System

- Roles and permissions stored in `roles`, `permissions`, `user_role`, `user_permissions`, `role_permissions` tables
- `User::hasPermissionFor($subject, $action)` loads all permissions once per request (no N+1) via `getAllPermissions()`
- `PermissionService::authorize($subject, $action)` returns a 403 JSON response or `null` (allow). Called at the top of every admin controller method.
- The `auth_user` endpoint (`GET /api/auth_user`) returns `casl_permissions: [{action, subject}]` alongside user data
- On the user subdomain, `app.js` calls `useAuthStore().fetchUser()` after mount, which calls `updateAbility()` in `services/ability/ability.js` to sync CASL rules with backend permissions
- Frontend permission checks: `this.$can('add', 'article')` / `this.$ability.can('edit', 'product')`

### Frontend State & Services

- **Pinia** is the state manager (`resources/js/store/`). Three stores:
  - `store/auth.js` (`useAuthStore`) — authenticated user + CASL sync
  - `store/dataTableTabs.js` (`useDataTableTabsStore`) — pagination state per tab
  - `store/routeLoader.js` — exports `isRouteLoading` ref used by router hooks
- **CASL** (`resources/js/services/ability/ability.js`) handles permission checks globally as `$can(action, subject)` / `$ability`. Rules are loaded from the backend via `updateAbility()` after auth_user fetch.
- **i18n** (`resources/js/services/localization/i18n.js`) supports English (`en`) and Georgian (`ka`). Language is stored in `localStorage` as `'us'` (maps to `en`) or `'ka'`. Georgian routes are prefixed with `/ka`.
- **`$bus`** is a simple global event bus (pub/sub) attached to `app.config.globalProperties` for cross-component events. Used for toast notifications: `this.$bus.$emit('toast', { type, title, message, duration })`.
- **`$going`** (from `easy_navigation_mixin.js`) and **`$editor_config`** (from `editor_config_mixin.js`) are global helpers.

### Global Error Handling

API errors are handled centrally in `app.js` axios interceptors:

| Status | Behaviour |
|---|---|
| 419 | Refreshes CSRF cookie once, retries request. Falls through to 401 if refresh fails. |
| 401 | Clears tokens, redirects to login (user subdomain only). |
| 403 (banned) | Shows ban toast notification, clears tokens, redirects home after 3s. |
| 403 (permission) | Shows "no permission" warning toast. |
| 422 | Passed through to component for inline form validation. |
| 5xx / other | Shows error toast with status code. |

Toast notifications use the globally registered `<toast-notification>` component (Bootstrap 5 toasts). Place `<toast-notification />` in the root layout of each subdomain.

### Localization Files

Translation strings live in `resources/lang/i18n/en.json` and `resources/lang/i18n/ka.json`. `MIX_*` prefixed env vars are the way to expose `.env` values to Vue (via Laravel Mix's `DefinePlugin`).

### Key Frontend Component Areas

Components are grouped by subdomain under `resources/js/components/`: `guide/`, `shop/`, `blog/`, `films/`, `summit/`, `user/`, plus `auth/`, `global_components/`, and `errors/`.

Rich-text editing uses three globally-registered editor components (`big_editor`, `small_editor`, `mini_editor` — all Quill-based) defined in `resources/js/components/user/items/form/parts/editor/`.

Grade conversion (FR ↔ YDS ↔ UIAA) is in `resources/js/mixins/grade_chart_mixin.js` as lookup tables. Add new grades there.

### Environment Setup

Copy `docs/BACKEND/examples/_.env .exemple` to `.env`. Key groups of variables to fill:
- `DB_*` — MySQL connection
- `SANCTUM_STATEFUL_DOMAINS` / `SESSION_DOMAIN` — must include all subdomains
- `SANCTUM_TOKEN_EXPIRATION` — token lifetime in minutes (default 10080 = 7 days)
- `MIX_SITE_URL`, `MIX_SHOP_URL`, `MIX_USER_PAGE_URL`, etc. — subdomain hostnames used by the Vue router switch
- `FACEBOOK_*` / `GOOGLE_*` — social OAuth (Socialite)
- `FLITT_*` — payment gateway
- `PUSHER_*` — realtime (Laravel Echo)
- `GOOGLE_CAPTCHA_V3_*` — reCAPTCHA

### Mail

Queued notification emails use `App\Mail\UserNotificationMessage` (dispatched by `App\Jobs\UserNotifications`). The job retries twice on failure.
