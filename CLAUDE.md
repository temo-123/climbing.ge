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
- `general.php` — search, site data, social links, CKEditor upload

Protected routes use `auth:sanctum` + custom `banned` middleware. The `AjaxHeader` middleware enforces `X-Requested-With: XMLHttpRequest` on API calls.

### Authentication Flow

1. Frontend POSTs encrypted password to `/api/login` (JSEncrypt)
2. On success, Sanctum session cookie is set; `x_xsrf_token` stored in `localStorage`
3. On 401/419 responses, the axios interceptor (in `app.js`) clears localStorage and redirects to the `login` named route

### Frontend State & Services

- **Pinia** is the primary store (`resources/js/store/`). `auth_user.js` module (Vuex-style) handles authenticated user state via `/api/auth_user`.
- **CASL** (`resources/js/services/ability/ability.js`) handles permission checks globally as `$can(action, subject)` / `$ability`.
- **i18n** (`resources/js/services/localization/i18n.js`) supports English (`en`) and Georgian (`ka`). Language is stored in `localStorage` as `'us'` (maps to `en`) or `'ka'`. Georgian routes are prefixed with `/ka`.
- **`$bus`** is a simple global event bus (pub/sub) attached to `app.config.globalProperties` for cross-component events.
- **`$going`** (from `easy_navigation_mixin.js`) and **`$editor_config`** (from `editor_config_mixin.js`) are global helpers.

### Localization Files

Translation strings live in `resources/lang/i18n/en.json` and `resources/lang/i18n/ka.json`. `MIX_*` prefixed env vars are the way to expose `.env` values to Vue (via Laravel Mix's `DefinePlugin`).

### Key Frontend Component Areas

Components are grouped by subdomain under `resources/js/components/`: `guide/`, `shop/`, `blog/`, `films/`, `summit/`, `user/`, plus `auth/`, `global_components/`, and `errors/`.

Rich-text editing uses three globally-registered editor components (`big_editor`, `small_editor`, `mini_editor` — all Quill-based) defined in `resources/js/components/user/items/form/parts/editor/`.

### Environment Setup

Copy `docs/BACKEND/examples/_.env .exemple` to `.env`. Key groups of variables to fill:
- `DB_*` — MySQL connection
- `SANCTUM_STATEFUL_DOMAINS` / `SESSION_DOMAIN` — must include all subdomains
- `MIX_SITE_URL`, `MIX_SHOP_URL`, `MIX_USER_PAGE_URL`, etc. — subdomain hostnames used by the Vue router switch
- `FACEBOOK_*` / `GOOGLE_*` — social OAuth (Socialite)
- `FLITT_*` — payment gateway
- `PUSHER_*` — realtime (Laravel Echo)
- `GOOGLE_CAPTCHA_V3_*` — reCAPTCHA
