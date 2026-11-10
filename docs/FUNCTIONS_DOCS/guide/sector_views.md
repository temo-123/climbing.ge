# Outdoor / ice area: sector views (Full / Tiles / Table / Tabs)

Four ways to show the sectors of an outdoor or ice area, picked with one switch above the sectors. Entry: `resources/js/components/guide/items/climbing_routes/SectorAndRoutesTabComponent.vue`.

## Why

A big area (Sveri: 5 sectors, 60 routes) is one very long page in the classic layout. Climbers look for different things: "what's in this area" (overview), "which 6b's are here" (all routes at once), or "just this one sector". A one-line "compact" summary was tried first and dropped (November 2026) — it was neither an overview nor a route list. The three extra views each answer one of those questions; the classic layout stays the default.

## Switch

- **Sectors: Full | Tiles | Table | Tabs** — `items/ViewSegmentSwitchComponent.vue`, the same segmented switch as the article Text / Cards switch ([article_view_switch.md](article_view_switch.md)). Icons: `fa-th-list`, `fa-th-large`, `fa-table`, `fa-folder-o`. On phones (< 576 px) icons only, full width.
- Shown only when the area has **2+ sectors**; with one sector the page is always Full.
- State: `services/guide/sectorView.js` → `sector_view.mode`, per device in `localStorage cg_sector_view` (try/catch). Unknown / old values (e.g. `compact`) fall back to `full`.
- Sits under the area route finder (`AreaRouteToolbarComponent`) and above the area photos. The non-Full views `clear: both` (`.cg-sector-view`) because the area photos are floated.

## The views

| View | Component | What it shows | Click |
|---|---|---|---|
| **Full** (default) | `items/SectorComponent.vue` per sector (unchanged) | every sector: icons, walking time, text, photos, route tables, multi-pitches | a route row opens the route modal |
| **Tiles** | `views/SectorTilesComponent.vue` | grid of tiles (auto-fill ≥ 210 px; 2 per row on phones): photo (first sector photo, else its local-approach photo), number badge, name, routes / multi-pitch count, grade range, grade-mix bar, walking time, filter matches | opens the normal `SectorComponent` in a `StackModal` (`xxxl`) with ‹ › to the neighbouring sectors and "2 / 5" |
| **Table** | `views/SectorRoutesTableComponent.vue` | every sport route, boulder and multi-pitch in one table: N, Name (+ "Bouldering" / "Multi-pitch" badge, ✓ when ticked), Sector, Grade, Height, ★; "All sectors" select; "n of N routes" | a row opens the route / multi-pitch modal (`items/RouteModalsComponent.vue`) |
| **Tabs** | `views/SectorTabsComponent.vue` | scrollable row of tabs (name, route count, grade-mix bar); only the chosen sector, with its local-approach photo when it has one (`SectorsAndAreaLocalImageComponrnt` fed only that sector) | tab selects; ‹ › at the bottom step and bring the tab row back into view |

### Table rules

- Default sort: Sector (page order), then N. Click a header to sort; again to reverse. ★ sorts high → low first. Grade sorts by the French rank (`grade_rank`); empty values are always last.
- Multi-pitch row: grade = hardest pitch, height = sum of pitch heights, stars from its reviews.
- Phones: N and ★ columns hidden.

### Shared facts (`services/guide/sectorSummary.js`)

- `sector_list(climbing_area)` → `[{ entry, group, n }]` in page order (`group` = its local-image group).
- `sector_name()` — cleaned name (`clean_name`: trailing `- – — : | .` dropped, no letter/digit = missing) or "Sector N" (`guide.article_right_nabar.unnamed_sector`).
- `sector_summary()` — route count (sport + boulder), multi-pitch count, grade range + band mix of the sport routes, number of routes matching the route finder (or `null` when not filtering).
- `sector_thumb()` — tile photo.
- Grade-mix bar: `.cg-grade-bar` + `.cg-gb-{easy|mid|hard|expert|project}` in `sass/components/_ui.scss` — same bands and colours as the route finder.

## Filtering

The area route finder (grade band, search, classics, hide ticked — `route_tools`, provided by `SectorAndRoutesTabComponent`) works in every view: Full / Tabs hide rows, Table drops rows, Tiles and Tabs show "matches" and dim sectors with 0.

## Jumping to a sector

Right-menu sector links and the phone "On this page" sheet call `open_sector(id)` (window event `cg-sector-open`):

| View | Reaction |
|---|---|
| Full | nothing extra — the menu scrolls to `#sector-{id}` |
| Tabs | selects that sector, then the menu scrolls to it |
| Tiles | opens that sector's panel; the page scrolls to the sectors block |
| Table | sets the sector select to it; the page scrolls to the sectors block |

A `?sector=` link opens Tabs on that sector; in Full, `OutdoorComponent` scrolls to it as before.

## Route rows (Full view)

In `SectorComponent.vue` the **whole row** of a sport / boulder route opens the route modal (click, or Enter on a focused row — `tabindex="0"`); the name and arrow cells no longer have their own click, so it opens once. Multi-pitch pitches: "More information" opens the MTP modal (unchanged).

## i18n

`guide.sector_view.*` (label, full, tiles, table, tabs, routes, mtps, sector, grade, all_sectors, shown) in `en.json` and `ka.json`. Georgian "Tiles" is "ბლოკები" so it doesn't read like "Cards" (ბარათები) in the article switch.
