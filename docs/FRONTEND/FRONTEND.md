# Frontend Architecture — climbing.ge

Vue 3 + Vite (via Laravel Mix) multi-subdomain SPA.

---

## Table of Contents

- [Design System](#design-system)
- [Bundle & Boot](#bundle--boot)
- [Directory Structure](#directory-structure)
- [Global Components](#global-components)
- [Global Services](#global-services)
- [Mixins](#mixins)
- [State Management (Pinia)](#state-management-pinia)
- [Routing](#routing)
- [i18n / Localization](#i18n--localization)
- [CASL Permissions](#casl-permissions)
- [Axios Setup](#axios-setup)
- [SEO / Meta Tags](#seo--meta-tags)
- [Rich Text Editors](#rich-text-editors)
- [Subdomain Components](#subdomain-components)

---

## Design System

> **Read this before you touch any style, layout or visual component.** It is written for both people and AI assistants: the *Concept* explains the direction, the *Rules* are binding, the rest is reference.

### Concept & direction

climbing.ge is made **by climbers, for climbers**. The UI should feel like a well-used guidebook: an outdoor brand that is clean and readable, with a few hand-drawn, playful details.

| Principle | What it means in practice |
|---|---|
| **Chalk & granite** | Light "chalk" page background (`--cg-chalk`), dark "granite" text (`--cg-ink`). Flat surfaces, soft radii, light shadows. No glossy gradients, glassmorphism or neon. |
| **One system, six accents** | Every subdomain uses the same components. Only the accent colour changes (`<body data-service="…">` → `--cg-accent`), so you can always tell which service you're in. |
| **Pencil sketches of real gear** | Decoration is always *recognisable climbing gear or terrain* (carabiner, quickdraw, hold, ice axe, shoe, helmet, rope, chalk bag, peaks) in a wobbly white or ink pencil line. Never generic abstract blobs, stock icons or emoji. |
| **Flat colour + sketch** | Page heads are a flat colour band with one sketch of the page topic on the right. Content stays on plain chalk/white. |
| **Climbing jokes, low volume** | Small motion details make climbing jokes (rappel climber on long pages, chalk puff, falling leader on the 404, swinging ice tool). They're optional, quick, never block content, and turn off for `prefers-reduced-motion`. |
| **Content first, fast first** | Photos and text win over decoration. Every decorative layer is CSS/inline SVG (no extra requests), and none of it may cost scroll or load performance (see *Performance rules*). |
| **Bilingual** | Every font stack falls back to Noto Sans Georgian; test long Georgian strings (they're ~30% wider). Uppercase display text must still look right in Georgian. |

Fonts: **Barlow Condensed** 600/700 for display/headings (`--cg-font-display`), **Manrope** (variable 400–800) for body text, both falling back to **Noto Sans Georgian** (variable 400–700). Icons: **Font Awesome 4.7** (`fa fa-*`). Don't add other icon sets.

### Rules (do / don't)

**Do**
- Use Bootstrap 5 classes (`row`, `col-md-4`, `g-4`, `d-flex`, `ms-2`, `text-bg-*`, `data-bs-toggle="dropdown"`) and the `cg-*` building blocks below.
- Use tokens (`var(--cg-accent)`, `var(--cg-ink)`, `var(--cg-r)`, `var(--cg-shadow)`) in component styles, never hard-coded service colours, so the component follows whichever service renders it.
- Put shared styles in `resources/sass/**`; keep `<style scoped>` in a component only for that component's own layout.
- Put cards in a real grid: `<div class="row g-4">` → `<div class="col-md-4 col-sm-12">` → card. A card component whose root is the column (e.g. `TourCardComponent`) must be placed directly inside a `.row`.
- Give every list/detail page a `.cg-page-head` with one `cg-art-<name>` sketch.
- Add every new UI string to both `resources/lang/i18n/en.json` and `ka.json`.
- Wrap anything animated in `@media (prefers-reduced-motion: reduce)` (CSS) or check `matchMedia('(prefers-reduced-motion: reduce)')` (JS).

**Don't**
- Don't load Bootstrap, jQuery, fonts or icon CSS from a CDN in a layout. Everything goes through `partials/assets.blade.php` + `app.css`.
- Don't use BS3/BS4 class names in new code (`col-xs-*`, `pull-right`, `ml-*`, `badge-primary`, `panel`, `data-toggle`). They only work through the legacy shim.
- Don't `require('bootstrap')` or import a whole Bootstrap JS bundle. Import single plugins from `bootstrap/js/dist/<plugin>` in `resources/js/bootstrap.js`.
- Don't add a Bootstrap SCSS partial to `app.scss` unless templates really use it (modal, carousel, toasts, tooltip, popover, offcanvas, accordion, placeholders and close are deliberately left out).
- Don't use photos, PNG/JPG textures or icon fonts for decoration. Use inline SVG via `cg-svg()`.
- Don't change the footer colour `#484848` or the `mountains.svg` skyline (brand elements; the rappel climber lands on that skyline).
- Don't edit `public/assets/` (build output) or `TabBodyComponent.vue` (admin tables).

### Files

```
resources/sass/
├── app.scss                    # entry — import order matters; imports only the Bootstrap partials in use
├── abstracts/_bootstrap-config # palette + Bootstrap variable overrides ($enable-dark-mode: false)
├── abstracts/_svg              # cg-svg($svg) → url("data:image/svg+xml,…") encoder
├── abstracts/_doodles          # cg-gear-doodles($color, $opacity) → 360px gear wallpaper tile
├── base/_tokens                # :root custom properties (--cg-*), --cg-topo / --cg-topo-dark wallpapers
├── base/_themes                # [data-service] accents (--cg-accent*) + Bootstrap runtime vars
├── base/_base                  # element defaults on top of Bootstrap reboot
├── base/_legacy                # BS3/BS4 shims for DB-stored / dynamic markup
├── layout/_navbar              # .cg-nav header (all public services)
├── layout/_footer              # footer (#484848 + mountains.svg)
├── layout/_admin               # user panel: .cg-topbar, .cg-sidebar, .cg-drawer, .cg-menu, dashboard, admin forms
├── layout/_loaders             # preloader, route progress bar, server error pages
├── components/_ui              # buttons, section titles, page heads, cards, chips, forms, tables, segmented control
├── components/_content         # legacy content blocks (portfolio cards, horizontal cards, events, hero…)
├── components/_article         # article / detail pages
├── components/_auth            # login / register / reset split layout
├── components/_sketches        # $cg-sketches: page-head line art, one SVG per topic
└── components/_fun             # climbing touches: page-head sketches/colours, reveals, image areas, ice tool…

resources/views/partials/assets.blade.php   # fonts, FA 4.7, app.css — included by every layout
resources/js/bootstrap.js                   # axios + Bootstrap Dropdown only (window.bootstrap = { Dropdown })
resources/js/services/fun/                  # chalkBurst.js, imageFallback.js, leadClimbScene.js
```

### Tokens

| Token | Use |
|---|---|
| `--cg-accent`, `--cg-accent-dark`, `--cg-accent-soft`, `--cg-accent-rgb` | the current service's colour (set by `data-service`) |
| `--cg-ink`, `--cg-ink-2`, `--cg-stone`, `--cg-muted` | text (granite) |
| `--cg-chalk`, `--cg-chalk-2`, `--cg-line` | page background (chalk), surfaces, borders |
| `--cg-hold` | signature "hold yellow", shared by all services (CTAs, slider, markers) |
| `--cg-rope` | "rope orange": sale badges, favourites, warnings |
| `--cg-topo` / `--cg-topo-dark` | gear-doodle wallpaper. Light lines for dark surfaces (header, sidebar, drawer, auth panel, tiles, preloader); ink lines for light ones (modals, empty states, error cards, image areas) |
| `--cg-grain`, `--cg-ridge` | rock-grain body texture, ridge line under page heads |
| `--cg-r*`, `--cg-shadow*`, `--cg-nav-h`, `--cg-ease` | radii, shadows, header height, easing |

Service accents: guide teal `#1f93b0`, shop green `#18a06f`, blog sandstone `#c4532d`, summit violet `#6c45c4`, films raspberry `#a83d6e`, user/admin purple `#7427bb`.

### Building blocks

| Class / component | What |
|---|---|
| `.cg-nav` (+ `__inner`, `__links`, `__tools`, `__toggle`, `__menu`) | fixed header used by every service navbar |
| `<cg-brand service="shop" :to="…">` | logo mark + "climbing.ge / Shop" (global) |
| `<services-switcher current="shop">` | one menu to jump between subdomains (global) |
| `.cg-page-head.cg-page-head--list.cg-art-<name>` (+ `__icon`, `__text`, `__lead`, `__fx`) | flat colour band + sketch at the top of list/detail pages |
| `.cg-section-title` / `.index_h2` / `.block_title` | section heading with the rope-and-hold underline |
| `.cg-card`, `.cg-panel`, `.cg-chip`, `.cg-grade`, `.cg-stat`, `.cg-empty` | content primitives |
| `.cg-segmented` | pill toggle (grid/list, group mode, tabs) |
| `.btn-hold` | yellow call-to-action button |
| `.table.table-hover.cg-route-table` (+ `--cols`) | route tables. With `cg-route-table--cols` (sector tables) each column type has its own colour, tagged `cg-col-num / name / height / bolts / grade / grade2 / info` (palette `$cg-route-cols` in `_ui.scss`: slate, teal, green, rope orange, violet, raspberry), as a tinted header with a coloured top stripe plus light cell tints; names and the main grade are bold. Without `--cols` (MTP modal) every other column gets an accent stripe. The row hover is translucent, with an accent edge on the first cell (a `::before`, never an animated box-shadow, which made it blink) |
| `.article_card_container` | becomes a responsive CSS grid when it directly holds `.article_card` / `.food` children (not in `.list-view`) |
| `AdminMenuTreeComponent`, `.cg-topbar`, `.cg-sidebar`, `.cg-drawer` | user/admin shell (see `COMPONENTS/USER_LEFT_MENU.md`) |
| `AuthAsideComponent` + `_auth.scss` | split login/register layout with the doodle panel |

### Page-head sketches

Available: `holds, quickdraw, iceaxe, mountains, trophy, bolts, tent, compass, rope, shoe, carabiners, notebook, summit, film, map, tag`.

```html
<div class="cg-page-head cg-page-head--list cg-art-iceaxe"> … </div>
```

**Adding a sketch:**
1. In `components/_sketches.scss`, add an entry to `$cg-sketches`. Copy an existing one and replace only the art inside `<g id='a'>…</g>`. Keep the `viewBox='0 0 300 180'`, the `#k` wobble filter, the optional `fill-opacity='.55'` colour-wash group and the `<use href='#a'>` offset pencil stroke. Use single quotes inside the SVG, white strokes and `stroke-linecap/linejoin='round'`. Draw the art once: the `<use>` repeats it, so don't paste the paths twice.
2. Add its band colour to `$cg-head-colors` in `components/_fun.scss`. Use `var(--cg-accent)` for service-neutral topics, or a fixed nature colour (glacier blue, forest, slate…) when the topic has a natural one.
3. The `@each` loop in `_fun.scss` generates `.cg-page-head.cg-art-<name>` automatically.

### Climbing touches

All of these respect `prefers-reduced-motion`.

| Element | Where |
|---|---|
| Rock-grain texture on the page background (`--cg-grain`, scrolls with the page) | `body` |
| Page-head sketch hangs on the band and sways slowly, faster on hover | `.cg-page-head::before` in `_fun.scss` |
| Preloader "sketchbook": mountains, carabiner, ice axe, hold and quickdraw draw themselves (`pathLength="1"` dash animation) over the doodle wallpaper. Hides on `window.load` (min 450 ms, max 2 s); uses `<transition type="transition">` and `pointer-events:none` while leaving so it can never block clicks | `loaders/PreloaderComponent.vue`, `layout/_loaders.scss` |
| Image areas: every photo frame (`#image`, `.item-img`, `.product-image-container`, `.horizontal-card-image-wrapper`, `.m-image-hero--media`, `.service-icon`…) has a chalk + doodle background, shown while loading or with no photo. Inline CSS-background photos add the doodles as a second layer (`url(photo), var(--cg-topo-dark)`). A broken `<img>`, or a stock "no image" picture (`site_img/image.png`, `demo_imgs/shop_demo.jpg`), is swapped for a transparent 4:3 box with class `cg-img-fallback`. Opt out with `data-no-fallback` | `services/fun/imageFallback.js` (installed in `app.js`), image rules in `_fun.scss` |
| Ice page: the ice tool swings and strikes an icefall, and chips burst on each hit | `.cg-art-iceaxe` in `_fun.scss` + `.cg-page-head__fx` overlay in `guide/pages/lists/IceListComponent.vue` |
| Blocks "top out" into view and section-title ropes pay out on scroll (CSS `animation-timeline: view()`, progressive enhancement) | titles, cards, tiles |
| Rappel + back to top: on **long pages** (more than 1.5 screens of scrolling, in the visitor's own screen height) a climber abseils down the right edge as you scroll. The rope is tight from the anchor to the descender (drawn behind the body; the descender is on the harness); the free tail hangs below it and swings like a real rope (a wave that grows toward the free end, bigger the faster you scroll, settling in about a second). Near the bottom the climber lands on the footer mountains' skyline (sampled from `mountains.svg` on a canvas), unties, and the rope end flutters. The **back-to-top button** is a white disc with a scroll-progress ring in the service accent and an up arrow; on hover it fills with the accent. Short pages, phones, the user subdomain and the wall calculator only get the button (it appears after 100px). Drawing is event-driven (scroll/resize/ResizeObserver, plus frames only while the rope still swings or flutters), using `clientWidth` so the feet touch the page edge, not the scrollbar | `GoOnTopComponent.vue` (`<go_to_top>`, once per root), `.cg-rappel` / `.back-to-top` in `_fun.scss` |
| Chalk-dust puff on click of `.btn-primary`, `.btn-hold`, `.btn-success`, dashboard/discipline tiles | `services/fun/chalkBurst.js` |
| "Off route!" 404: a calm, realistic lead climb on a simple flat rock face. The bolts follow the rock a little but stay nearly in a straight line (`ROUTE_X(y)`), so the rope runs straight up through long quickdraws. The holds are just right of the bolt line, so each draw hangs by the climber's left side, never hidden behind the body, and is clipped with the left hand (the carabiner pops, scaling up with a white ring, as the rope goes in). The first bolt is ~3m up; there are four draws, then only the lowering anchor; a lowering anchor (two bolts, chains, ring) tops the route. The weight shifts before each foot move; then feet up, stand up, reach, with short rests. The pace is quick on the easy start and slower and more deliberate toward the crux. The climber clips the four quickdraws the way lead climbers do: as soon as the bolt is within reach in front of the chest or face, they pull rope up from the tie-in and clip it, never reaching down to a draw below the harness. Then they run it out about 1m above the last one, pull up rope to clip into the anchor and slip mid-clip. That's a real lead fall (~5m): twice the distance above the bolt plus the clip slack, at real gravity for the scene's scale (≈400px/s², climber ≈1.7m). Arms go up, the body tips back slightly, and the feet come to the wall at the catch. A soft dynamic rope gives one small bounce. The belayer stands right of the route and is a spring-mass pulled along the rope toward the first bolt: lifted (~0.7m) and pulled a step or two in toward the wall only once the rope tension beats their weight, then dropped back to the ground (no flailing, no speech bubble); then the climber hangs, is lowered off, and the loop repeats (~32s from the ground; the fall comes at ~22s). The rope runs behind the climber, who is seen from behind, to a tie-in knot at the harness. Simulated once at 60fps (IK limbs) and played back with rAF; reduced motion shows a still frame hanging on the rope. Lazy-loaded, so it's only downloaded when a 404 is shown | `errors/404Component.vue` → `defineAsyncComponent(OffRouteSceneComponent.vue)` + `services/fun/leadClimbScene.js` |

**Adding a new touch:** keep it to one small, recognisable climbing moment. Draw it as inline SVG/CSS, give it a reduced-motion fallback, and make sure it never covers content or catches clicks (`pointer-events:none` on overlays). It also has to meet the performance rules below.

### Admin forms & tables (user subdomain)

Most add/edit pages share one legacy markup. `layout/_admin.scss` normalises it, so **new forms should use the same markup and get the look for free**:

```html
<div class="tabs">
  <div class="row"><div class="form-group"><button class="btn btn-primary">Back</button></div></div>   <!-- action bar -->
  <div class="row"><div class="form-group"><button class="btn btn-primary">Save</button></div></div>
  <div class="row">                                           <!-- radio tabs → pill switcher -->
    <div class="col"><input type="radio" id="tab1" :value="1" v-model="tab_num"><label for="tab1">Global info</label></div>
    …
  </div>
  <div class="row width_100" v-show="tab_num == 1">
    <div class="jumbotron width_100"><div class="row"><div class="col-12">   <!-- intro card -->
      <h2 class="display-4">Section title</h2><p class="lead">Subtitle</p>
    </div></div></div>
    <form class="width_100">
      <published_item :published_prop="…" @item_data="…" />            <!-- publish state first -->
      <div class="form-group row">                                      <!-- label | field row -->
        <label class="col-md-2 control-label">Name</label>
        <div class="col-md-10"><input class="form-control" v-model="…"></div>
      </div>
    </form>
  </div>
</div>
```

- **Label | field rows:** any `.form-group` with `label.control-label` + a `col-*` wrapper becomes an 11rem / 1fr grid (stacked under 768px), whatever the old column classes were (`col-xs-2`, `col-8`, `col-10`…). Hints and alerts after the field stay in the field column.
- **Plain labels** inside `.tabs` are reset to normal field labels. Many pages still have a copied scoped `.tabs label { gray bar }` rule meant only for the radio tab buttons; don't copy it into new pages.
- **Intro cards** (`.jumbotron`) are compact white cards with an accent edge. The inline `margin-top: 5%` some forms put on the following `<form>` is neutralised.
- **Published:** every content type with a `published` column gets `PublishedValueComponent` (`items/form/parts/`) as the **first** field of the global tab (0 = not public, 1 = public, 2 = public by URL only).
- **Data tables** (`items/data_table/TabsComponent.vue`): one toolbar row, with "go to list" + "add" on the left and the bulk action (select + Apply) + Refresh on the right. Below it, filters sit in an even auto-fit grid (`FilterComponent`), then the pager row (`PaginationComponent`): "Per page" select on the left, page links on the right. Row action buttons are compact. The table card sits inside the content edge and scrolls horizontally. Boolean cells use `['data', ['field'], 'bool']` and render Font Awesome ✓ / ✗ icons.

### Animated scenes (error pages & moments)

Small flat-style climbing scenes in the same visual language as the 404 (sky, rock facets, chunky holds; teal climber with a yellow helmet, orange belayer). Each is a **self-contained SVG with CSS keyframes** in `public/images/scenes/`, including a `prefers-reduced-motion` fallback. They cost nothing in the JS bundle, only load on the page that shows them, and work on the Blade error pages too (no Vue there).

| Scene | Story | Used on |
|---|---|---|
| (404 simulation) | lead climb → slip clipping the top draw → fall & catch | SPA `/404` and unknown routes (`OffRouteSceneComponent`, JS) |
| `403.svg` | "Closed project": red tape on the first quickdraw | SPA `/403`, Blade 403 |
| `500.svg` | "Rope's stuck": belayer tugs the jammed rope, climber stays at the anchor | SPA `/500`, Blade 500 |
| `401.svg` | "Tie in first": climber ties a figure-eight, belayer waits | Blade 401 |
| `419.svg` | "Weather window closed": clouds roll over the summit, the team turns back | Blade 419 |
| `429.svg` | "Queue on the route": climbers shuffle up a fixed line | Blade 429 |
| `bolter.svg` | Bolting a new route, side profile, on a rock face: hanging on a static rope from a two-bolt anchor (sling and locking carabiner), feet flat on the rock, hammer drill square to the face, dust, bolt bag | Blade 503 |
| `bolter-solo.svg` | The same bolter with **no background**; the image's right edge is the "rock" | Right menu card: special articles, outdoor areas with one sector (`.cg-card-bolter`, see below) |
| `lost.svg` | "Lost on the approach": the compass won't settle | Blade 404 (server), host-error page |
| `summit.svg` | top-out + flag (one-shot) | Summit "ascent recorded" (`MakeAscentModal`) |
| `bolting.svg` | bolts appear up a new line, the anchor glints (one-shot) | `DonationSuccessPage` |
| `pack.svg` | gear drops in, lid closes, ready tag (one-shot) | `PaymentSuccessPage` |
| `comp.svg` | climbing festival: two climbers race up a speed wall to the buzzers, winners on the podium, fans with Georgian flags, confetti | `EventPageComponent` (banner at the bottom of the event content) |
| `empty.svg` | a climber at a blank wall, shrugs (short) | `EmptyPageComponent` (every empty list) |

**Right-menu side scenes.** On article detail pages the right menu card (`guide/items/navbars/RightMenuComponent.vue`) gets a climbing scene on its left edge. The card's edge is the "wall" and the card bottom is the ground. It only shows on wide screens (≥1200px), and the card gets `overflow: visible` (`.cg-has-side-scene`). `RightMenuComponent.sideScene` picks it from the route:

| Page | Scene |
|---|---|
| `outdoor` with 2+ sectors (tall menu) | `lead`: lead climbing with a partner. The belayer stands ~1m from the route. The climber clips every quickdraw (rope pulled up through the hand, carabiner pops), clips the anchor (two hangers, chains, quick-link and ring) and is lowered. Then a real changeover: the climber unties, the belayer pulls the rope down through the anchor and draws into a pile, they swap places, the new leader ties in from the pile and the other loads the belay device |
| `outdoor` with 1 sector, `special_article` | the bolter image (`bolter-solo.svg`) |
| `ice` | `ice`: lead ice climbing with ice screws and a belayer at the base. The climber holds an A-frame stance: hips out from the ice, feet low and wide, tools high |
| `indoor` | `indoor`: lead climbing on a gym wall with coloured holds; no helmets |
| `other` | `slackline`: a slackliner walks a line between two trees under the card, arms out for balance, the line sagging under their feet; turns round at the ends |
| `tech_tip` | `safety`: practising a lead fall. Climb ~1m above a clipped draw, let go, a soft catch (the belayer is pulled up and in), hang, climb back up |
| `mountaineering` | `summit`: the card top becomes a mountain. The leader climbs the side placing protection (cams on slings, rope clipped in), belayed from the bottom; tops out, turns round and belays the second up from above. The second removes each piece on the way. Both walk to the summit, stand either side and a large waving Georgian flag (taller than the climbers) goes up. They walk back to a rappel anchor (sling round a rock horn), rappel one after the other on a double rope (orange + blue strands) and pull the rope down; the flag stays (the sticky card sits lower: `.cg-has-mountain-top`) |

The menu lists sectors on outdoor **and ice** pages, and adds a *Live camera* item when the article has one (`#live-camera`). The outdoor choice waits for the sector request (`sectorsLoaded`), so the scene doesn't start as the bolter and then switch.

Shared rules in `MenuSideSceneComponent`:
- **Entrances:** climbers walk out from behind the card before they start. The SVG is clipped to left of, above and below the card, never over it.
- **Belayers:** ground belayers aren't anchored; their spare rope lies in a coil beside them, with the brake strand running from the belay device into it (404 too). Only the top belay on the mountain is tethered to the rock-horn anchor.
- **Ropes:** drawn with rounded corners (`roundPath`), and hang in a curve between walking climbers (`belly`). Double-rope rappel strands keep their colours while the rope is pulled.
- **Ledge:** a short ledge only under the climbers (it never covers the page), styled per category: rock and grass, gym mat, snow.
- **Clipping:** the quickdraw swings on its hanger for a moment and the rope swings out as it's clipped.
- **Proportions:** legs are 19+19px (about half the body height), standing feet are apart, and when climbing the feet stay in a band well below the hips, so legs never fold up under the body.

`MenuSideSceneComponent.vue` (lazy-loaded) draws side-profile figures from a pose (one torso stroke; limbs solved with two-bone IK, so they always join the body). Climbing limbs step hold to hold one at a time (`holdY`). It sizes itself to the card (`ResizeObserver`), runs at ~30fps only while on screen and the tab is visible, and shows a still frame for reduced motion.

In Vue use `<cg-scene name="403" />` (`<cg-scene name="summit" wide />` for the 360×240 moment scenes); in Blade use `<img class="cg-scene-img error-scene" src="{{ asset('images/scenes/403.svg') }}" alt="" aria-hidden="true">`.

**Rules for new scenes:** draw figures with one thick torso stroke and start every limb inside it (no gaps between body parts); one short, true-to-climbing story (no speech bubbles or cartoon effects); viewBox 360×420 (error) or 360×240 (moment); reuse the palette and figure shapes from an existing scene; when an element needs both a position and an animation, put the animation on an **inner** `<g>` (a CSS `transform` replaces the SVG `transform` attribute); one-shot scenes use `animation-fill-mode: both`; keep files under ~6 KB; always include the reduced-motion block. Animate moments, not content pages: no scenes on guide/shop content pages or in the admin.

### Event dates

`guide/items/EventDatesComponent.vue` (event page, under the title): a status pill (*Starts in N days*, *Starts today*, *Happening now* or *Finished*) and Start → End date tiles (weekday, big day number, month and year, time), with the duration between them, or one tile with a time range for single-day events. Names come from `Intl.DateTimeFormat` in the site language (`en-GB` / `ka-GE`); `00:00` times are hidden. Time formats elsewhere use `h:mm A` / `HH:mm` (moment's `MM` is the month, not minutes). Until the event has finished it also offers **Add to calendar**: a Google Calendar link (`ctz=Asia/Tbilisi`) and a generated `.ics` file (all-day events end the day after the last day; timed events use floating local time). Pass `title` and `details` (HTML is stripped).

### Galleries

The index and article galleries (`guide/items/galleries/ArticleImageGalleryComponent.vue`) show even **landscape 3:2 tiles** (`object-fit: cover`); the lightbox opens the full, uncropped photo.

### Guide article cards

`ArticleCardComponent`, `OutdoorCardComponent`, `MountCardComponent` and `ArticleSerchingCardComponent` share one markup: `.portfolio-img.view.view-first` > badge, **`.cg-card-media`** (photo + `.mask` with the arrow link), `.isotope-item-dtls` (title, text, chips). The hover mask lives inside `.cg-card-media`, so it always covers exactly the photo, never the title or text, whatever the photo height. The hover state is a light accent-tinted gradient with a round white arrow button. In grids the card fills its whole cell (`height: 100%` in `.cards_block` / `.article_card_container`). Keep this structure for new cards.

### Images

- Inside photo frames use a plain `<img>` with `object-fit: cover`. Don't add your own "no image" placeholder: `imageFallback.js` and the frame background handle it.
- If a component has its own `@error` fallback, it still wins: the global handler waits a tick and only steps in when the source is still broken.
- Use `loading="lazy"` on images below the fold.

### Legacy markup

Templates were migrated to Bootstrap 5 class names with a codemod (`col-xs-* → col-*`, `pull-right → float-end`, `ml-* → ms-*`, `badge-* → text-bg-*`, `data-toggle → data-bs-toggle`…). `base/_legacy.scss` keeps dynamically built or database-stored BS3/BS4 classes working:
- `col-xs-*`, `pull-*`, `panel`, `label`, `badge-*`, `ml-/mr-/pl-/pr-*` aliases.
- **Orphan columns:** an exact `col-{sm,md,lg,xl}-{1..11}` token outside a `.row` floats like in BS3, with gutters and bottom margin. The selector has zero specificity (`:where`), so anything else overrides it. `.container`, `.cols`, `col-sm-12`, `col-md-12` and `.h-recent-work` get a clearfix.

New code should never rely on the shim. Use a real `.row`.

### Mobile (phones, < 768px / < 576px)

Every page was audited at 390px wide (no horizontal overflow on any service). Rules that keep it that way:

- **Header menu:** below 1200px the links collapse into a scrolling panel; the page behind it is locked (`body:has(.cg-nav.is-open)`), and it closes on navigation. The tool dropdowns (grade system, language, services) **and the search panel** open *in the flow* as full-width blocks under the icon row (`display: contents` on their `<li>`). Never position them absolutely there: the scrolling panel clips them and Popper flips them upward.
- **Account button:** `NavBadgesComponent` shows the logged-in user's photo (`/images/user_profil_img/<file>` or a full social-login URL), ringed in the service accent; the user icon when logged out or when the photo fails to load.
- **Route tables** fit the screen without sideways scrolling: compact padding, small wrapping headers, names wrap, arrow-only Info column.
- **Shop product grid:** two cards per row from 340px (`.grid:has(> .grid-tile)`), one below.
- **Hero news cards** (`.m-image-hero`): 4:3 box on phones, title clamped to 4 lines.
- **Tap targets** ≥ 38–40px: card actions, segmented controls (`min-height: 2.5rem`), slider dots get an enlarged invisible hit area.
- **Tables that must scroll** (admin data tables, summit ascents) sit in `.table-responsive` / `.data_tab`; trim them on phones (e.g. icon-only badges with `d-none d-sm-inline` text) before letting them scroll.
- Test new pages at 390px: no horizontal overflow, nothing cut, the main action reachable with a thumb.

### UX helpers (October 2026)

Small, reusable pieces that make pages easier to use. Prefer reusing these over writing new ones.

| Piece | Where | What |
|---|---|---|
| `$timeAgo(date)` | global mixin (`app.js`) | "3 days ago" / "3 დღის წინ" via `Intl.RelativeTimeFormat`; falls back to `$formatDate` after ~35 days. Use it for anything user-posted (reports, posts, ascents). |
| `<html lang>` | `app.js` watcher | follows the i18n locale (`en` / `ka`). `_base.scss` uses `html[lang="ka"]` for Georgian heading line-height and wrapping. |
| `.cg-skip-link` | guide `IndexComponent` + `_base.scss` | "Skip to content" on the first Tab, jumps to `#main-content`. |
| `OnThisPageComponent` | `global_components/`, mounted in the guide and blog layouts | phones only (< 768px): floating "On this page" pill → bottom sheet listing every `h2[id]` (or `[id]` wrapper whose first heading is an h2) inside `.blog-main`; `sector-*` ids are nested. Re-scans after route changes (content loads late). Give new sections an `id` and they appear automatically. |
| `NavbarSearchComponent` | every service navbar | **Ctrl/⌘+K** or **/** opens it; ↑/↓ + Enter pick a suggestion; the last 5 searches per service are kept in `localStorage` (`cg_recent_searches_<site>`). |
| Toast actions | `ToastNotificationComponent` | `$bus.$emit('toast', { message, type, duration, action: { label, handler } })` adds a button (e.g. **Undo**). Use it instead of `confirm()`/`alert()` for reversible actions (see the favourite heart in `OutdoorComponent`). |
| `services/guide/recentlyViewed.js` + `RecentlyViewedComponent` | article pages record; list pages + guide home show | per-browser "Recently viewed" strip (outdoor, ice, indoor, mountaineering), `localStorage` only. |
| `services/fun/shareCard.js` | log-ascent modal | draws a 1080×1350 "Sent ✓" image on a canvas (grade tape, pencil skyline) and opens the native share sheet or downloads it. No network. |
| Animation switch | `services/motion.js`, `<motion-toggle>` in every navbar | turns the **article-page animations** off completely (not frozen): the side scenes beside the article menu, the phone scene banner (`MobileSceneComponent`) and the rappel climber (the go-to-top button stays). Stored per device in `localStorage.cg_motion` (`on`/`off`, default = the OS reduced-motion setting), so it holds across reloads and page changes; also an account default (`users.animations`). Components read the reactive `motion.enabled` and use `v-if`. **Not affected:** the 404 scene, empty-state and other `<cg-scene>` illustrations, the bolter image, sliders, chalk puffs — those follow only the OS setting via `window.cgReducedMotion()`. |
| Phone scene banner | `guide/items/MobileSceneComponent.vue` | under 1200px (where the right menu and its scene are hidden) the same `MenuSideSceneComponent` scene climbs a small "wall" card with the article's numbers and a jump button: outdoor (lead / bolter by sector count, after the sectors load via `SectorAndRoutesTabComponent`'s `loaded` event), ice, indoor, tech tip (safety), other (slackline), mountaineering (summit). |
| Account defaults | `services/preferences.js` | applies the user's saved language, grade systems and animations once per device after login — see [USER_PAGE.md](../USER_PAGE.md#preferences--welcome-questions). |
| Skeletons | component-local `cg-shimmer` keyframes | new async blocks (conditions, partners, crag picker) show shimmering placeholders instead of spinners; stop the animation under `prefers-reduced-motion`. |

### Performance rules

Measured production build (gzip, 2026-10-25): `app.css` ≈ 53 KB, `app.js` ≈ 342 KB (1.3 MB minified; mostly Vue, vue-i18n with both locale files, axios, the six subdomain shells). Lazy chunks total ≈ 7.9 MB on disk. Keep it that way:

| Rule | Why |
|---|---|
| Import only the Bootstrap SCSS partials and JS plugins in use | importing all of BS5 cost ≈ 51 KB CSS + 65 KB JS (raw), ≈ 6.5 + 13 KB gzipped |
| No `background-attachment: fixed`, and no big `filter`/`backdrop-filter` on full-page layers | forces a repaint of the whole page on every scroll frame |
| Avoid `:has()` with a universal or very broad subject (`*:has(…)`, `div:has(…)`) | re-evaluated on every DOM change; scope it to a class (`.article_card_container:has(> .article_card)`) |
| Animations: only `transform` / `opacity`. JS animations are event-driven (react to scroll/resize/click), never an always-running rAF loop, and are cancelled in `beforeUnmount` | idle pages must use 0% CPU |
| Heavy scenes are lazy (`defineAsyncComponent`) | the 404 simulation isn't in the main path |
| Decoration is CSS / inline SVG data URIs built with `cg-svg()`. In a repeated SVG, draw the art once and reuse it with `<use>` | no extra HTTP requests, smaller CSS |
| Fonts: one variable-font request through `partials/assets.blade.php`, `display=swap` | one CSS request, no FOIT |
| The preloader must hide by `window.load` + 2 s at the latest and must never catch clicks | perceived speed, no "dead page" |
| `webpack.mix.js`: `devtool` is `eval-source-map` **only outside production** | in production it wrapped every module in an eval string: app.js was ≈ 11 MB (2.5 MB gzipped) |
| `webpack.mix.js`: `runtimeOnly: true`; the root is mounted with `render: () => h(rootComponent)` in `app.js`. Never use string `template:` options or in-DOM templates | the template compiler (~190 KB) isn't shipped; a string template would render nothing |
| `webpack.mix.js`: async `splitChunks` groups `asyncVendors` / `asyncShared` (own names, since Mix re-merges `default`/`defaultVendors: false` after `mix.override`) | shared libraries (paper.js, cropper, image compression, moment…) were copied into up to 44 page chunks: 26 MB → 7.9 MB |
| moment locales are ignored (`IgnorePlugin`) | no `moment.locale()` anywhere; saves ~160 locale files per chunk |
| Heavy or rarely shown globals are `defineAsyncComponent` in `app.js` (Quill editors, `canvas-json-show`, `mount-summits-map-modal`), and so is the footer QR code | they and their libraries (Quill, Leaflet, qrcode + Buffer) stay out of the main bundle |
| Nothing third-party at boot: Echo/pusher-js, jsencrypt and the reCAPTCHA script load through `when_idle()` (`services/idle.js`), or right away when they're actually needed (login modal opens) | the first paint and the first route's data don't compete with them |
| No `window._` / full lodash: import single functions if you ever need them | full lodash was 533 KB raw and unused |
| `<site-img>` (one component for all subdomains, `guide/items/ImageComponent.vue`) is `loading="lazy" decoding="async"` by default; pass `loading="eager"` for an above-the-fold hero | list pages don't download every card image at once |
| Scroll-linked / rAF code: read layout (`getBoundingClientRect`, `getComputedStyle`, `scrollHeight`) **before** the first DOM write of the frame; cache values that don't change per frame (e.g. `--cg-nav-h`) | a read after a write forces a synchronous layout every frame (GoOnTop rappel) |
| Frame-capped loops use slack (`now - last > 29` for ~30 fps), not the exact interval | an exact `> 33` judders: it skips a 3rd frame now and then at 60 Hz and drops to 24 fps at 120 Hz |
| Scroll-driven (`animation-timeline: view()`) and infinite CSS animations: `transform` / `scale` / `translate` / `opacity` only. Move a drifting background onto a pseudo-element and translate it (see `.cg-preloader::before`) | composited animations stay smooth while the main thread is busy; `width` / `background-position` repaint every frame |
| Global DOM listeners: one capturing listener per concern (see `imageFallback.js`, `chalkBurst.js`), installed once (`window.__cg*` guard) | no per-component listener leaks |

### Checklist for a new page or component

- [ ] Uses Bootstrap 5 classes and `cg-*` blocks; cards sit in `row g-4` → `col-*`.
- [ ] Colours come from tokens (`--cg-accent`, `--cg-ink`…); no hard-coded service hex.
- [ ] List/detail page has a `.cg-page-head` with a fitting `cg-art-<name>`.
- [ ] Strings are in both `en.json` and `ka.json`; layout checked with Georgian text and at phone width (≥ 360 px, no horizontal scroll).
- [ ] Images: plain `<img>` in a frame, `loading="lazy"` below the fold, no custom no-image placeholder.
- [ ] Any motion has a reduced-motion fallback and doesn't catch clicks.
- [ ] No new CDN links, no full Bootstrap imports, no fixed backgrounds.
- [ ] The relevant doc in `docs/` is updated.

---

## Bundle & Boot

**Entry:** `resources/js/app.js`

On load the boot sequence:

1. Creates Vue app (root = render function of the subdomain's main component)
2. Registers global components (modals, nav-badges, etc.; editors / canvas / map modal as async components)
3. Installs plugins: Pinia, Vue Router (subdomain-matched), i18n, CASL, `@unhead/vue`
4. Sets `axios.defaults.baseURL` for the active subdomain
5. Attaches axios interceptors (401/419 → logout)
6. Sets `$bus` global event bus
7. Mounts to `#app`

**Hostname matching:**

```javascript
const host = window.location.hostname

if (host === process.env.MIX_SITE_URL)      { /* mount guide */  }
else if (host === process.env.MIX_SHOP_URL) { /* mount shop */   }
else if (host === process.env.MIX_BLOG_URL) { /* mount blog */   }
// ... etc
```

---

## Directory Structure

```
resources/js/
├── app.js                          # Entry point
├── routes/
│   ├── SiteRoutes.js               # climbing.ge
│   ├── ShopRoutes.js               # shop.climbing.ge
│   ├── BlogRoutes.js               # blog.climbing.ge
│   ├── SummitRouter.js             # summit.climbing.ge
│   ├── FilmsRoutes.js              # films.climbing.ge
│   ├── UserRoutes.js               # user.climbing.ge
│   └── ErrorRoutes.js              # fallback / error pages
├── store/
│   ├── auth.js                     # useAuthStore — user + CASL sync
│   ├── dataTableTabs.js            # useDataTableTabsStore — pagination state
│   └── routeLoader.js              # isRouteLoading ref for router hooks
├── services/
│   ├── ability/
│   │   └── ability.js              # CASL instance
│   └── localization/
│       └── i18n.js                 # Vue i18n setup
├── mixins/
│   ├── axios_mixin.js              # API helper methods
│   ├── easy_navigation_mixin.js    # $going navigation helper
│   ├── editor_config_mixin.js      # $editor_config Quill config
│   └── go_to_service_mixin.js      # Cross-subdomain navigation
├── components/
│   ├── global_components/          # Shared across all subdomains
│   ├── auth/                       # Login, register, verify pages
│   ├── errors/                     # Error pages (404, 500)
│   ├── guide/                      # climbing.ge components
│   ├── shop/                       # shop.climbing.ge components
│   ├── blog/                       # blog.climbing.ge components
│   ├── summit/                     # summit.climbing.ge components
│   ├── films/                      # films.climbing.ge components
│   └── user/                       # user.climbing.ge components
└── lang/
    └── i18n/
        ├── en.json
        └── ka.json
```

---

## Global Components

**Location:** `resources/js/components/global_components/`

Registered globally in `app.js` — available in every subdomain without importing.

| Component | Tag | Description |
|---|---|---|
| `StackModal` | `<stack-modal>` | Generic reusable modal |
| `NavBadgesComponent` | `<nav-badges>` | Notification/cart badge in navbar |
| `LoginModalComponent` | `<loginModal>` | Login modal overlay |
| `LocaleChangeComponent` | `<locale-switcher>` | EN/KA language toggle |
| `GoOnTopComponent` | `<go_to_top>` | Scroll-to-top button |
| `PreloaderComponent` | `<preloader>` | Full-screen page loader |
| `RouteloaderComponent` | `<routeloader>` | Route transition loader |
| `SwiperComponent` | `<swiperComponent>` | Hero image carousel |
| `SiteImgComponent` | `<site-img>` | Lazy-loading image |
| `StarReitingShowComponent` | `<star-reiting>` | Star rating display |
| `ServicesListComponent` | `<services-list>` | Services widget |
| `ViewControlsComponent` | `<view-controls-component>` | Grid/list view toggle |
| `MetaDataComponent` | `<metaData>` | SEO meta tags (per subdomain) |
| `NavbarSearchComponent` | `<navbar-search>` | Live search bar (calls `POST /search/suggest`) |
| `SkeletonLoaderComponent` | `<skeleton-loader>` | Loading placeholder skeleton |
| `ToastNotificationComponent` | `<toast-notification>` | Bootstrap 5 toast container (place in each root layout) |
| `CgBrandComponent` | `<cg-brand>` | Logo mark + service name for headers/sidebar |
| `ServicesSwitcherComponent` | `<services-switcher>` | Header dropdown linking all climbing.ge services |

### `StackModal`

The universal modal component. See [STACK_MODAL.md](STACK_MODAL.md) for full documentation.

```html
<stack-modal
  :show="modal_open"
  title="Modal Title"
  @close="modal_open = false"
  :saveButton="{ visible: true, title: 'Save' }"
  :cancelButton="{ visible: true, title: 'Cancel' }"
  @save="handleSave"
>
  <p>Modal content here</p>
  <template #footer>
    <!-- custom footer buttons -->
  </template>
</stack-modal>
```

---

## Global Services

### `$bus` — Event Bus

Simple publish/subscribe for cross-component communication.

```javascript
// Emit
this.$bus.emit('cart:updated', { count: 3 })

// Listen
this.$bus.on('cart:updated', (data) => { this.cartCount = data.count })
```

### `$going` — Navigation Helper (from `easy_navigation_mixin.js`)

Provides named route navigation with built-in scroll-to-top.

```javascript
this.$going('routeName', { id: 5 })
// equivalent to: this.$router.push({ name: 'routeName', params: { id: 5 } })
```

### `$editor_config` — Quill Editor Config (from `editor_config_mixin.js`)

Returns the standard Quill toolbar config object used across all editors.

```javascript
this.$editor_config  // returns toolbar configuration object
```

### `go_to_service_mixin.js`

Cross-subdomain navigation using environment variables.

```javascript
this.go_to_service('shop')    // navigates to shop.climbing.ge
this.go_to_service('guid')    // navigates to climbing.ge
this.go_to_service('summit')  // navigates to summit.climbing.ge
```

### `services/coefficients.js` — Admin-editable coefficients

The server prints every coefficient into the page as `window.__COEFFICIENTS__`, so reading one is synchronous and needs no API call.

```javascript
import { coef, deliveryDays } from '../../services/coefficients.js'

coef('wall_vat_percent', 18)  // value, or the fallback if missing
deliveryDays(true)            // "5-9" (made to order) / deliveryDays(false) → "2-4"
```

Pass values into translations as parameters. Don't write the numbers into the i18n strings. See [COEFFICIENTS.md](../COEFFICIENTS.md).

---

## Mixins

### `axios_mixin.js`

Helper methods for common API call patterns. Mixed into many page components.

```javascript
// Example usage inside a component
this.get_articles('outdoor', 'en', (data) => { this.articles = data }, onError, onFinally)
```

---

## State Management (Pinia)

**Location:** `resources/js/store/`

Three stores:

### `auth.js` — `useAuthStore`

Stores authenticated user data loaded from `GET /api/auth_user` and syncs CASL abilities.

**Actions:**
```javascript
fetchUser()      // GET /api/auth_user → stores user, calls updateAbility()
clearUser()      // clears on logout
```

**Usage:**
```javascript
import { useAuthStore } from './store/auth'
const store = useAuthStore()
await store.fetchUser()
```

### `dataTableTabs.js` — `useDataTableTabsStore`

Stores per-tab pagination state for the admin data table system.

### `routeLoader.js`

Exports the `isRouteLoading` ref, set by router `beforeEach`/`afterEach` hooks to drive the route transition loader.

---

## Routing

Each subdomain has its own router file in `resources/js/routes/`. All routers are created with `createWebHistory()` and include i18n locale prefix support.

### Locale Prefix

Routes support an optional `/:locale(ka)?` prefix for Georgian language:
```
/summits/list         → English
/ka/summits/list      → Georgian
```

The locale regex is built from `MIX_VUE_APP_I18N_SUPORTED_LOCALE` env var.

### Route Meta

Route meta fields:
```javascript
{ path: '/summit/:url_title', meta: { title: 'Summit Detail' } }
```

---

## i18n / Localization

**Location:** `resources/js/services/localization/i18n.js`

Uses **Vue i18n v9**.

**Language files:**
- `resources/lang/i18n/en.json` — English
- `resources/lang/i18n/ka.json` — Georgian

**Storage:** Language key stored in `localStorage`:
- `'us'` → English
- `'ka'` → Georgian

**Usage:**
```html
<h1>{{ $t('guide.title.outdoor climbing') }}</h1>
<p>{{ $t('summit.scan_qr.instruction') }}</p>
```

**Important:** Vue i18n v9 returns the key string itself (not `undefined`) when a key is missing. `$t('missing.key') || 'fallback'` does NOT work because the key string is truthy. Missing keys must be added to the JSON files.

### Key Namespaces

| Namespace | Subdomain |
|---|---|
| `guide.*` | climbing.ge |
| `shop.*` | shop.climbing.ge |
| `blog.*` | blog.climbing.ge |
| `summit.*` | summit.climbing.ge |
| `films.*` | films.climbing.ge |
| `user.*` | user.climbing.ge |
| `auth.*` | Shared auth pages |

---

## CASL Permissions

**Location:** `resources/js/services/ability/ability.js`

Loaded after login from `/api/auth_user` response.

```javascript
// Template
<button v-if="$can('add', 'summit')">Add Summit</button>

// Script
if (this.$ability.can('edit', 'article')) { ... }
```

**Plugin registration (`app.js`):**
```javascript
import { abilitiesPlugin } from '@casl/vue'
import ability from './services/ability/ability'
app.use(abilitiesPlugin, ability, { useGlobalProperties: true })
```

---

## Axios Setup

Global axios configuration in `app.js`:

```javascript
axios.defaults.baseURL = '/api/'                        // per-subdomain base
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
axios.defaults.headers.common['X-XSRF-TOKEN'] = localStorage.getItem('x_xsrf_token')
```

**Response interceptor:**
```javascript
axios.interceptors.response.use(
  res => res,
  err => {
    if (err.response?.status === 401 || err.response?.status === 419) {
      localStorage.clear()
      router.push({ name: 'login' })
    }
    return Promise.reject(err)
  }
)
```

---

## SEO / Meta Tags

Each subdomain has its own `MetaDataComponent.vue` in `components/{subdomain}/items/`.

**Location:** e.g. `resources/js/components/summit/items/MetaDataComponent.vue`

Uses `@unhead/vue` v3 for reactive head management.

```html
<metaData
  :title="summit.title + ' – Summit Climbing Georgia'"
  :description="summit.description"
  :image="'/public/images/meta_img/mountain.jpg'"
/>
```

**What it sets:**
- `<title>` tag
- `<html lang>` attribute (from localStorage language)
- `<link rel="canonical">` (current URL)
- `<meta name="description">`
- Open Graph: `og:title`, `og:description`, `og:image`, `og:url`, `og:type`
- Twitter Card: `twitter:card`, `twitter:title`, `twitter:description`, `twitter:image`

**`@unhead/vue` import note:**
```javascript
// app.js — createHead must come from /client subpath
import { createHead } from '@unhead/vue/client'

// MetaDataComponent.vue — composables from main package
import { useSeoMeta, useHead } from '@unhead/vue'
```

---

## Rich Text Editors

Three Quill-based editors registered globally:

| Global Tag | Component | Use Case |
|---|---|---|
| `<big_editor>` | `BigEditorComponent.vue` | Full featured: paragraphs, images, tables, embeds |
| `<small_editor>` | `SmallEditorComponent.vue` | Basic inline formatting |
| `<mini_editor>` | `MiniEditorComponent.vue` | Single-line rich text |

**Location:** `resources/js/components/user/items/form/parts/editor/`

**Usage:**
```html
<big_editor v-model="content" />
<small_editor v-model="short_desc" />
```

---

## Subdomain Components

### Guide (`components/guide/`)

```
guide/
├── pages/
│   ├── IndexPageComponent.vue       # Homepage
│   ├── AboutUsComponent.vue         # About
│   ├── SerchPageComponent.vue       # Search results
│   └── lists/
│       ├── OutdoorListComponent.vue # Outdoor spots
│       ├── MountaineeringListComponent.vue
│       ├── IceListComponent.vue
│       └── ...
├── items/
│   ├── cards/
│   │   ├── ArticleCardComponent.vue # Standard article card
│   │   └── ...
│   ├── navbars/NavbarComponent.vue
│   └── MetaDataComponent.vue
└── GuideMainComponent.vue
```

**Card structure (guide style):**
```html
<div class="food col-md-4">
  <div class="portfolio-img view view-first">
    <img ...>
    <div class="mask">
      <a class="info"><i class="fa fa-arrow-right"></i></a>
    </div>
    <div class="isotope-item-dtls">
      <h3>Title</h3>
      <div class="article_size">Description</div>
    </div>
  </div>
</div>
```

**Section header structure (guide style):**
```html
<h1 class="index_h2">Section Title</h1>
<div class="bar"><i class="fa fa-icon"></i></div>
<h3 class="article_list_short_description">Description</h3>
```

### Shop (`components/shop/`)

```
shop/
├── pages/
│   ├── MeinPage.vue             # Shop homepage
│   ├── ProductPage.vue          # Product detail
│   ├── CartPage.vue             # Shopping cart
│   ├── CheckoutPage.vue         # Checkout flow
│   └── SearchPage.vue           # Product search
├── items/
│   ├── cards/ProductCard.vue
│   └── ...
└── MainWrapper.vue
```

### Blog (`components/blog/`)

```
blog/
├── pages/
│   ├── IndexPage.vue
│   ├── pages/PostPage.vue
│   └── pages/NewsPage.vue
├── items/
│   └── modals/PostModal.vue
└── BlogMainComponent.vue
```

### Summit (`components/summit/`)

```
summit/
├── pages/
│   ├── IndexPage.vue               # QR scanner home
│   ├── AboutUsPage.vue
│   ├── SummitMapPage.vue
│   ├── MakeSummitAscentPage.vue    # Submit ascent form
│   ├── lists/SummitListPage.vue    # Public summit grid
│   └── pages/SummitPage.vue        # Summit detail
├── items/
│   ├── NavbarComponent.vue
│   ├── FooterComponent.vue
│   ├── MetaDataComponent.vue
│   └── cards/BigPostCardComponent.vue
└── SummitMainComponent.vue
```

### User Admin (`components/user/`)

```
user/
├── pages/
│   ├── articles/              # Guide article management
│   ├── summits/               # Summit management
│   │   ├── SummitListPage.vue
│   │   └── SummitFormModal.vue
│   ├── shop/                  # Shop management
│   ├── blog/                  # Blog management
│   ├── films/                 # Films management
│   └── user_management/       # Users, roles, permissions
├── items/
│   ├── data_table/            # TabsComponent system
│   ├── modal/                 # StackModal + content modals
│   ├── navbars/               # User panel navbar
│   ├── form/parts/editor/     # Quill editors
│   └── BreadcrumbComponent.vue
└── HomeComponent.vue
```

---

## Building for Production

```bash
npm run build    # produces versioned files in public/assets/js/ and public/assets/css/
```

Files referenced in `resources/views/*.blade.php` via Laravel Mix manifest.

**Never edit files in `public/assets/`** — they are compiled build outputs and will be overwritten on next build.

---

[Go back](../../README.md)
