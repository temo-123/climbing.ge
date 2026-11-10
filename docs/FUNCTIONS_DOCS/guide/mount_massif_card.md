# Mountaineering route: mountain massif card

`resources/js/components/guide/items/MountMassifCardComponent.vue`, used at the top of the main column of `guide/pages/pages/MountaineeringPageComponent.vue` (`<mountMassifCard :massif="mounts_system" />`).

## Why

The old block printed the whole massif article (text, best time, how to get there, weather, map) above the route, under a "Massive Mountain – Name" heading with a plain-text "X" / "Read more" toggle. Readers couldn't tell where the massif info ended and the route started, the toggle wasn't translated, and its `best_time_to_climb` / `how_to_get_there` ids clashed with the route's own sections.

## Design

One card that reads as **context** for the route, not as part of it:

1. **Header** — mountain icon, eyebrow "Mountain massif", the massif name, one plain-text lead line (short description, or the first ~180 characters of the text). On the right: the existing `mount-summits-map-modal` "Show Map" button (Google map, OSM summits, summit list) when the massif has a map.
2. **Fact chips**
   - "All N routes in this massif ›" (accent chip) → `/mountaineering?mount={id}` — only when the massif has other public routes than this one.
   - One chip per published summit of the massif: name + height, opens the summit page on the summit subdomain (new tab).
3. **Tabs**, one per filled field only (empty / tag-only text = missing): About (`text`), Best season (`best_time`), Getting there (`how_get`), Map (shown when there is a Google map — `global_data.map` holding an `<iframe>`/`<img>` — and/or at least one published summit with coordinates; full card width, 380 px high, 280 px on phones. **Both maps exist → a Google Map / OpenStreetMap switch** above the map, Google first. The OpenStreetMap view is `items/MountMassifOsmMapComponent.vue`, loaded only when shown (Leaflet is a separate chunk): one accent dot per summit with a popup (name, height, link to the summit page), zoomed to fit all summits, scroll-wheel zoom off so the page still scrolls), What to bring (`what_need`, never shown before), Weather (`global_data.weather` embed). The "Show map" button in the header stays (shown when either map exists — note the stored map is a bare `<iframe>`, so it must not be tested with `has_text()`, which strips tags; that hid the button): its modal adds the OpenStreetMap view with summit markers and the summit list.
4. **Open by default** on the first tab (About, or the first filled one). Clicking the active tab or "Hide details" closes it; a tab click or the "Read about {name}" button opens it again. Nothing is remembered: every page load (and every route change) starts open.
5. Long texts (> 600 characters) are shown **in full by default** (also after switching tabs or routes), with "Show less" to cut them to 260 px with a fade (then "Show more"). Forecasts and maps are never cut.

Ids: the card is `#massif` and each tab button is `#massif-tab-{key}` (about / season / getting / map / gear / weather) — the right menu uses them to list the massif with one sub-item per tab ([article_right_menu.md](article_right_menu.md)). No other ids inside the card, so the route's own sections stay unique.

## Data

`GET /api/get_mount/on_page/{lang}/{mount_route_id}` → `{ locale_data, global_data, routes_count, summits }`, or empty when the route has no massif / the massif isn't viewable (`Mount::viewable()`). The card renders nothing unless `locale_data.title` exists.

## States

| State | Shows |
|---|---|
| No massif / hidden massif | nothing |
| Massif with only a title | header only |
| No Google map and no summit coordinates | no "Show map" button, no Map tab |
| Only one of the two maps | Map tab without the Google / OpenStreetMap switch |
| Only route in the massif | no "all routes" chip |
| No summits linked | no summit chips |
| No text fields and no map | no tabs / footer |

i18n: `guide.massif.*` in `en.json` and `ka.json`.

Also on the page: the grade bar no longer has a second "Route Grade" heading and grey box around it (`MountGradeBarComponent` already has both).

The modal (`global_components/modals/MountSummitsMapModalComponent.vue`) links each summit with a `router-link` only on the summit subdomain; on the guide (this card, the mountaineering list) it uses a plain link to the summit subdomain in a new tab.
