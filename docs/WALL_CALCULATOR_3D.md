# Climbing Wall Price Calculator — Interactive 3D Viewer (three.js)

**Route:** `shop.climbing.ge/.../climbing_wall_colculator` (named route `wall_price_colculator`)
**Component:** `resources/js/components/shop/pages/pages/wall_colculator/ClimbingWallPriceColculatePage.vue`

This page lets a visitor configure a climbing wall (type, dimensions, safety mat,
holds, VAT) and see an **approximate, adjustable price estimate**. It is a public,
unauthenticated, frontend-only tool — it does not create an order or quote in the
backend.

This doc covers the 3D preview that replaced the old flat SVG sketch, how to build
it, and how to verify it after changes.

---

## Section 1 is a wizard, not a single picker

Section 1 used to be one flat grid of 6 wall-type cards. It's now a 3-step
progressive wizard, each step only appearing once the previous one is answered:

1. **Discipline** — Bouldering or Sport Climbing (`discipline` data field).
   Drives the price multiplier (`coepicients.disciplines`), the default
   height/width, the 10m height ceiling, and which 3D geometry path runs
   (vertical-then-overhang split vs. one continuous slant).
2. **Construction style** — Indoor / Outdoor / Stand-Free Indoor / Stand-Free
   Outdoor (`structure` data field, `coepicients.structures`). Indoor/Outdoor
   feed straight into sizing; either Stand-Free option unlocks step 3.
3. **Number of climbing sides** (stand-free only) — 1 to 4 (`sides_count`).
   A stand-free structure can have climbing faces on more than one side, like
   a real freestanding tower, each with its own width/depth sharing one height.

Only after all applicable steps are answered (`wizardComplete` computed) does
Section 2 (sizing) appear. `discipline` and `structure` replaced the old flat
`wall_type` string everywhere — there is no `wall_type` field left in the
component.

### Multi-side data model

Side 1 still lives directly in the existing `width`/`depth` fields (so nothing
that only ever dealt with a single wall needed touching). Sides 2-4 live in a
fixed-length `extra_sides: [{width, depth}, {width, depth}, {width, depth}]`
array, only as many of which are used as `sides_count - 1`. The computed
`all_sides` property is the single place both pricing and the 3D viewer read
the full side list from — always exactly `sides_count` entries, sharing one
`height`.

### Discipline-specific behavior

| | Sport climbing | Bouldering |
|---|---|---|
| Default size (only fills in a dimension the user hasn't typed yet) | height 6m, width 3m | height 3m, width 3m |
| Max height | none | height ≥10m bounces the wizard back to step 1 (discipline/structure/sides all cleared, with an on-screen explanation) rather than silently keep pricing a 10m+ wall as bouldering |
| Shape | one continuous slant from the floor (`buildWallModel`) | straight vertical base, then the overhang starts above it (`buildBoulderingModel`) — real bouldering-wall framing doesn't lean from the floor, see reference photos this was checked against. The straight section's height matches the actual safety mat height (`matHeightHint`), not an arbitrary fraction |
| Ground feature | two-strand belay rope + carabiner anchor, no mat | safety mat |

Stand-free structures (either discipline) additionally get a primary frame box
(on its own legs) + diagonal truss subframe behind the stud frame, matching the
reference steel-structure diagram — see `addStandFreePrimaryFrame` in
`wallGeometryBuilder.js`.

Minimum width and height are both **1.5m** (`MIN_SIZE` in
`wallGeometryBuilder.js`, and `min="1.5"` + blur-clamping on the form inputs) —
a wall smaller than that isn't a meaningful structure. Depth (overhang) has no
minimum; 0 is a valid, normal vertical wall.

### Multi-side tower rendering

`WallViewer3D.vue` doesn't have a separate "tower" code path — it calls the
exact same per-side model builder (`buildWallModel`/`buildBoulderingModel`) and
mesh builder (`buildWallMeshGroup`) once per entry in `allSides`, then wraps
each full assembly (panel + frame + holds) in a pivot `Group` rotated by
`index * (360 / sidesCount)` degrees around the shared Y axis. `sidesCount = 1`
rotates by 0° and offsets by radius 0, so this is a strict superset of the
original single-wall path, not a fork of it.

**Each side is also pushed outward from the shared axis before rotating**
(`group.position.z = radius` in `computeTowerRadius`), using the regular-polygon
apothem formula `radius = width / (2 * tan(pi / N))`. Skipping this (an earlier
version did) rotates every face in place around the axis itself, so all N
climbing surfaces end up clustered on top of each other near the center with
their support frames — offset behind their own panels — ending up outside the
cluster: construction facing out, climbing surfaces facing in. With the radius
offset, adjacent faces' corners land at the exact same point (verified by hand
for N=3 and N=4), forming a real closed polygon with frames hidden on the
inside, facing the shared mast, and climbing surfaces facing outward. A 2-sided
wall isn't a true regular polygon (`tan(pi/2)` is undefined), so it's treated
as two faces back-to-back with a small fixed spine gap instead.

