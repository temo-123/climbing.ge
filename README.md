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

# 4. Storage symlink
php artisan storage:link

# 5. Build frontend
npm run dev        # development
npm run build      # production

# 6. Queue worker (needed for mail)
php artisan queue:work
```

See `docs/BACKEND/examples/apatch_config.conf.example` for Apache virtual host configuration.

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
├── general.php                 # Search, site data, CKEditor, social links
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
