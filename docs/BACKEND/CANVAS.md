# Canvas System — Backend Documentation

Drawing data for climbing routes, MTP pitches, and sector local images is stored as Paper.js JSON in the database. Composite images (background photo + drawn strokes baked together) are saved to the filesystem.

---

## Table of Contents

- [Overview](#overview)
- [Database Models](#database-models)
- [API Endpoints](#api-endpoints)
- [Save Logic — Sector Local Images](#save-logic--sector-local-images)
- [Save Logic — Climbing Routes](#save-logic--climbing-routes)
- [Save Logic — MTP Pitches](#save-logic--mtp-pitches)
- [Extra Drawing Tables](#extra-drawing-tables)
- [CanvasService — Cleanup on Delete](#canvasservice--cleanup-on-delete)
- [General Canvas Image Endpoint](#general-canvas-image-endpoint)
- [File Storage Paths](#file-storage-paths)
- [Permissions Required](#permissions-required)

---

## Overview

Four different entities can have canvas drawings:

| Entity | JSON Model | Image location |
|---|---|---|
| Sector image (shared background) | `ClimbingRoutesJson`, `MtpPitchJson` | `public/images/sector_img/` |
| Sector local image | `SectorLocalImagesJson` | `public/images/sector_local_img/` |
| Spot rocks image | `SpotRocksImageJson` | `public/images/spot_rocks_img/` |

A single sector (background) image can have **multiple route drawings** and **multiple pitch drawings** layered on top of it. Each is stored separately and displayed as reference when editing another.

A sector **local** image has one drawing per sector (the `(sector_local_image_id, sector_id)` pair is unique) — a **spot rocks** image works the same way, one drawing per sector (the `(spot_rocks_image_id, sector_id)` pair is unique).

Any of the three "shared image" entities above (sector image, sector local image, spot rocks image) can ALSO carry one **extra drawing** — a general annotation layer independent of any one route/sector/pitch drawn on it. See [Extra Drawing Tables](#extra-drawing-tables).

---

## Database Models

![Canvas JSON structure](../DEMO_IMAGES/Canvas/Canvas_json_structure.svg)

### `ClimbingRoutesJson`

**Table:** `climbing_routes_jsons`

| Column | Type | Description |
|---|---|---|
| `id` | PK | |
| `route_id` | FK → `routes` | The climbing route this drawing belongs to |
| `sector_image_id` | FK → `sector_images` | Background image |
| `json` | text | Paper.js JSON string |
| `canvas_width` | int | Canvas width at save time (px) |
| `canvas_height` | int | Canvas height at save time (px) |

### `MtpPitchJson`

**Table:** `mtp_pitch_jsons`

| Column | Type | Description |
|---|---|---|
| `id` | PK | |
| `mtp_pitch_id` | FK → `mtp_pitches` | The pitch this drawing belongs to |
| `sector_image_id` | FK → `sector_images` | Background image |
| `json` | text | Paper.js JSON string |

### `SectorLocalImagesJson`

**Table:** `sector_local_images_jsons`

| Column | Type | Description |
|---|---|---|
| `id` | PK | |
| `sector_local_image_id` | FK → `sector_local_images` | The local image |
| `sector_id` | FK → `sectors` | Which sector's route is drawn |
| `json` | text | Paper.js JSON string |
| `canvas_width` | int | Canvas width at save time |
| `canvas_height` | int | Canvas height at save time |

**Unique constraint:** `(sector_local_image_id, sector_id)` — one drawing per route per image.

---

## API Endpoints

### Sector Local Images Canvas

All under `POST /api/set_sector/set_sector_local_images/`

| Method | URI | Controller | Permission |
|---|---|---|---|
| POST | `save_canvas_data/{image_id}` | `SectorLocalImagesController@save_canvas_data` | `sector_local_image › edit` |
| GET | `get_layouts/{id}` | `SectorLocalImagesController@get_layouts` | `sector_local_image › show` |
| GET | `get_layout/{layout_id}` | `SectorLocalImagesController@get_layout` | `sector_local_image › show` |
| GET | `get_layouts_for_sector/{sector_local_image_id}` | `SectorLocalImagesController@get_layouts_for_sector` | `sector_local_image › show` |

### Route JSON (Climbing Routes Canvas)

Under `/api/set_route_json/`

| Method | URI | Controller | Permission |
|---|---|---|---|
| POST | `add_route_json` | `RouteJsonController@add_route_json` | `route › add` |
| POST | `edit_route_json/{route_id}` | `RouteJsonController@edit_route_json` | `route › edit` |
| DELETE | `del_route_json/{route_id}` | `RouteJsonController@del_route_json` | `route › edit` |

### MTP Pitch Canvas

Under `/api/set_mtp_pitch/`

| Method | URI | Controller | Permission |
|---|---|---|---|
| POST | `save_pitch_drawing/{pitch_id}` | `MTPPitchController@save_pitch_drawing` | `mtp_pitch › edit` |
| DELETE | `del_pitch_drawing/{pitch_id}` | `MTPPitchController@del_pitch_drawing` | `mtp_pitch › edit` |
| GET | `get_pitch_jsons_for_sector_image` | `MTPPitchController@get_pitch_jsons_for_sector_image` | `mtp_pitch › show` |

### Spot Rocks Images Canvas

Under `/api/set_sector/set_spot_rock_images/`

| Method | URI | Controller | Permission |
|---|---|---|---|
| GET | `get_for_editor/{id}` | `SpotRockController@get_for_editor` | `sector › show` |
| POST | `save_drawing/{image_id}` | `SpotRockController@save_drawing` | `sector › edit` |
| DELETE | `del_layout/{layout_id}` | `SpotRockController@del_layout` | `sector › edit` |
| DELETE | `del_spot_rock_image/{image_id}` | `SpotRockController@del_spot_rock_image` | `sector › del` |

Public: `GET /api/get_sector/get_spot_rock_images/get_spot_rock_images/{article_id}` — `SpotRockController@get_spot_rock_images` (`Api\Guide`), no auth.

See [Extra Drawing Tables](#extra-drawing-tables) for the three `*_extra_drawing` endpoint groups (one per shared-image entity above), also under `/api/set_sector/`.

### General Canvas Image Save

| Method | URI | Controller | Auth |
|---|---|---|---|
| POST | `/api/save-canvas-image` | `CanvasController@saveImage` | None |

---

## Save Logic — Sector Local Images

**Controller:** `App\Http\Controllers\Api\User\Admin\Guide\SectorLocalImagesController@save_canvas_data`

**Request payload:**

```json
{
    "canvasData":    "<Paper.js JSON>",
    "sectorId":      123,
    "edited_image":  "data:image/jpeg;base64,...",
    "canvas_width":  800,
    "canvas_height": 600
}
```

**What it does:**

1. Validates `sectorId` is present.
2. Looks up the `sector_local_image` record to get the filename.
3. If `edited_image` is provided:
   - Creates `origin_img/` subdirectory if missing.
   - **Backs up the clean original** once to `images/sector_local_img/origin_img/{filename}`. Never overwrites the backup.
   - Strips the `data:image/...;base64,` prefix and writes the decoded bytes over the main file at `images/sector_local_img/{filename}`.
4. Upserts `SectorLocalImagesJson` on `(sector_local_image_id, sector_id)` with the JSON and dimensions.
5. Returns `layout_id` and `has_original` flag.

**Image backup rule:** The first time canvas data is saved for an image, the untouched photo is backed up. Subsequent saves overwrite the main file with the composite (photo + strokes). The backup is never overwritten so the original can always be restored.

---

## Save Logic — Spot Rocks Images

**Controller:** `App\Http\Controllers\Api\User\Admin\Guide\SpotRockController@save_drawing`

Identical shape and backup rule to Sector Local Images above — `sector_id` required, backs up the clean original once to `images/spot_rocks_img/origin_img/{filename}`, overwrites the main file with the composite, upserts `SpotRocksImageJson` on `(spot_rocks_image_id, sector_id)`, returns `layout_id` and `has_original`.

---

## Save Logic — Climbing Routes

**Controller:** `App\Http\Controllers\Api\User\Admin\Guide\RouteJsonController`

`edit_route_json` is a static helper called from `RouteController` during route add/edit. It does an upsert by `route_id`:

- If no existing record → calls `add_route_json` (inserts).
- If record exists → validates and updates.

**Fields stored:** `route_id`, `sector_image_id`, `json`.

**Note:** `add_route_json` validates `route_id` is unique in `climbing_routes_jsons`. The `edit_route_json` validator does NOT check uniqueness (to allow updates).

---

## Save Logic — MTP Pitches

**Controller:** `App\Http\Controllers\Api\User\Admin\Guide\MTPPitchController@save_pitch_drawing`

1. Looks up the `mtp_pitch` record.
2. Saves the composite image to `images/sector_img/` (same path as the original sector image, overwriting with strokes).
3. Upserts `MtpPitchJson` on `mtp_pitch_id`: updates if exists, creates if not.

The `get_pitch_jsons_for_sector_image` endpoint returns JSON strings for all pitches on a given sector image EXCEPT the one currently being edited (used to show other pitches as reference layers in the editor). Each item also carries `pitch_name` (the sibling pitch's own `name`) so the frontend can label its reference overlay without a separate lookup — see `docs/FRONTEND/COMPONENTS/CANVAS_EDITOR.md`'s `canvasOverlaysMixin.js` section. `RouteJsonController@get_related_routes_jsons` carries the equivalent `route_name` field for the same reason.

---

## Extra Drawing Tables

A toggleable general-annotation layer, separate from any one route/pitch/sector drawing — for approach notes, hazards, or landmarks that aren't about any one item on the shared photo. Three separate tables/controllers by design (each tied to a different "shared image" entity), unified only on the frontend via `canvasExtraDrawingMixin.js` (see `docs/FRONTEND/COMPONENTS/CANVAS_EDITOR.md`).

| Entity | Table | Model | Keyed by | Admin controller | Public controller |
|---|---|---|---|---|---|
| Sector image (routes/pitches) | `sector_image_extra_drawings` | `SectorImageExtraDrawing` | `sector_image_id` (unique) | `Api\User\Admin\Guide\SectorImageExtraDrawingController` | `Api\Guide\SectorImageExtraDrawingController` |
| Sector local image | `sector_local_image_extra_drawings` | `SectorLocalImageExtraDrawing` | `sector_local_image_id` (unique) | `Api\User\Admin\Guide\SectorLocalImageExtraDrawingController` | `Api\Guide\SectorLocalImageExtraDrawingController` |
| Spot rocks image | `spot_rocks_image_extra_drawings` | `SpotRocksImageExtraDrawing` | `spot_rocks_image_id` (unique) | `Api\User\Admin\Guide\SpotRocksImageExtraDrawingController` | `Api\Guide\SpotRocksImageExtraDrawingController` |

Every table has the same shape: `id`, `json`, `canvas_width`, `canvas_height`, `bg_left`/`bg_top`/`bg_width`/`bg_height`, the one FK column (unique, cascade-deletes with its parent image), `timestamps`.

**Admin endpoints** (all three follow this exact pattern, permission subject `sector`/`sector_local_image` show+edit matching each entity's own regular canvas endpoints):

| Method | URI pattern | Notes |
|---|---|---|
| GET | `/api/set_sector/set_<entity>_extra_drawing/get_for_editor/{id}` | Returns the parent image (with `has_original`) + the extra drawing row (or `null`) |
| POST | `/api/set_sector/set_<entity>_extra_drawing/save/{id}` | Same composite-image backup/overwrite pattern as the entity's own regular save (backs up `origin_img/` once, overwrites the live file) |
| DELETE | `/api/set_sector/set_<entity>_extra_drawing/delete/{id}` | Deletes the row; does not touch the image file |

**Public endpoint** (read-only, no auth): `GET /api/get_sector/get_<entity>_extra_drawing/get/{id}` → `{ extra_drawing }`.

`SectorImageExtraDrawing` is shared by routes AND pitches (both are drawn on the same kind of `sector_images` row) — `CanvasRouteEditorComponent.vue`, `sectorRouteDrawingsEditorComponent.vue` (route mode), and `CanvasPitchEditorComponent.vue` all point at the same three endpoints, keyed only by `sector_image_id`.

---

## CanvasService — Cleanup on Delete

**File:** `app/Services/CanvasService.php`

**Always call these before deleting an image record** to prevent orphaned JSON rows.

```php
use App\Services\CanvasService;

// Before deleting a sector_images row:
CanvasService::deleteSectorImageCanvasData($sectorImageId);
// → deletes from climbing_routes_jsons WHERE sector_image_id = $sectorImageId
// → deletes from mtp_pitch_jsons WHERE sector_image_id = $sectorImageId

// Before deleting a sector_local_images row:
CanvasService::deleteSectorLocalImageCanvasData($sectorLocalImageId);
// → deletes from sector_local_images_jsons WHERE sector_local_image_id = $sectorLocalImageId
// → deletes from sector_local_image_extra_drawings WHERE sector_local_image_id = $sectorLocalImageId

// Before deleting a spot_rocks_images row:
CanvasService::deleteSpotRocksImageCanvasData($spotRocksImageId);
// → deletes from spot_rocks_image_jsons WHERE spot_rocks_image_id = $spotRocksImageId
// → deletes from spot_rocks_image_extra_drawings WHERE spot_rocks_image_id = $spotRocksImageId
```

`deleteSectorImageCanvasData` (called before deleting a `sector_images` row) already also deletes from `sector_image_extra_drawings WHERE sector_image_id = $sectorImageId`.

---

## General Canvas Image Endpoint

`POST /api/save-canvas-image` — `CanvasController@saveImage`

- **Auth:** None required (public).
- **Purpose:** General-purpose PNG save, not tied to any specific entity.
- **Input:** `image` (file upload, PNG/JPG/JPEG/GIF, max 10MB).
- **Output:** `{ success, filename, path }` — path is under `storage/images/`.
- **Used by:** Any canvas component that needs to upload a raw image without entity context.

---

## File Storage Paths

| Context | Path |
|---|---|
| Sector images (shared background) | `public/images/sector_img/` |
| Sector local images | `public/images/sector_local_img/` |
| Sector local images — originals backup | `public/images/sector_local_img/origin_img/` |
| Spot rocks images | `public/images/spot_rocks_img/` |
| Spot rocks images — originals backup | `public/images/spot_rocks_img/origin_img/` |
| Product option combination images | `public/images/product_option_img/` |
| General canvas saves | `storage/images/` (via Laravel Storage) |

---

## Permissions Required

| Subject | Action | Used For |
|---|---|---|
| `sector_local_image` | `show` | Load layouts / JSON |
| `sector_local_image` | `edit` | Save canvas data |
| `route` | `show` | Load route JSON |
| `route` | `add` | Create route JSON record |
| `route` | `edit` | Update / delete route JSON |
| `mtp_pitch` | `show` | Load pitch JSON |
| `mtp_pitch` | `edit` | Save / delete pitch drawing |
| `sector` | `show` | Load spot rocks image drawing / extra drawing; load sector-image extra drawing |
| `sector` | `edit` | Save / delete spot rocks image drawing / extra drawing; save/delete sector-image extra drawing |
| `sector_local_image` | `show` / `edit` | Load / save-delete sector-local-image extra drawing (same subject as its regular canvas endpoints) |

---

[Back to Backend docs](API.md)