To avoid visual clutter with 3+ faces: only side index 0 gets the human
reference figure and (for sport climbing) the belay rope; every side still
gets its own share of holds (split proportionally by that side's surface area,
the same pattern already used to split holds between a bouldering wall's two
segments). Mat, roof, and any extra support structure are tower-level, not
per-face — see below.

**Roof, mat, and extra structure are tower-level, not per-face.** A single
free-standing wall (`sidesCount === 1`) still gets the original per-wall roof,
mat, and primary-frame+truss add-on (`addStandFreePrimaryFrame`) exactly as
before. A multi-side tower gets none of those per face — calling them once per
side either crammed N overlapping box+truss structures into the small central
area (roof/frame) or left gaps at every corner (mat, since separate rectangles
never touch). Instead, for `sidesCount > 1`:
- `addTowerRoof` builds one flat cap kept tight to the tower's actual footprint
  (`radius + widest face's half-width + a small fixed eave margin`, `0.18m` —
  not a large fixed offset, which used to balloon disproportionately on small
  towers and effectively scale with overhang depth instead of the tower's own
  size). Each face also gets its own header/fascia panel (added inside the
  per-side loop in `rebuildModel`, inheriting that side's own position/rotation
  automatically) closing the gap between its own top edge and the shared roof —
  without it that gap read as an open hole rather than a finished structure.
  The single-wall roof (`sidesCount === 1`, in `wallGeometryBuilder.js`) got the
  same tight-eave + fascia treatment.
- `addTowerMat` builds one mat disc covering the whole footprint — gap-free by
  construction, matching the safety pad visible around real concrete climbing
  towers in reference photos. It's a faceted polygon, not a smooth circle
  (segment count = `sidesCount * 2`, so a real cut mat shows corners rather
  than looking like a perfect disc).
- The per-side stud frames meeting at the shared center are considered
  sufficient "extra construction" for a 3+ sided tower on their own (no
  `addStandFreePrimaryFrame`). A 2-sided tower is a special case: see below.

