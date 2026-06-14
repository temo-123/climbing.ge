<p align="center"><img src="public/images/site_img/site_logo/climbing.ge(background).jpg" alt="climbing.ge logo" width="400"></p>
<h1 align="center">climbing.ge — Open Source Climbing Platform</h1>

**Laravel 11 (PHP 8.2) · Vue 3 · Multi-subdomain SPA · MySQL · Sanctum**

climbing.ge is a Georgian rock climbing guidebook, social network, e-commerce store, and summit tracking platform — all served from a single codebase across multiple subdomains.

---

## Subdomains

| Domain | Description | Docs |
|---|---|---|
| `climbing.ge` | Rock climbing guidebook | [GUIDBOOK.md](docs/GUIDBOOK.md) |
| `shop.climbing.ge` | Gear & tours store | [SHOP.md](docs/SHOP.md) |
| `blog.climbing.ge` | Climbing blog & news | [BLOG.md](docs/BLOG.md) |
| `summit.climbing.ge` | Summit ascent tracker (QR-based) | [SUMMIT.md](docs/SUMMIT.md) |
| `films.climbing.ge` | Climbing films portal | [FILMS.md](docs/FILMS.md) |
| `user.climbing.ge` | User dashboard & admin CMS | [USER_PAGE.md](docs/USER_PAGE.md) |

---

## Quick Start

### Requirements
- PHP 8.2+, Composer
- Node.js 18+, npm
- MySQL 8+
- Apache/Nginx with virtual hosts for each subdomain

### Installation

```bash
# 1. Clone and install dependencies
composer install
npm install

# 2. Configure environment
cp "docs/BACKEND/examples/_.env .exemple" .env
php artisan key:generate

# 3. Database
php artisan migrate --seed

DELETE FROM user_role;
DELETE FROM role_permissions;
DELETE FROM user_permissions;
DELETE FROM permissions;
DELETE FROM roles;

php artisan db:seed

# 4. Storage symlink
php artisan storage:link

# 5. Build frontend
npm run dev        # development
npm run build      # production

# 6. Queue worker (needed for mail)
php artisan queue:work
```

See `docs/BACKEND/examples/apatch_config.conf.example` for Apache virtual host configuration.

### Key Environment Variable Groups

