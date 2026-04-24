# Summit Ascent Tracker — summit.climbing.ge

QR-code-based system for recording and sharing mountain summit ascents. Climbers reach a summit, scan the QR code installed there, fill in their details, and their ascent is permanently logged.

---

## Table of Contents

- [Overview](#overview)
- [Frontend Pages](#frontend-pages)
- [Backend API — Public](#backend-api--public)
- [Backend API — Admin](#backend-api--admin)
- [Database Models](#database-models)
- [Admin Panel](#admin-panel)
- [QR Code System](#qr-code-system)
- [Ascent Flow](#ascent-flow)

---

## Overview

**Subdomain:** `summit.climbing.ge`  
**Root Component:** `resources/js/components/summit/SummitMainComponent.vue`  
**Router:** `resources/js/routes/SummitRouter.js`  
**API base URL:** `/api/summit/`

### Page Routes

| Path | Component | Description |
|---|---|---|
| `/` | `IndexPage.vue` | Home — scan QR or browse summits |
| `/about_us` | `AboutUsPage.vue` | About the platform |
| `/summits/list` | `lists/SummitListPage.vue` | All published summits |
| `/summit/:url_title` | `pages/SummitPage.vue` | Summit detail + ascent list |
| `/summits/map` | `SummitMapPage.vue` | Summit map |
| `/make_ascent/:id` | `MakeSummitAscentPage.vue` | Submit ascent form |

All pages follow the **guide-style layout**: `div.h-recent-work > div.container` with `h1.index_h2`, `div.bar > i.fa`, and `h3.article_list_short_description` section headers.

---

## Frontend Pages

### `IndexPage.vue` — Home / QR Scanner

**`resources/js/components/summit/pages/IndexPage.vue`**

Landing page. On **mobile**: shows a Scan QR button. On **desktop**: shows a message with a Browse Summits link.

**Computed:**
```javascript
isMobile  // detects by user-agent, screen width < 768, or touch events
```

**Methods:**
```javascript
scanQR()                    // activates Html5QrcodeScanner overlay
initScanner()               // configures and renders the scanner
onScanSuccess(decodedText)  // extracts path from URL, calls $router.push()
onScanError()               // silently ignores "no QR in frame" errors
closeScanner()              // clears scanner instance, hides overlay
```

**Dependencies:** `html5-qrcode` npm package

---

### `lists/SummitListPage.vue` — Public Summit List

**`resources/js/components/summit/pages/lists/SummitListPage.vue`**

All published summits displayed as guide-style cards (`.food.col-md-4 > .portfolio-img.view.view-first`). Each card contains an inline SVG mountain illustration unique per summit.

**API:** `GET /api/summit/list`

**Card features:**
- Night-sky SVG mountain with crescent moon and snow cap
- Gradient ID keyed by `summit.id` to avoid SVG conflicts
- Height rendered as SVG text
- Hover overlay → link to summit detail
- "Details" → `/summit/:url_title`
- "Ascent" → `/make_ascent/:id`

---

### `pages/SummitPage.vue` — Summit Detail

**`resources/js/components/summit/pages/pages/SummitPage.vue`**

Full summit detail page with info panel, ascent table, and QR sidebar.

**API calls:**
- `GET /api/summit/show/:url_title` — summit data (with region)
- `GET /api/summit/ascents/:url_title` — public ascent list

**Data displayed:**
- Title, Georgian title, height badge, region, coordinates
- Description text
- Record My Ascent CTA button
- Ascent table: climber name, date, route (clickable external link to guide), GPS badge, expandable comment

**Methods:**
```javascript
fetchSummit()               // loads summit, then triggers fetchAscents()
fetchAscents()              // loads public ascents for this summit
formatDate(d)               // formats to 'en-GB' locale string
guideRouteUrl(articleUrl)   // builds: {base}/outdoor/{articleUrl}
toggleComment(id)           // expands/collapses comment row in table
```

**QR sidebar:** `qrcode-vue` component displaying `ascentUrl`:
```javascript
ascentUrl  // computed: MIX_APP_SSH + MIX_SUMMIT_URL + /make_ascent/:id
```

---

### `MakeSummitAscentPage.vue` — Submit Ascent

**`resources/js/components/summit/pages/MakeSummitAscentPage.vue`**

Form loaded directly from QR scan redirect or "Record Ascent" button.

**API:** `POST /api/summit/ascent/:summit_id`

**Form fields:** name, surname, email, ascent date, route selection, other route (free text), comment, photo, GPS (auto-captured via browser geolocation API)

---

### `AboutUsPage.vue`

Static informational page. Three feature blocks: Explore Summits, QR Registration, Climber Community.

### `SummitMapPage.vue`

Placeholder page — map implementation pending.

---

## Backend API — Public

**File:** `routes/api/summit_public_routes.php`  
**Controller:** `App\Http\Controllers\Api\Summit\SummitPublicController`  
**Prefix:** `/api/summit`

| Method | Path | Description |
|---|---|---|
| GET | `/list` | All published summits |
| GET | `/show/{url_title}` | Single summit with region |
| GET | `/routes/{id}` | Available routes for a summit |
| GET | `/ascents/{url_title}` | Public ascent list (no email) |
| POST | `/ascent/{summit_id}` | Submit a new ascent |

### Response: `GET /api/summit/list`

```json
[
  {
    "id": 1,
    "title": "Mount Kazbek",
    "ka_title": "ყაზბეგი",
    "url_title": "mount-kazbek",
    "description": "The highest mountain in Georgia...",
    "height": 5047,
    "latitude": "42.6993",
    "longitude": "44.5183",
    "region": { "id": 1, "us_name": "Kazbegi" },
    "mount_route_id": 5,
    "qr_code": "https://summit.climbing.ge/make_ascent/1",
    "published": true
  }
]
```

### Response: `GET /api/summit/ascents/{url_title}`

```json
{
  "ascents": [
    {
      "id": 12,
      "name": "John",
      "surname": "Doe",
      "comment": "Amazing sunrise!",
      "photo": null,
      "is_gps_validated": true,
      "ascent_date": "2024-06-15",
      "route_name": "North Face Normal Route",
      "route_grade": "4A",
      "route_article_url": "mount-kazbek-routes"
    }
  ]
}
```

Note: `email` is intentionally omitted from the public response.

### Request: `POST /api/summit/ascent/{summit_id}`

```json
{
  "name": "John",
  "surname": "Doe",
  "email": "john@example.com",
  "ascent_date": "2024-06-15",
  "comment": "Great climb!",
  "is_gps_validated": false,
  "latitude": 42.6993,
  "longitude": 44.5183
}
```

---

## Backend API — Admin

**File:** `routes/api/admin/set_summit_routes.php`  
**Controller:** `App\Http\Controllers\Api\User\Admin\Summit\SummitController`  
**Middleware:** `auth:sanctum`, `banned`

| Method | Path | Controller Method | Description |
|---|---|---|---|
| GET | `/api/get_summit_admin/index` | `index` | All summits (full admin data) |
| GET | `/api/get_summit_admin/get_regions` | `get_regions` | Regions for dropdown |
| GET | `/api/get_summit_admin/get_mount_routes` | `get_mount_routes` | Mount routes for dropdown |
| GET | `/api/get_summit_admin/get_ascents/{id}` | `get_ascents` | Ascents for one summit |
| GET | `/api/get_summit_admin/get_all_ascents` | `get_all_ascents` | All ascents with full data |
| POST | `/api/set_summit/store` | `store` | Create summit |
| POST | `/api/set_summit/update/{id}` | `update` | Update summit |
| DELETE | `/api/set_summit/destroy/{id}` | `destroy` | Delete summit |
| POST | `/api/set_summit/save_qr/{id}` | `save_qr` | Save QR URL to DB |

### Store / Update Validation

```
title          required|string|max:255
ka_title       nullable|string|max:255
description    nullable|string
ka_description nullable|string
height         nullable|integer|min:0
latitude       nullable|numeric|between:-90,90
longitude      nullable|numeric|between:-180,180
mount_route_id nullable|integer|exists:articles,id
published      nullable|boolean
```

`url_title` is auto-generated: `Str::slug(title)` with `-1`, `-2` suffix for uniqueness.  
On update, `url_title` regenerates only if `title` changed.

---

## Database Models

### `Summit` (`app/Models/Summit/Summit.php`) — table: `summits`

| Column | Type | Notes |
|---|---|---|
| `id` | int | PK |
| `title` | string | English title |
| `ka_title` | string\|null | Georgian title |
| `url_title` | string | Unique URL slug |
| `description` | text\|null | English description |
| `ka_description` | text\|null | Georgian description |
| `height` | int\|null | Metres |
| `latitude` | decimal\|null | GPS |
| `longitude` | decimal\|null | GPS |
| `region_id` | int\|null | → `regions` |
| `mount_route_id` | int\|null | → `articles` |
| `qr_code` | string\|null | Full QR URL |
| `published` | boolean | Visibility |
| `image` | string\|null | Image path |

**Relationships:** `region()` `BelongsTo(Region)`, `mountRoute()` `BelongsTo(Article)`, `ascents()` `HasMany(SummitAscent)`

---

### `SummitAscent` (`app/Models/Summit/SummitAscent.php`) — table: `summit_ascents`

| Column | Type | Notes |
|---|---|---|
| `id` | int | PK |
| `summit_id` | int | → `summits` |
| `name` | string | Climber first name |
| `surname` | string | Climber last name |
| `email` | string\|null | Contact email |
| `comment` | text\|null | Notes |
| `photo` | string\|null | Photo path |
| `is_gps_validated` | boolean | GPS verified |
| `ascent_date` | date | Date of ascent |
| `other_route` | string\|null | Free-text route |

**Relationships:** `summit()` `BelongsTo(Summit)`, `users()` `HasMany(SummitAscentUser)`, `ascentRoutes()` `HasMany(SummitAscentRoute)`

---

### `SummitAscentRoute` — route taken during the ascent

Links an ascent to a guide climbing `Route`, or stores a free-text name.

**Route → guide page URL chain:**  
`Route → Sector → Article (url_title)` → `/outdoor/{article.url_title}`

---

## Admin Panel

**`resources/js/components/user/pages/summits/SummitListPage.vue`**

Uses the standard admin `tabsComponent` pattern (same structure as `articleListComponent.vue`).

### Tab 1: Summits

Columns: ID, Title, KA Title, Height, Mount Route, QR ✓/✗, Published ✓/✗

Action buttons:
- **QR Code** — opens QR preview modal + Save QR button
- **Edit** — opens `SummitFormModal` pre-filled
- **Delete** — opens delete confirmation modal

The "Add Summit" button (via `tabsComponent` add_action) opens `SummitFormModal` in create mode.

### Tab 2: Ascents

Columns: ID, Name, Surname, Email, Summit, Date, Route, Grade, GPS ✓/✗, Comment

Both tabs load simultaneously on mount with `Promise.all`.

### `SummitFormModal.vue`

Add/Edit modal. Fields: Title, KA Title, Description (Quill editor), KA Description, Height, Latitude, Longitude, Region (dropdown via `get_regions`), Mount Route (dropdown via `get_mount_routes`), Published toggle.

---

## QR Code System

QR code URL format: `https://summit.climbing.ge/make_ascent/{summit_id}`

Built from env vars:
```javascript
const base = process.env.MIX_APP_SSH.replace(/\/$/, '')
           + '/' + process.env.MIX_SUMMIT_URL.replace(/^\/|\/$/g, '')
const qr_value = `${base}/make_ascent/${summit.id}`
```

**Saving QR to database:**
1. Admin opens QR modal → URL displayed + `qrcode-vue` preview
2. Admin clicks **Save QR** → `POST /api/set_summit/save_qr/:id` with `{ qr_code: url }`
3. URL persisted to `summits.qr_code`
4. Badge on summit card/list changes from "None" to "Saved"

**QR scanning (mobile):**
1. `Html5QrcodeScanner` opens device camera
2. QR decoded → URL string
3. `decodedText.replace(window.location.origin, '')` extracts path
4. `$router.push(path)` → `/make_ascent/:id`

---

## Ascent Flow

```
Climber reaches summit → scans QR code
         ↓
/make_ascent/:id loaded in browser
         ↓
Form: name, surname, email, date, route, comment, photo
GPS coordinates optionally captured from browser
         ↓
POST /api/summit/ascent/:id
         ↓
SummitAscent record created
SummitAscentRoute record created (if route selected)
Email matched against registered users → SummitAscentUser
         ↓
Ascent visible on public SummitPage and admin Ascents tab
```

---

[Go back](../README.md)
