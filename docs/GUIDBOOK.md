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

All of the above fetch their record in `mounted()` and gate their whole content span — `<metaData>` included — behind `article_loading` (`true` initially, `v-else-if="!article_loading"`), **except `EventPageComponent.vue`**, which used to initialize `article_loading: false` and left `<metaData>` ungated. Since its `event` object starts as `[]`, the very first render evaluated `event.locale_event.title` (`[].locale_event` → `undefined`, `.title` on that throws) before the fetch even started — a real render-crashing bug on every event-page load, not just an analytics artifact. Fixed to match the other five; if you add another page here, copy one of those five, not the old `EventPageComponent.vue` pattern.

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
| GET | `/api/get_mount/get_locale_mounts/{lang}` | Public mount massifs (`published = 1`), for the mountaineering and summit filters |
| GET | `/api/get_mount/{lang}/{mount_id}` / `/api/get_mount/on_page/{lang}/{mount_route_id}` | One mount massif, only when `published` is 1 or 2 (public by URL) |
| GET | `/api/get_event/get_event_on_site_list/{lang}` | Events list |
| GET | `/api/get_region/get_all_outdoor_regions` | All outdoor regions |
| GET | `/api/get_article/last_news/{lang}` | Latest news |

**Mount massif visibility:** `mounts.published` (0 not public, 1 public, 2 public by URL), set in the admin mount massif form (first field) or by bulk publish. Lists only show 1. A mountaineering route whose massif isn't public still appears in the "other routes" group. `get_mount/get_all_mount` is unfiltered because it feeds the admin article forms.

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

**Created / updated dates on the article page** (`ArticlesService::get_locale_article_in_page`, used by every `get_locale_article_on_page` call): "Created" is the general `articles.created_at` (falls back to the earliest `us`/`ka` `locale_articles.created_at` when the general row has none). "Updated" is the **latest** `updated_at` across the general row and both locale rows — editing only the Georgian text still moves the date. The values are overridden on the returned model only, never saved.