| Group | Variables | Notes |
|---|---|---|
| **App** | `APP_NAME`, `APP_KEY`, `APP_ENV`, `APP_URL` | Run `php artisan key:generate` after copy |
| **Subdomains** | `SITE_URL`, `SHOP_URL`, `BLOG_URL`, `SUMMIT_URL`, `FILMS_URL`, `USER_PAGE_URL`, `FORUM_URL` | Set to actual hostnames; `MIX_*` mirrors expose them to Vue |
| **Sanctum** | `SANCTUM_STATEFUL_DOMAINS`, `SESSION_DOMAIN`, `SANCTUM_TOKEN_EXPIRATION` | Must list all subdomains; token expiry in minutes (default 10080 = 7 days) |
| **Database** | `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` | MySQL 8+ |
| **Mail** | `MAIL_MAILER`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD` | SMTP for outgoing queued notifications |
| **IMAP** | `IMAP_HOST`, `IMAP_PORT`, `IMAP_USERNAME`, `IMAP_PASSWORD` | Inbox access for messaging features |
| **Social OAuth** | `FACEBOOK_CLIENT_ID/SECRET/URL`, `GOOGLE_CLIENT_ID/SECRET/URL` | Callback URLs must match OAuth app settings |
| **reCAPTCHA v3** | `GOOGLE_CAPTCHA_V3_SITE_KEY`, `GOOGLE_CAPTCHA_V3_SECRET_KEY` | Frontend key also exposed as `MIX_GOOGLE_CAPTCHA_V3_SITE_KEY` |
| **Analytics** | `MIX_*_ANALITICS_ID`, `MIX_GOOGLE_MAPS_API_KEY` | Per-subdomain Google Analytics IDs |
| **Payments** | `FLITT_MERCHANT_ID`, `FLITT_SECRET_KEY`, `FLITT_API_VERSION` | Flitt payment gateway |
| **Donations** | `DONATION_TBC_IBAN`, `DONATION_TBC_ACCOUNT_NAME`, `DONATION_TBC_BANK_CODE` | TBC Bank donation display info |
| **Realtime** | `PUSHER_APP_ID`, `PUSHER_APP_KEY`, `PUSHER_APP_SECRET`, `PUSHER_APP_CLUSTER` | Laravel Echo / Pusher for live events |
| **Queue / Redis** | `QUEUE_CONNECTION`, `REDIS_HOST`, `REDIS_PORT` | `database` driver by default; Redis required for Horizon |

---

## Architecture Overview

### Multi-Subdomain SPA

All six subdomains share a **single compiled Vue bundle** (`public/assets/js/app.js`). On page load the bundle checks `window.location.hostname` against `MIX_*_URL` env vars and activates the correct router and root component.

| Subdomain | Root Component | Router File |
|---|---|---|
| `climbing.ge` | `IndexComponent` | `SiteRoutes.js` |
| `shop.climbing.ge` | `MainWrapper` | `ShopRoutes.js` |
| `blog.climbing.ge` | `BlogMainComponent` | `BlogRoutes.js` |
| `summit.climbing.ge` | `SummitMainComponent` | `SummitRouter.js` |
| `films.climbing.ge` | `StudiaComponent` | `FilmsRoutes.js` |
| `user.climbing.ge` | `HomeComponent` | `UserRoutes.js` |

### Backend API Structure

```
routes/api/
├── auth.php                    # Login, register, OAuth, password reset
├── general.php                 # Search, site data, editor image upload, social links
├── get_guide_routes.php        # Public guide/climbing data
├── get_shop_routes.php         # Public shop/product data
├── get_blog_routes.php         # Public blog posts
├── get_films_routes.php        # Public films
├── get_user_routes.php         # Authenticated user data
├── summit_public_routes.php    # Public summit data
└── admin/
    ├── set_guide_routes.php    # Admin guide CRUD
    ├── set_shop_routes.php     # Admin shop CRUD
    ├── set_blog_routes.php     # Admin blog CRUD
    ├── set_films_routes.php    # Admin films CRUD
    ├── set_user_routes.php     # Admin user management
    └── set_summit_routes.php   # Admin summit CRUD
```

Protected routes require `auth:sanctum` + custom `banned` middleware.

### Frontend Services

| Service | Purpose |
|---|---|
| **Pinia** (`store/`) | App-wide reactive state |
| **auth_user** (`store/auth_user.js`) | Authenticated user via `/api/auth_user` |
| **CASL** (`services/ability/`) | Permission checks: `$can(action, subject)` |
| **i18n** (`services/localization/`) | English (`us`) / Georgian (`ka`) translations |
| **`$bus`** | Global event bus for cross-component events |

---

## Commands

```bash
npm run dev          # Development build
npm run watch        # Watch mode
npm run build        # Production build

php artisan migrate --seed    # Fresh DB with seed data
php artisan queue:work        # Process queued jobs
php artisan test              # Run all tests
```

---

## Production Deployment

### 1. Environment — fill every placeholder in `.env`

```bash
# Core
APP_ENV=production
APP_DEBUG=false
APP_URL=https://climbing.ge

# Real subdomain URLs (used by Vue router switch)
MIX_SITE_URL=climbing.ge
MIX_SHOP_URL=shop.climbing.ge
MIX_BLOG_URL=blog.climbing.ge
MIX_USER_PAGE_URL=user.climbing.ge
MIX_SUMMIT_URL=summit.climbing.ge
MIX_FILMS_URL=films.climbing.ge

# Sanctum — list every subdomain or cross-domain auth breaks
SANCTUM_STATEFUL_DOMAINS=climbing.ge,shop.climbing.ge,blog.climbing.ge,user.climbing.ge,summit.climbing.ge,films.climbing.ge
SESSION_DOMAIN=.climbing.ge

# Database — use production credentials (not root:123)
DB_HOST=...
DB_DATABASE=...
DB_USERNAME=...
DB_PASSWORD=...

# Mail — use real SMTP, not Mailtrap
MAIL_MAILER=smtp
MAIL_HOST=...
MAIL_PORT=465
MAIL_USERNAME=...
MAIL_PASSWORD=...

