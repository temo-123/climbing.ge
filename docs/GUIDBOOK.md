# Rock Climbing Guidebook — climbing.ge

**climbing.ge** is a Georgian rock climbing guidebook covering outdoor sport climbing, mountaineering, ice climbing, bouldering, and indoor facilities.

---

## Table of Contents

- [Overview](#overview)
- [Frontend Pages](#frontend-pages)
- [Backend API](#backend-api)
- [Database Structure](#database-structure)
- [Admin Panel](#admin-panel)

---

## Overview

**Subdomain:** `climbing.ge`  
**Root Component:** `resources/js/components/guide/GuideMainComponent.vue` (IndexComponent)  
**Router:** `resources/js/routes/SiteRoutes.js`

### Article Categories

| Category | Description |
|---|---|
| `outdoor` | Outdoor sport climbing spots |
| `mount_route` | Mountaineering routes |
| `ice` | Ice climbing |
| `indoor` | Indoor climbing gyms |
| `bouldering` | Bouldering areas |
| `other` | Other climbing-related content |

---

## Frontend Pages

### `IndexPageComponent.vue` — Homepage

Sections:
- Hero swiper (`SwiperComponent`)
- What We Do (`WhatWeDoComponent`)
- Topo map (from `$globalSiteData.data.map`)
- Latest news (`newsCard`, `bigNewsCard`)
- Special/featured articles (`SpecialArticleComponent`)
- Upcoming events (`EventComponent`)
- Other articles (`OtherArticlesComponent`)
- Tech tips (`TechtipsComponent`)
- Gallery (`IndexGalleryComponent`)
- Team members slider
- Products slider (cross-promo from shop)

### List Pages (`pages/lists/`)

Each category has a dedicated list page:
- `OutdoorListComponent.vue` — sport climbing regions
- `MountaineeringListComponent.vue` — mountaineering routes
- `IceListComponent.vue` — ice climbing
- `IndoorListComponent.vue` — indoor gyms
- `EventsListPageComponent.vue` — competitions and events

**Common pattern:**
```html
<h1 class="index_h2">Section Title</h1>
<div class="bar"><i class="fa fa-icon"></i></div>
<h3 class="article_list_short_description">Description</h3>
<!-- cards in .article_card_container -->
```

Features: region filter dropdown, grouped/list view toggle (`ViewControlsComponent`).

### Article Pages (`pages/pages/`)

- `OutdoorPageComponent.vue` — outdoor spot with sectors, routes, images, map
- `MountaineeringPageComponent.vue` — mountaineering route
- `IcePageComponent.vue` — ice climbing
- `IndoorPageComponent.vue` — indoor gym
- `EventPageComponent.vue` — event/competition detail
- `LocalBisnesPageComponent.vue` — local business

### Search

`SerchPageComponent.vue` — searches across articles, products, and films.

---

## Backend API

**File:** `routes/api/get_guide_routes.php`

### Key Public Endpoints

| Method | Path | Description |
|---|---|---|
| GET | `/api/get_article/get_locale_articles/{category}/{lang}` | Articles by category + language |
| GET | `/api/get_article/get_locale_article_on_page/{category}/{lang}/{url_title}` | Full article page data |
| GET | `/api/get_outdoor/get_filtred_outdoor_spots/{lang}/{filter_id}/{published}` | Filtered outdoor spots |
| GET | `/api/get_sector/get_sector_and_routes/{article_id}` | Sectors + routes for climbing spot |
| GET | `/api/get_route/get_route_for_modal/{route_id}` | Route detail |
| GET | `/api/get_mount_route/get_mount_routes_by_maunt/{lang}` | Mountaineering routes grouped by mountain |
| GET | `/api/get_event/get_event_on_site_list/{lang}` | Events list |
| GET | `/api/get_region/get_all_outdoor_regions` | All outdoor regions |
| GET | `/api/get_article/last_news/{lang}` | Latest news |

Full endpoint list in [BACKEND/API.md](BACKEND/API.md#guide--public).

---

## Database Structure

### Full Entity Hierarchy

![Guidebook full hierarchy](DEMO_IMAGES/Guidbook/Guide_full_hierarchy.svg)

### Articles (Global + Locale)

Articles have two tables:
- `articles` — global data: published status, image, category, url_title
- `locale_articles` — locale version: title, short_description, text content per language

```
articles
├── id, category, url_title, image, published
└── locale_articles (1:many)
    └── article_id, lang, title, short_description, text
```

![Article structure](DEMO_IMAGES/Guidbook/Article_structure.svg)

### General Info

Reusable info blocks (contact details, warnings) that can be embedded in multiple articles.

```
general_infos
└── inserted into article blocks as references
```

![General info structure](DEMO_IMAGES/Guidbook/General_info_structure.svg)

### Outdoor Climbing Spot

An outdoor climbing area is an article with `category = 'outdoor'`.

```
article (outdoor)
├── spot_rocks_images       # Overview rock images for the area
├── sector_local_images     # Sub-area images with multiple sector links
└── sectors (1:many)
    ├── sector_images       # Topo images per sector
    └── routes (1:many)     # Climbing routes
```

![Climbing spot diagram](DEMO_IMAGES/Guidbook/Climbing_spot_overview.svg)  
![Sector images](DEMO_IMAGES/Guidbook/Sector_images_structure.svg)  
![Sector local images](DEMO_IMAGES/Guidbook/Sector_local_images_structure.svg)

### Routes

Sport routes and bouldering share the `routes` table (different `category` value).  
Multi-pitch routes use two tables:

```
mtps (multi-pitch routes)
└── mtp_pitchs (individual pitches, ordered)
```

![Route diagram](DEMO_IMAGES/Guidbook/Route_structure.svg)  
![Multi-pitch structure](DEMO_IMAGES/Guidbook/Mtp_structure.svg)

### Mountaineering Routes

```
mount_masives (mountain groups)
└── articles (mount_route category)
    └── locale_articles
```

![Mount route diagram](DEMO_IMAGES/Guidbook/Mount_route_structure.svg)

### Gallery

`galleries.image_type` values:

| Value | Used in |
|---|---|
| `header_image` | Section hero images |
| `index_gallery_image` | Homepage gallery |
| `article_image` | Article-specific gallery |

Article images linked via `gallery_image_article` pivot table.

![Gallery structure](DEMO_IMAGES/Guidbook/Gallery_structure.svg)

### Comments

Guests and authenticated users can comment on articles.

**Guest flow:** Name + email required → after submission, email matched against registered users → if match found, "Is it your comment?" notification sent to user's dashboard.

**Comment violations:** Any user can report a comment. Admin reviews and decides. Email notification sent.

**Public API field exposure (fixed September 2026):** the commenter/answerer `user` object attached to the public per-article comment feed (`get_article_comments`) is restricted to `id, name, surname, image` — it used to serialize the full `User` model, leaking `email`/`phone_number`/`country`/`city` to any anonymous visitor. The admin-only "all comments" moderation view (which does legitimately need the raw stored `name`/`surname`/`email` columns for guest commenters) is gated behind `auth:sanctum` + `comment › show` permission rather than having its data trimmed. Full writeup: [SECURITY_AND_PITFALLS.md](BACKEND/SECURITY_AND_PITFALLS.md).

![Comments diagram](DEMO_IMAGES/Guidbook/Comments_structure.svg)  
![Is it your comment](DEMO_IMAGES/Guidbook/Comment_queries.svg)  
![Comment complaints](DEMO_IMAGES/Guidbook/Comment_complaints.svg)  
![Public vs admin comment/review data exposure](DEMO_IMAGES/Guidbook/Comment_public_vs_admin_exposure.svg)

### Route & MTP Reviews

Authenticated climbers can leave a star rating + text review on a sport/bouldering route (`sport_route_reviews`, `Api\Guide\RoutesReitingController`) or a multi-pitch route (`mtp_reviews`, `Api\Guide\MtpReitingController`). Unlike comments, review creation is auth-only (no guest flow) — `create_route_review`/`create_mtp_review` reject a request with no valid Sanctum token.

**Public listings** (shown on the route/MTP page itself, no login required): `GET get_route_review/get_all_route_reviews/{route_id}` and `GET get_mtp_review/get_all_mtp_reviews/{mtp_id}`. Both are rendered by `RouteAllReviewsModal.vue` / `MtpAllReviewsModal.vue`, which link the reviewer's name to their [Climber Profile](CLIMBER_PROFILE.md) (`review.user.id`).

**Admin moderation** (all reviews site-wide, across every route — used to review/hide reviews and handle complaints): the frontend's `all_routes_reviews_page.vue` calls `get_route_review/get_all_review` for route reviews (backed by `Api\Guide\RoutesReitingController::get_all_review`) and `set_mtp_review/get_all_mtp_reviews_admin` for MTP reviews (the properly admin-namespaced `Api\User\Admin\Guide\MtpReitingController`). Both require the `comment › show` permission (reviews share the same moderation permission subject as comments).

**Published/hidden filtering**: only reviews with `published=1, admin_hidden=0` count toward a climber's public `route_reviews_count`/`mtp_reviews_count` and the weighted [points total](CLIMBER_PROFILE.md#the-points-system) shown on their profile — same filter the public listing endpoints apply.

**Data exposure fix (September 2026)**: the public per-route/per-MTP listing endpoints used to attach the *full* `User` row (email, phone number, etc.) to each review — trimmed to `id, name, surname, image` now, matching what the review modals actually render. The site-wide admin "all reviews" endpoints (which do legitimately need more, e.g. email, for moderation) were separately found to be reachable *without any login at all* — now require `auth:sanctum` + the `comment › show` permission. Full writeup, including every other bug found in the same audit: [SECURITY_AND_PITFALLS.md](BACKEND/SECURITY_AND_PITFALLS.md).

![Public vs admin comment/review data exposure](DEMO_IMAGES/Guidbook/Comment_public_vs_admin_exposure.svg)

### Local Businesses

Businesses (guesthouses, shops, tour operators) linked to climbing areas.

```
suport_local_bisneses (global)
├── locale_bisneses (locale: title, description)
├── suport_local_bisnes_images (gallery)
└── article_id (linked climbing spot)
```

Visibility controlled by `published_date` — only show if still within date range.

![Local business structure](DEMO_IMAGES/Guidbook/Local_business_structure.svg)

### Events

```
events (global)
└── locale_events (locale: title, description, dates)
```

Users can mark events as "interested" → stored in `interested_events`.

![Favorites diagram](DEMO_IMAGES/Guidbook/Favorites_and_events.svg)

### Favorite Outdoor Areas

Registered users can save favorite climbing areas:
```
favorite_outdoor_areas
├── user_id
└── article_id (outdoor spot)
```

---

## Admin Panel

Guide content is managed at `user.climbing.ge` under the **Guide** section.

| Admin Section | Manages |
|---|---|
| **Articles** | Create/edit/delete articles by category |
| **Sectors** | Add sectors to outdoor spots |
| **Routes** | Add climbing routes to sectors |
| **Multi-pitch** | Add MTP routes and pitches |
| **Regions** | Outdoor climbing regions |
| **Mount Massifs** | Mountain group definitions |
| **Events** | Competitions and events |
| **General Info** | Reusable info blocks |
| **Sliders** | Hero image sliders |
| **Local Businesses** | Partner businesses |
| **Team Members** | Staff profiles |
| **Live Cameras** | Webcam embeds |
| **Comments** | Moderate user comments |
| **Gallery** | Manage images |

---

[Go back](../README.md)