**Indoor gyms** (`category = 'indoor'`):
- `open_time` / `closed_time` (`time`) — working hours shown on `IndoorCardComponent.vue` with a live open/closed hint. `Article::$fillable` used to list `open_timen` (typo), so **editing** a gym never saved `open_time` — fixed October 2026.
- `work_status` (`string`, nullable) — overrides the hours: `temporarily_closed`, `sport_session_closed`, `coming_soon`, `no_info` (`null` = normal hours). Choosing any status clears `open_time`/`closed_time` — in the admin form (inputs disabled) and server-side in `Admin\Guide\ArticleController::normalize_work_status()` (which, like `normalize_mount_grade()`, now also reaches `edit_content()` via `$request->merge(['data' => ...])` — before that, edit-time normalization was silently ignored). Set in the admin form's working-time block. Shown as a colored alert (`guide/items/IndoorWorkStatusAlert.vue`: red / orange / blue, icon, title `guide.article.work_status.*` + explanation `guide.article.work_status_text.*`) on the indoor card and at the top of the gym page; `no_info` gets no alert, only the "No information about working time" line. The card (`IndoorCardComponent.vue`) also shows a colored ribbon on the photo for the three alert statuses, price and hours with icons (seconds trimmed; the hours row is hidden while one of the three alert statuses is set), the whole card is clickable (no separate button), and a live badge — Open now / Closes in N min / Opens in N min / Closed now — computed in minutes (handles closing after midnight), refreshed every minute. Migration `2026_10_18_110000_add_work_status_to_articles_table.php`.
- **Region** — same `article_region` relation outdoor uses (`Article::outdoor_region()`), selected in the add/edit global form and saved by `Admin\Guide\ArticleController::description_img()` for both `outdoor` and `indoor`. The public indoor list (`IndoorListComponent.vue`) gets each gym's region ids (`article_list()` eager-loads `outdoor_region` for `indoor` only) and filters client-side via a region select that lists only regions with at least one gym (`?region=<id>` in the URL).
- **Indoor list filters/sorting** (`IndoorListComponent.vue`, all client-side, kept in the URL query): region (`?region=<id>`, shows a `RegionListHeaderComponent` with the region text), status (`?status=open_now|working` — open right now / hide `temporarily_closed`, `sport_session_closed`, `coming_soon`), sort (`?sort=name|open_now|price`, default newest = API order; closed/coming-soon gyms sort after working ones), and, while all regions are shown, grouping by region (`?group=flat` to turn off; gyms without a region go into an "Other" group). The open-now rule is duplicated from `IndoorCardComponent.vue` — keep them in sync.
- **Coming soon gyms have no page:** the card isn't a link (clicking shows a friendly "page will be available once it opens" notice), and `IndoorPageComponent.vue` shows a placeholder with a link back to the list instead of the article if opened directly.
- **Admin tables** (`user/pages/articles/articleListComponent.vue`): outdoor, indoor and ice get a "Region" text column (`prepare_article_rows()` flattens `outdoor_region` into `region_name`, since data-table cells can't read relations), indoor also a "Working status" column. `Api\Guide\ArticleController::get_category_articles()` eager-loads `outdoor_region:id,us_name` for these three categories. The ice page also gets the Regions management tab (tab id 4) and the region filter on its articles tab, like outdoor.
- **Admin region filter** (`Admin\Guide\OutdoorController::get_filtred_outdoor_spots_for_admin`, `?category=`) only returns articles of the table's category — regions are shared by outdoor, indoor and ice, so without it the outdoor table filtered by region also listed gyms and ice spots.

**Ice articles** have a region too (same `article_region` relation): region select in the add/edit global form, saved/loaded by `Admin\Guide\ArticleController` together with `outdoor` and `indoor`. The admin "ice sectors by region" filter (`SectorController::get_filtred_ice_sectors_for_admin`) already relied on this relation but had no way to set it before.
The public ice list (`IceListComponent.vue`) filters by region client-side like the indoor list: `article_list()` eager-loads `outdoor_region` for `indoor` and `ice`; region select (only regions with at least one spot, `?region=<id>`), region header, and while all regions are shown a By region / Flat toggle (`?group=flat`, spots without a region in "Other"); grid/list view is in the URL too (`?view=list`). Migration `2026_10_19_130000_link_ice_articles_to_regions.php` linked the existing ice spots.

The add form used to keep `region_id` outside the emitted `data` object, so **creating** an outdoor article never saved its region. It now lives in `data`; `LocaleContentControllService::addGlobalArticle()` filters its raw `insertGetId()` to real table columns so form-only keys like `region_id` can't break the insert. (`mount_id` on the add form still has the old problem.)

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

**Route quantity block** (`GET get_route/get_routes_quantity/{article_id}` → `SportClimbingRoutesService::get_routes_quantity`) counts only **published** sectors. Routes, MTPs and pitches have no publish flag of their own, so they're hidden with their sector — same rule as `get_sector_and_routes` and the outdoor list badges (`ArticlesService::get_route_quantity_for_outdoors(..., true)`).

**Sector order** (`sectors.num`) is edited in the admin "Spot Sectors" modal (`ArticleSectorSequenceModalComponent.vue`, opened from the article list). `GET get_sector/get_spot_sectors_data_for_model/{article_id}` returns `{ groups: [{ image, sectors }], ungrouped: [] }` — one group per sector local image, in the same order and membership the public page uses (`SectorController::get_sector_and_routes`: a group's position is decided by its first sector by `num`). Sectors are dragged inside their own group; whole groups move with the up/down buttons; saving flattens all groups (then ungrouped sectors) into `POST set_sector/save_sector_sequence`, which renumbers `num` 1..n. `Sector_local_image::sectors()` / `allSectors()` are ordered by `num` — before October 2026 they weren't, so grouped sectors on the public page ignored the saved order.

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

### MTP Ascents

Climbers record their multi-pitch ascents — the guide-side counterpart of [summit ascents](SUMMIT.md). Unlike summits there is no GPS check (MTPs have no coordinates), so every ascent is self-reported with a date the climber picks (like a summit **archive** ascent). Login is required, same as MTP reviews.

```
mtp_ascents
├── mtp_id   → mtps (cascade)
├── user_id  → users (nullable, SET NULL — deleted with the account unless the user keeps ascents, see AUTH.md)
├── ascent_date (required, ≤ today), ascent_time (optional)
├── ascent_style  lead | second | alternate (MtpAscent::STYLES, optional)
└── comment, photo (images/mtp_ascents_img/)
```

| Method | Path | Auth | Controller |
|---|---|---|---|
| GET | `get_mtp_ascent/get_mtp_ascents/{mtp_id}` | public | `Api\Guide\MtpAscentController` — user restricted to `id, name, surname, image` |
| POST | `set_mtp_ascent/create_mtp_ascent/{mtp_id}` | `auth:sanctum` + reCAPTCHA (if configured) | `Api\User\Admin\Guide\MtpAscentController` (multipart) |
| GET | `set_mtp_ascent/get_my_mtp_ascents` | `auth:sanctum` | own ascents |
| DELETE | `set_mtp_ascent/del_my_mtp_ascent/{id}` | `auth:sanctum` | own ascent only |
| GET | `set_mtp_ascent/get_all_mtp_ascents_admin` | `mtp › show` | all ascents |
| DELETE | `set_mtp_ascent/del_mtp_ascent_admin/{id}` | `mtp › del` | any ascent |

**Frontend:** the MTP modal (`MTPModalComponent.vue`) shows "Record ascent" and "Ascents (N)" (`ascents_count` from `get_mtp_for_modal`); both open modals in `climbing_routes/items/modals/ascents/` wired through `SectorComponent.vue`, the same way the review modals are. User page `/my_mtp_ascents` (`MyMtpAscentsListPage.vue`, left menu → Favorites) and admin page `/mtp_ascents` (`MtpAscentsAdminPage.vue`, left menu → Guide). MTP ascents don't count toward [climber points](CLIMBER_PROFILE.md#the-points-system) yet.

### Route Ascents (logbook)

Climbers log ticks on sport / bouldering routes (`routes` table) — the single-pitch counterpart of [MTP ascents](#mtp-ascents), same login-only + reCAPTCHA flow and self-reported date.

```
route_ascents
├── route_id  → routes (cascade)
├── user_id   → users (nullable, SET NULL — deleted with the account unless the user keeps ascents, see AUTH.md)
├── ascent_date (required, ≤ today)
├── ascent_style  onsight | flash | redpoint | toprope (RouteAscent::STYLES, required)
├── attempts (optional; forced to 1 server-side for onsight/flash)
├── suggested_grade (optional "grade opinion"; empty = agrees with the topo grade)
└── comment, photo (images/route_ascents_img/)
```

| Method | Path | Auth | Controller |
|---|---|---|---|
| GET | `get_route_ascent/get_route_ascents/{route_id}` | public | `Api\Guide\RouteAscentController` — user restricted to `id, name, surname, image` |
| POST | `set_route_ascent/create_route_ascent/{route_id}` | `auth:sanctum` + reCAPTCHA (if configured) | `Api\User\Admin\Guide\RouteAscentController` (multipart) |
| GET | `set_route_ascent/get_my_route_ascents` | `auth:sanctum` | own ascents (+ `route_name`, `route_grade`, `sector_name`) |
| DELETE | `set_route_ascent/del_my_route_ascent/{id}` | `auth:sanctum` | own ascent only |
| GET | `set_route_ascent/get_all_route_ascents_admin` | `route › show` | all ascents |
| DELETE | `set_route_ascent/del_route_ascent_admin/{id}` | `route › del` | any ascent |

**Frontend:** the route Info modal (`RouteModalComponent.vue`) shows "Log ascent" and "Ascents (N)" (`ascents_count` from `get_route_for_modal`), opening `CreateRouteAscentModal.vue` / `RouteAscentsListModal.vue` (in `climbing_routes/items/modals/ascents/`) through `SectorComponent.vue`. The create modal hides Onsight for boulders and offers grade opinions in the route's own scale (French for sport, V-scale for boulders). The list modal shows per-style counts and the most-suggested grade. User page `/my_route_ascents` (`MyRouteAscentsListPage.vue`, left menu → Favorites) is the climber's logbook with total / hardest send / hardest boulder (toprope excluded); admin page `/route_ascents` (`RouteAscentsAdminPage.vue`, left menu → Guide). Route ascents don't count toward [climber points](CLIMBER_PROFILE.md#the-points-system) yet.

### Local Businesses

Businesses (guesthouses, shops, tour operators) linked to climbing areas. An article can be related to at most **4** businesses (`Admin\Guide\LocalBisnesController::MAX_RELATIONS_PER_ARTICLE`, returned to the admin relation tabs as `max_relations`; the slot columns follow it). On the article page they're shown by `LocaleBisnesListComponent.vue` as cards in a slider.

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
| **Route ascents** | All logged sport/boulder ascents (`/route_ascents`, `route › show`; delete needs `route › del`) |
| **Multi-pitch ascents** | All recorded MTP ascents (`/mtp_ascents`, `mtp › show`; delete needs `mtp › del`) |
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