# These are "..." placeholders that must be filled before going live:
FACEBOOK_CLIENT_ID=...
FACEBOOK_CLIENT_SECRET=...
FLITT_MERCHANT_ID=...
FLITT_SECRET_KEY=...
DONATION_TBC_IBAN=...
DONATION_TBC_ACCOUNT_NAME=...
MIX_SITE_ANALITICS_ID=...       # Google Analytics per subdomain
MIX_SHOP_ANALITICS_ID=...
MIX_BLOG_ANALITICS_ID=...
MIX_SUMMIT_ANALITICS_ID=...
MIX_FILMS_ANALITICS_ID=...
MIX_USER_ANALITICS_ID=...
```

### 2. One-time server commands

```bash
# Production frontend build (versioned + minified)
npm run build

# Laravel performance caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Database
php artisan migrate

# Make uploaded files publicly accessible
php artisan storage:link

# Generate sitemap
php artisan generate:sitemap
```

### 3. Queue worker — required for emails

The app sends emails asynchronously via the `UserNotifications` job. Without a running worker emails are never delivered. Use **supervisord** or systemd to keep it alive:

```ini
# /etc/supervisor/conf.d/climbing-horizon.conf
[program:climbing-horizon]
command=php /var/www/html/artisan horizon
user=www-data
autostart=true
autorestart=true
redirect_stderr=true
stdout_logfile=/var/log/climbing-horizon.log
```

```bash
supervisorctl reread && supervisorctl update && supervisorctl start climbing-horizon
```

Or without Horizon:
```bash
php artisan queue:work --tries=3 --sleep=3
```

### 4. Nginx — one server block per subdomain

```nginx
server {
    listen 443 ssl;
    server_name climbing.ge;
    root /var/www/html/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* { deny all; }
}
# Repeat for shop., blog., user., summit., films.
```

### 5. SSL certificates

```bash
certbot --nginx \
  -d climbing.ge \
  -d shop.climbing.ge \
  -d blog.climbing.ge \
  -d user.climbing.ge \
  -d summit.climbing.ge \
  -d films.climbing.ge
```

> Sanctum session cookies use `SESSION_DOMAIN=.climbing.ge` which requires HTTPS across all subdomains.

### 6. File permissions

```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Deployment checklist

| # | Task | Why |
|---|---|---|
| 1 | Fill all `.env` placeholders | App won't start without `APP_KEY`; features break without other keys |
| 2 | `npm run build` | Must be production build — dev build is slow and exposes source maps |
| 3 | `php artisan migrate` + `storage:link` | Database up to date; uploaded files accessible |
| 4 | `config:cache` + `route:cache` | Significant performance improvement |
| 5 | SSL on all 6 subdomains | Required for Sanctum cross-subdomain cookies |
| 6 | Nginx block per subdomain | Each domain needs its own `server_name` |
| 7 | Horizon / queue worker running | Without it, notification emails never send |
| 8 | `generate:sitemap` | Submit to Google Search Console after first run |

---

## Documentation Index

| File | Contents |
|---|---|
| [docs/GUIDBOOK.md](docs/GUIDBOOK.md) | Guide: articles, sectors, routes, regions |
| [docs/SHOP.md](docs/SHOP.md) | Shop: products, orders, tours, cart |
| [docs/BLOG.md](docs/BLOG.md) | Blog: posts and news |
| [docs/SUMMIT.md](docs/SUMMIT.md) | Summit: QR ascent tracking |
| [docs/FILMS.md](docs/FILMS.md) | Films portal |
| [docs/USER_PAGE.md](docs/USER_PAGE.md) | User dashboard and admin CMS |
| [docs/AUTH.md](docs/AUTH.md) | Authentication & authorization |
| [docs/BACKEND/API.md](docs/BACKEND/API.md) | Full API reference |
| [docs/FRONTEND/FRONTEND.md](docs/FRONTEND/FRONTEND.md) | Frontend architecture & components |

---

<h5 align="center">Project version 13.0.0</h5>
<h2 align="center">Best wishes from climbing.ge</h2>
<p align="center"><img src="public/images/site_img/site_logo/header_logo(background).png" alt="header logo" width="400"></p>
