# Article right menu ("Navigation Menu")

`resources/js/components/guide/items/navbars/RightMenuComponent.vue` — the sticky card beside the article on guide detail pages (mountaineering, ice, indoor, tech tips, other, special articles, businesses, outdoor). Wide screens only (`display-biger-then-768px`).

Under the title: the **Text / Cards** view switch for the article sections ([article_view_switch.md](article_view_switch.md)). Same switch on phones in the "On this page" sheet.

## Two modes

| Page | Menu |
|---|---|
| **Outdoor** (`$route.name === 'outdoor'`) | Fixed list: Description, Recommended services (when the block is on the page), Climbing sectors & routes (with the sector submenu), Live camera (when the area has one), Gallery, Comments, Climb It With a Local Guide (when tours exist), Similar articles. The page observer still runs here, only to know whether the two optional blocks are present. Outdoor areas already have a long sector list, so no subtitles are added. |
| **Every other page** (mountaineering, ice and indoor matter most) | Built from the page's own sections, in reading order (`autoSections`). |

## How the built menu is made (`scan_sections`)

The menu scans the page around it (the parent of its row: header, article, comments, similar articles) and turns it into items:

1. `#description` → **Description**.
2. Every `h2` with an id rendered by a component → one item, labelled with the heading's own (already translated) text: Best time, Address, How to get there, Working hours, Price, What you need, Info, Routes, Gallery, Comments, Similar articles…
3. Headings typed in the editor (`h1`–`h4` inside `.article_text_block`: the description, address, how-to-get, price texts, and the mountaineering route text) → **sub-items** of the section above them. They get an id `cg-subtitle-N` if they have none. `h3`/`h4` are indented one more step. Editor headings before any section become their own items.
4. Mountaineering: the massif card (`#massif`) → item with the massif's name and **one sub-item per tab** (About, Best season, Getting there, Map, What to bring, Weather). Clicking a sub-item opens that tab (`#massif-tab-{key}`) and scrolls to the card. The tab that is open in the card is marked in the menu (accent background, bold, eye icon); opening, closing or switching a tab re-renders the card panel, which triggers the re-scan below, so the mark follows it. The card's own title is not listed again.
5. `#live-camera` → **Live camera** (icon).
6. `#local-services` (the "Recommended services" business cards, `LocaleBisnesListComponent.vue`) → **Recommended services** (handshake icon) — only when the article has linked businesses.
7. `#tours` (the "Climb It With a Local Guide" slider, `ToursSliderComponent.vue`) → item labelled with the slider's own title (compass icon) — only when tours exist. Headings inside the slider are not listed.
8. Ice: `#routes` (or `#sectors` when the area has no route text) → **Climbing sectors & routes** with the sector / local-image-group submenu. Each sector's own `h2` is not listed separately.

Skipped: headings without an id (comment author names, business card titles, "Recommended services"), anything inside the menu itself.

The page is re-scanned (150 ms debounce) by a `MutationObserver` whenever content is added or removed — route images, the massif card, gallery, comments and similar articles load after the page — and on route / language change. The list is only replaced when it really changed, and mutations coming from the menu itself are ignored.

## Current section

On scroll (one `requestAnimationFrame` per frame), the last section or sub-item whose top has passed 100 px under the viewport top is the active one: its menu row gets the accent-soft background (top items) or an accent left bar (sub-items, sectors). A top item also stays highlighted while one of its sub-items is active. Massif tabs are never "active" (they all point at the same card).

## Sector list

- A local-approach photo with **several** sectors: the photo's title as a small group header (jumps to the photo, `#local-image-{id}`), its sectors nested under it.
- A photo with **one** sector: a single row (the photo's title, else the sector's name) that jumps to the photo — no header over a lone child.
- Placeholder names are cleaned (`clean_name`): trailing `- – — : | .` are dropped ("Sector -" → "Sector"), and a name with no letter or digit ("-") counts as missing. Unnamed sectors show as **"Sector N"** (`guide.article_right_nabar.unnamed_sector`, N = its place on the page); an untitled photo uses `local_image_group_fallback`.
- With a non-Full sector view ([sector_views.md](sector_views.md)) a sector link first calls `open_sector(id)` (Tabs selects it, Tiles opens its panel, Table filters to it), then scrolls on the next frame; when the sector's heading doesn't exist (Tiles, Table) or a `#local-image-…` target is missing, it scrolls to `#sectors` instead.
- Jumps re-aim after the smooth scroll (at ~0.9 s and ~1.6 s): lazy photos and charts above the target load during the scroll and used to push it down, so the page stopped short.

## Fits the window

The card is sticky, and with a side scene it can't clip (`_fun.scss` sets `overflow: visible; max-height: none` so the climbers stay visible), so a long menu used to run off the bottom of the screen. Now `fit_menu()` gives the list (`<nav ref="nav">`) a `max-height` = window height − the card's sticky `top` − the card head above the list (title, view switch, quick stats) − anything below it − padding (min 160 px), and only the list scrolls (`overflow-y: auto`, `overscroll-behavior: contain`). It re-runs on window resize and whenever the card resizes. When the current section changes, `reveal_active()` scrolls the list (not the page) so the highlighted row stays visible.

Between 768 and 1199 px the view switch hides its "View" label so both buttons fit the narrow card.

## Not changed

Quick stats (sectors / routes / multi-pitches) on outdoor and ice, the `below` slot, and the side scenes (`sideScene`, see `docs/FRONTEND/FRONTEND.md`). Scrolling still offsets 80 px for the fixed navbar.