**Corner infill for overhanging towers.** Adjacent faces share their *bottom*
corner by construction (verified for N=3 and N=4 — see `towerCorner`), but if
either side overhangs, that same shared point pulls each side's *top* corner
in a different outward direction, opening a wedge-shaped gap that widens with
height. `addCornerInfills` (any N ≥ 2, including the 2-sided case) closes it
with one flat triangle per corner — from the shared bottom corner to each
side's own top corner — using the closed-form corner positions, not a fixed
shape. With no overhang on either neighboring side the two top corners
coincide too, so the triangle degenerates to zero area and nothing is drawn:
the fix applies itself correctly for any combination of per-side depths and
angles without a special case for "no overhang here." These triangles are real
climbing surface, not a cosmetic filler — each gets its own plywood/bolt-hole
texture (via `buildPanelTexture`, UV-mapped so the shared bottom corner lands
at the texture's bottom-center and the two top corners at its top-left/right),
sized to that specific gap's own width so it doesn't look stretched.

**2-sided towers are a deliberate special case**, not a 2-gon: `tan(pi/2)` is
undefined, so there's no real regular-polygon radius to compute. The two faces
sit back-to-back as close as `computeTowerRadius` allows while leaving room
for `addTwoSideSpine` — a small central mast + a few horizontal rails filling
the gap, so it reads as the shared structural core real double-sided towers
have (see the 2-side reference photo this was checked against) instead of
looking like a rendering gap.

### Holds: capacity and mounting fixes

Two bugs from earlier rounds are fixed:

- **Unbounded quantity.** `holds_total_quantity` used to be
  `wall_square * holds_per_sqm` with no ceiling, so a large wall with a high
  density slider could price (and try to render) tens of thousands of holds —
  physically impossible, since a wall only has as many bolt holes as its real
  21cm grid provides. `maxHolesAvailable` (a computed property on the page,
  summed across every side) now hard-caps both `colculate_holds_price()` and
  `colculate_total_price()`, and the UI shows "this wall has room for N bolt
  holes" next to the density slider.
- **Floating holds.** Holds were offset from the panel by
  `PANEL_THICKNESS / 2 + 0.045` — but the hold geometry itself (see
  `holdsInstancer.js`) already protrudes outward from its own `z=0` mounting
  face, so that `+0.045` double-counted the offset and left a visible gap
  between every hold and the wall. Fixed to `PANEL_THICKNESS / 2 + 0.002`
  (just enough to dodge z-fighting).

## Architecture

`viewer3d/wallGeometryBuilder.js` builds the wall model and its Three.js meshes:
a climbing-surface panel (one or two segments, see above) textured with a real
21cm bolt-hole grid and 1.5m plywood sheet-seam lines (`panelTexture.js`), a
stud-and-rail support frame behind it (8x8cm square lumber, aligned to the
plywood sheet seams/midpoints — every 0.75m, half the 1.5m sheet — not a fixed
handful of posts regardless of size, and no diagonal bracing: real
plywood-on-studs construction gets its shear strength from the sheathing
itself; plus a primary-frame+truss for a single stand-free wall), an optional
safety mat or two-strand belay rope, an optional outdoor roof, holds shaped
like real faceted pyramid/dome holds and snapped onto actual bolt holes
(`holdsInstancer.js`), and a human reference figure for scale.

```
wall_colculator/
├── ClimbingWallPriceColculatePage.vue   ← page; renders <WallViewer3D> with SVG fallback
├── coepicients_mixin.js                 ← pricing coefficients (unchanged by the 3D work)
└── viewer3d/
    ├── WallViewer3D.vue       ← Vue wrapper: canvas lifecycle, dynamic three.js import, dispose
    ├── sceneSetup.js          ← Scene/Camera/Renderer/Lights/OrbitControls plumbing
    ├── wallGeometryBuilder.js ← wall model + Three.js mesh builder (panel, frame, mat, rope, roof)
    ├── panelTexture.js        ← real 21cm bolt-hole grid + 1.5m plywood sheet-seam texture
    ├── holdsInstancer.js      ← places holds on actual bolt holes via InstancedMesh per shape variant
    └── humanFigure.js         ← ~1.7m low-poly human, for scale reference only
```

### Coordinate convention (read this before touching the geometry)

This bit was wrong in an earlier pass and produced a wall that leaned away from
the viewer like a ramp instead of overhanging toward them — worth getting very
explicit about it so it doesn't regress:

- The climber/viewer stands on the **+Z side**, looking toward -Z.
- The climbing surface's visible face points **+Z** (toward the climber).
- `depth` is the overhang lean distance: how far the **top** of the wall sticks
  out toward +Z (toward the climber) past vertical. `depth = 0` is a flat
  vertical wall. This is built once as `tiltAngle = atan2(depth, height)`
  applied as a single rotation to a pivot group containing the panel, frame,
  and holds — not faked per-part.
- The support frame (studs, rails — no diagonals, modeled after real
  plywood-on-studs construction, see the reference photos this was checked
  against) sits on the **-Z side**, behind the panel, hidden from the climber.
- The safety mat and the human reference figure stand on the ground at **+Z**,
  flush against the wall's base — the same side as the climbing face, not
  behind it.

**Three.js is loaded lazily.** `WallViewer3D.vue` does
`await import('three')` / `await import('three/examples/jsm/controls/OrbitControls.js')`
inside `mounted()` instead of a static top-level import (same pattern the project
already uses for `jsencrypt` in `LoginComponent.vue`). This keeps the ~150–300KB
Three.js bundle out of the route's own webpack chunk — it only downloads once a
visitor actually scrolls to the price panel on this calculator page.

**Fallback:** if WebGL is unavailable or the dynamic import throws, `WallViewer3D`
emits a `failed` event and the page falls back to the original SVG isometric
sketch (`wallViz` computed property) — the page never breaks, it just degrades.

**Cleanup:** because this is an SPA route (no full page reload between
navigations), `WallViewer3D.vue`'s `beforeUnmount()` disposes the renderer,
controls, geometries and materials, disconnects its `ResizeObserver`, and cancels
the render loop. Skipping this would leak WebGL contexts across a browsing
session — check this first if you see console warnings about too many active
WebGL contexts during development.

---

## Build / Deploy

Three.js is a normal npm dependency (`"three"` in `package.json`), nothing special
in `webpack.mix.js` was required — Webpack 5's default `resolve.extensions`
already covers the `.mjs`/ESM subpath imports Three.js's `examples/jsm/*` exports
need.

```bash
npm install              # picks up the `three` dependency
npm run dev               # or: npm run watch / npm run build (production)
```

Production build also runs as part of the normal deploy flow:

```bash
npm run build              # mix --production && sitemap + cache clear
```

To confirm Three.js is going into its own lazy chunk (not inlined into `app.js`
or into this route's own chunk) after any change to the viewer code:

```bash
npx mix --production
ls public/assets/js/ | grep -i three
# expect: node_modules_three_build_three_module_js.js
#         node_modules_three_examples_jsm_controls_OrbitControls_js.js
grep -c "OrbitControls" public/assets/js/app.js   # expect: 0
```

No database migrations, no backend routes, no `.env` changes are needed for this
feature — it's frontend-only.

---

## Manual Verification

### 1. Local dev server

```bash
php artisan serve --host=127.0.0.1 --port=8123 &
npm run watch
```

The shop subdomain (`shop.climbing.loc` per `.env`'s `SHOP_URL`) needs to resolve
to wherever the app is being served. If `/etc/hosts` doesn't already map it (check
for a commented-out `shop.climbing.loc` line), either uncomment/add it, or use a
browser/tool that supports host-resolver overrides (e.g. Chrome's
`--host-resolver-rules="MAP shop.climbing.loc 127.0.0.1"`) instead of editing the
system hosts file.

### 2. Headless-browser screenshot check (used for this feature's own verification)

This container had no headless browser installed. Setup that worked:

```bash
npx playwright install chromium      # downloads the browser binary, no sudo needed
sudo npx playwright install-deps     # installs system shared libs (libatk, libnss, etc.) — needs sudo
```

The `install-deps` step needs root (apt) and will prompt for a password if sudo
isn't passwordless in your shell — run it yourself if an agent/CI user can't enter
one. Once both steps succeed, a minimal Playwright script can `nav` to the
calculator route, fill in width/height/depth, and `screenshot` to confirm:

- a 3D `<canvas>` renders in the price panel instead of the flat SVG
- dragging orbits the camera (`OrbitControls`)
- the model rebuilds live as width/height/depth change
- `console --errors` (or `page.on('console')`) reports nothing

### 3. What to manually click through

1. Open the wall calculator page.
2. Pick any wall type and enter width + height — a vertical panel with a visible
   stud frame, footings, and a person standing beside it should appear.
3. Increase Depth (Overhang) from 0 — the **top** of the panel should swing
   toward the camera/viewer (overhanging over the climbing side), not away from
   it like a ramp. If it leans away, the tilt sign regressed — check
   `wallPivot.rotation.x` in `wallGeometryBuilder.js` is `+tiltAngle`.
4. Drag on the canvas — the camera should orbit (desktop mouse and touch).
   Orbiting to the back should reveal the support frame; it should be entirely
   hidden behind the panel from the front.
5. Toggle "Add Climbing Holds" — colored holds should appear scattered on the
   *front* (climber-facing) surface, not the back.
6. With a safety mat showing (wall height ≤ 5m), the mat should sit flush
   against the wall's base, on the same side as the climbing face — not
   floating near the top or behind the wall.
7. For an outdoor wall type, a roof slab + two support posts should appear
   above the leaning top edge.
8. Change width/height/depth — everything (panel, frame, mat, holds, roof,
   human) should resize/reposition live with no flicker or duplicated meshes
   left behind (watch the browser's WebGL context count in `chrome://gpu` or
   devtools if debugging a leak).
9. Confirm the price breakdown panel still computes sensible totals — wall area
   is now `width × slant_length` (the true overhang surface area), not
   `width × height × depth`.
10. Pick a bouldering type — height/width should default to 3m/3m if you
    hadn't typed them, and the 3D view should show a short straight section at
    the floor (matched to the mat's height, not a big chunky 30%-of-height
    block) before the overhang kicks in. Try to type a height ≥ 10m: the
    bouldering cards should grey out and refuse the click; if a bouldering
    type was already selected, raising the height past 10m should silently
    deselect it.
11. Pick a sport climbing type — height/width should default to 6m/3m if you
    hadn't typed them, the Safety Mat section should disappear entirely, and
    the 3D view should show two thin rope strands meeting at a carabiner near
    the top, dropping straight down, instead of a mat.
12. Try to type a width or height below 1.5 and blur the field — it should
    snap back up to 1.5, not stay below the structural minimum.
13. Zoom into the climbing surface — you should see a fine grid of small dark
    bolt holes exactly 21cm apart, with slightly darker seam lines every 1.5m
    marking individual plywood sheets. Toggle holds on — each rendered hold
    should sit exactly on one of those holes, never floating between them, and
    should vary between a few distinct rounded/flat/pinch shapes rather than
    all being identical.
14. Pick a stand-free wall type — behind the usual stud frame you should see a
    separate box-shaped frame standing further back on its own legs, with a
    thick diagonal truss running from its base up to the top of the panel.

---

## Roadmap

This is a multi-phase plan (full plan kept in the repo's planning history).
Phase 1 (Three.js foundation) shipped, and Phase 3 (holds) and Phase 5 (outdoor
roof) were pulled forward early because a single flat panel didn't read as a
real wall without them. Phase 2 partially shipped too — bouldering walls now
get the vertical-then-overhang split (see "Discipline-specific behavior"
above) — but a free-form segment *editor* (user stacking arbitrary vertical /
slab / overhang / roof pieces) is still outstanding. Still outstanding:

- **Phase 2 (remainder)** — a segment editor for combining more than the fixed
  two pieces bouldering walls get today.
- **Phase 4** — 2D technical drawings (front / side / construction with
  dimension callouts), generated from the same model via SVG projection, not a
  second WebGL render.
- **Phase 6 (lower priority)** — top-down floor-plan "map" view showing the
  wall's footprint in a room/yard.

Already shipped ahead of schedule:
- Support frame (posts, rails, diagonal braces) behind the panel, including a
  seam rail at the bouldering vertical/overhang kink.
- Safety mat flush against the wall base (sport climbing walls get a belay
  rope + anchor instead — they don't use crash pads).
- Holds scattered on the climbing face (`THREE.InstancedMesh`, capped at 600
  rendered instances regardless of priced quantity; split proportionally by
  area across both segments on bouldering walls).
- Outdoor roof + support posts.
- A ~1.7m human figure (`humanFigure.js`) standing on the mat (or the ground,
  if there's no mat) purely for scale reference — not priced, not configurable.
- Discipline-aware defaults and constraints (default heights, the 10m
  bouldering cutoff, 1.5m minimum width/height).
