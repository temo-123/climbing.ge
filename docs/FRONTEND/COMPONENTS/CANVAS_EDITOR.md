# Canvas Editor — Frontend Documentation

The canvas editor is a Paper.js-based drawing tool embedded in the admin panel. It is used in three contexts: **sector local images**, **climbing routes**, and **MTP pitches**. All three share the same editor component tree.

---

## Table of Contents

- [Component Tree](#component-tree)
- [Actions (Tool Modes)](#actions-tool-modes)
- [Toolbar](#toolbar)
- [Style Controls](#style-controls)
- [Layers Panel](#layers-panel)
- [Paper.js Architecture Notes](#paperjs-architecture-notes)
- [Canvas Actions Reference](#canvas-actions-reference)
- [Events Flow](#events-flow)
- [Save Flow — Per Context](#save-flow--per-context)
- [Crop Tool](#crop-tool)
- [Keyboard Shortcuts](#keyboard-shortcuts)
- [Common Pitfalls](#common-pitfalls)

---

## Component Tree

```
EditorComponent.vue                   ← top-level: toolbar + style bar + layout
├── ToolbarComponent.vue              ← action buttons, group labels, active tool badge
├── CanvasContainerComponent.vue      ← thin pass-through wrapper
│   └── CanvasManager.vue             ← Paper.js setup, image loading, history, zoom
│       ├── mixin: DrawingTools.vue   ← all shape-drawing methods (line, rect, circle…)
│       └── mixin: CanvasHandlers.vue ← all mouse-event handlers per action
└── LayersPanelComponent.vue          ← layers list with lock/visibility/reorder/delete
```

**Key rule:** `CanvasManager` is a Vue component that mixes in `DrawingTools` and `CanvasHandlers`. Both mixins run in the context of the `CanvasManager` instance, so `this` inside them is the `CanvasManager` component.

---

## Actions (Tool Modes)

The active tool is an integer `action` prop passed down from `EditorComponent` through `CanvasContainerComponent` to `CanvasManager`. Mouse event handlers in `CanvasHandlers.vue` branch on `this.action`.

| Action | Constant | Tool |
|---|---|---|
| 1 | `line` | Freehand line |
| 2 | `point` | Dot / point |
| 3 | `number` | Numbered route (group: text + line + dot) |
| 4 | `rectangle` | Rectangle |
| 5 | `eraser` | Delete item on click |
| 7 | `combined` | Combined: number + line + dot (group) |
| 8 | `move` | Move single item (drag) |
| 9 | `pan` | Pan / scroll the canvas |
| 10 | `circle` | Circle (drag to size) |
| 11 | `ellipse` | Ellipse (drag to size) |
| 13 | `text` | Text label |
| 14 | `selection` | Rubber-band multi-select + drag to move |
| 15 | `crop` | Crop view (zoom canvas to selection) |
| 16 | `edit-points` | Drag individual path segment points |
| 17 | `move-all` | Move all unlocked objects together |
| 19 | `resize` | Resize a single shape (click-drag quadrant) |
| 20 | `continue-line` | Extend an existing line from its nearest endpoint |
| 21 | `arrow` | Directional arrow (drag from start to end, solid triangular head) |
| 22 | `rappel` | Rappel / lower-off marker (click to place; fixed circle + shaft + chevron symbol) |
| 23 | `bolt` | Bolt marker (click to place; fixed "X" symbol) |
| 24 | `pin` | Peg / pin / piton marker (click to place; fixed ring + diagonal shaft symbol) |
| 25 | `pendulum-left` | Pendulum marker, swinging left (click to place; fixed hollow circle + shaft + swing arc + chevron symbol) |
| 26 | `pendulum-right` | Pendulum marker, swinging right (mirror of 25) |
| 27 | `crux` | Sequence / crux-pitch marker (click to place; fixed "key" symbol — bow + shaft + teeth) |

---

## Toolbar

**File:** `resources/js/components/user/items/canvas/assets/toolbar/ToolbarComponent.vue`

Groups of buttons separated by `<div class="vr">` dividers. Each group has a small label below it ("Draw", "Edit", "Navigate", "Export"). An **active tool badge** at the right shows the current mode name and color-codes it (primary = draw, warning = edit, secondary = navigate).

**Props:**

| Prop | Type | Default | Description |
|---|---|---|---|
| `action` | Number | 1 | Current active action integer |
| `historyLength` | Number | 0 | Undo stack depth (badge shown when > 0) |
| `redoLength` | Number | 0 | Redo stack depth |
| `hasDrawing` | Boolean | false | True when the canvas has any drawn items |
| `hasUnlockedDrawing` | Boolean | true | True when at least one drawn item is unlocked |

**`hasUnlockedDrawing` vs `hasDrawing`:**
- `hasDrawing` gates: eraser, edit-points, continue-line
- `hasUnlockedDrawing` gates: move, move-all, selection, resize — these are disabled when ALL items are locked

---

## Style Controls

Located in `EditorComponent.vue` directly below the toolbar (visually attached as a bottom bar).

| Control | Description |
|---|---|
| Stroke color picker | Color of lines, borders |
| Fill toggle + picker | Enable/disable fill; disabled = `null` fill (transparent) |
| Width slider (1–20) | Stroke width in px |
| Zoom display + reset | Shows current zoom %; click to reset to 1:1 |

Colors are pushed to `CanvasManager` via `this.$refs.canvasContainer.updateColors(stroke, fill, width)` where `fill = null` means no fill.

**`fillEnabled` flag:** `EditorComponent.data.fillEnabled` controls whether fill is active. When `false`, the computed `fillColor` returns `null`, which propagates as `null` fill color to `DrawingTools`. Check `DrawingTools.vue` for how `null` fill is handled (it skips setting `fillColor` on the Paper.js item).

---

## Layers Panel

**File:** `resources/js/components/user/items/canvas/assets/layers/LayersPanelComponent.vue`

Single-line rows, one per canvas item. Structure left→right:

```
[↑/↓ reorder] [type icon] [name (editable)] [color swatch] [size input] [spacer] [action buttons]
```

**Layer data shape** (built in `EditorComponent.updateLayersList()`):

```javascript
{
    id: Number,          // Paper.js item.id
    name: String,        // Raw Paper.js item name (e.g. "line 3")
    displayName: String, // Formatted for display
    color: String,       // CSS hex color of stroke/fill
    strokeWidth: Number,
    visible: Boolean,
    locked: Boolean,     // item.locked
    layerName: String,   // Paper.js Layer name ('main', 'related-0', …)
    isGroup: Boolean,
    isRelated: Boolean,  // true for reference/related route layers
    isText: Boolean,
    textContent: String | null,
    expanded: Boolean,   // group expansion state
    children: Array      // only for groups
}
```

**Lock visual:** Locked rows get `layer-locked` CSS class → text strikethrough, row dimmed. The move/resize/selection toolbar buttons are also disabled via `hasUnlockedDrawing`.

**Reorder:** Up/Down arrows call `moveLayerUp` / `moveLayerDown` in `EditorComponent`, which reorders Paper.js items within the same layer and re-emits `layers_updated`.

---

## Paper.js Architecture Notes

### Layers

Paper.js uses named layers internally:

| Layer name | Purpose |
|---|---|
| `background` | Locked raster image (the photo) |
| `main` | All user drawings |
| `related-0`, `related-1` … | Other routes drawn on the same image (tinted colors, locked, shown for reference) |

### Vue 3 Proxy + Paper.js — Critical

**Do NOT store Paper.js objects in `data()`.** Vue 3 wraps `data()` values in a reactive Proxy. Paper.js matrix/transform operations break silently when accessed through a Proxy (`.position.add()` setter chains fail).

**All Paper.js item references are stored as plain instance properties in `mounted()`:**

```javascript
// CanvasManager.vue mounted()
this.selectedItem      = null;   // ← NOT in data()
this.group             = null;
this.editingSegment    = null;
this.editingSegmentDot = null;
this.panLastScreen     = null;

// CanvasHandlers.vue mounted()
this.path                  = null;
this.currentLine           = null;
this._multiSelectedItems   = [];
this._selectedResizeItem   = null;
this._resizeHandle         = null;
this._resizeStart          = null;
this._resizeOriginalBounds = null;
```

Only **primitive flags** (`Boolean`, `Number`, `null`) are safe in `data()`.

### Layer Boundary Bug (Fixed)

`paper.Layer` is a subclass of `paper.Group`. Any bubble-up loop like:

```javascript
while (item.parent instanceof paper.Group) { item = item.parent; }
```

…will climb all the way to the main Layer and translate every item on the canvas. The correct check:

```javascript
while (item.parent &&
       !(item.parent instanceof paper.Layer) &&   // ← stop here
       item.parent instanceof paper.Group &&
       !item.parent.locked) {
    item = item.parent;
}
```

This pattern is used in `selectItemForMove`, `startResizeOrSelect`, and anywhere bubble-up is needed.

### Canvas Sizing — Portrait vs Landscape

In `loadBackgroundRaster` (`CanvasManager.vue`):

- **Landscape** (naturalW ≥ naturalH): canvas fills full container width (`width: 100%` CSS), height calculated proportionally.
- **Portrait** (naturalH > naturalW): height capped at 80% of `window.innerHeight` to avoid excessive scrolling, width calculated proportionally and set as inline style.

`canvasWidth = 0` means "use CSS 100%". `canvasWidth > 0` means portrait mode, applied as inline `width` style.

---

## Canvas Actions Reference

### Move Item (action 8)

`selectItemForMove(event)` → `hitTest` → set `this.selectedItem` → **on drag**: `selectedItem.translate(event.delta)`. Locked items emit a toast warning instead of moving. `selectItemForMove` is called on `mouseDown`, not `mouseUp`, so the item is selected immediately before dragging.

Unlike `startResizeOrSelect`/`dragResize` below, this does **NOT** generally bubble up to the topmost Group — a numbered-route `isLayerGroup` only organizes its line + number label for the Layers panel, and each is meant to stay independently movable, so hitting the line moves just the line. The one exception: hitting a child path that belongs to one of the atomic multi-part symbols (Arrow/Rappel/Bolt/Pin/Pendulum/Crux — see `DrawingTools._isAtomicMarkerGroup`) bubbles up to that symbol's own Group before being assigned to `selectedItem`. Without this, dragging (say) a bolt's `diag1` translated only that one line, visibly tearing the "X" apart and leaving `diag2` behind — `erase_at_point` has the identical bubble-up for the same reason (erasing one child of an atomic symbol must erase the whole thing, not leave orphaned pieces).

### Move All (action 17)

`_moveAllActive = true` on mouseDown. On drag: iterates all items in non-locked, non-background layers and calls `item.translate(event.delta)` on each. Skips locked items.

### Resize (action 19)

Single click-drag UX (no two-click):
1. `mouseDown` → `startResizeOrSelect`: hitTest → select item → determine resize handle from **quadrant** (top-left, top-right, bottom-left, bottom-right of the shape's bounding box).
2. `mouseDrag` → `dragResize`: recompute bounds from `_resizeOriginalBounds + delta`, update shape.
3. `mouseUp` → save, clear `_resizeHandle`.

Circles: `_recreateCircle` creates a new `paper.Path.Circle` to replace the old one (Paper.js doesn't support bounds-based resize for circles directly). Use `let item` (not `const`) since the circle reference changes.

### Multi-Select (action 14)

- Rubber-band: draws a dashed rectangle; on mouseUp, selects all items whose bounds intersect the rectangle.
- Drag on existing selection: moves all `_multiSelectedItems` together.

### Continue Line (action 20)

`startContinueLine`: finds the nearest segment endpoint (within 20px) on any non-locked path. If found, activates the line from that endpoint. If not found, starts a new line.

### Arrow (action 21)

Drag-to-define: `add_arrow` seeds a degenerate shaft+solid-triangle-head Group at `mouseDown`, `updateArrow` repositions both on every `mouseDrag` frame from the fixed start point to the current point. The Group is tagged `data: { isArrow: true }` (not `isLayerGroup`) so it shows in the layers panel as one item, not an expandable folder — see `EditorComponent._isArrowContainer` and its use in `_getItemColor`/`_setItemColor`/`_getItemWidth`/`_setItemWidth`. The layers-panel width input calls `resizeArrow` (rescales shaft width + recomputes head geometry together); the canvas's own drag-resize (action 19) uses the generic `item.fitBounds(...)` fallback instead, since an arrow isn't flagged `isCircle`/`isRectangle`.

### Rappel Marker (action 22)

Click-to-place (no drag step): `add_rappel` builds a solid circle + downward shaft + open chevron as one Group, proportioned from a 30px-radius reference design and scaled by the **same dot-size control `add_point` uses** (`R = dotSize`, default 4; `k = R / 30`) — no separate size control, and the existing size slider resizes a placed-but-not-yet-saved rappel marker exactly the way it already resizes a point. Defaults to green (`#22C55E`, matching the reference design) rather than the shared stroke color — same reasoning as `add_point`'s gold default — but an explicitly-set fill color still overrides it, and it can be recolored afterward via the layers panel like anything else. Represents the standard topo symbol for a rappel/lower-off station — the circle is the anchor/bolt point the user clicks on. Tagged `data: { isRappel: true }`, following the **same pattern as Arrow above** (own container-type check, single non-expandable layers-panel row, `fa-anchor` icon) rather than the generic `isLayerGroup` grouping used by the numbered-route tool — see `EditorComponent._isRappelContainer`. Layers-panel width changes go through `resizeRappel` (replaces the circle via `Item.replaceWith()` since Paper.js can't resize a `Path.Circle`'s bounds directly, then repositions the shaft/chevron segment points — all keeping the circle's center fixed); canvas drag-resize (action 19) again falls through to the generic `fitBounds`.

### Topo Symbols group: Bolt / Peg-Pin-Piton / Pendulum (Left/Right) / Sequence-Crux Markers (actions 23-27)

Rappel plus these five more click-to-place topo symbols live in their own **"Topo Symbols"** toolbar group (`ToolbarComponent.vue`), separate from the general "Draw" group — a `vr` divider + `tool-group` block with its own `topo_symbols_group_label`, following the same layout pattern as the History/Draw/Edit groups above it. Every group-separating `vr` divider (not just this one) also carries `mx-2` for visible breathing room between groups — previously they only had vertical margin (`my-1`), so adjacent groups' buttons visually ran together.

Each is a Group of plain paths (no fill except where noted) proportioned from a fixed reference design and scaled by the same dot-size control (`R = dotSize`, `k = R / 30`), tagged with its own `data.isXxx` flag (`isBolt`/`isPin`/`isPendulum`/`isCrux`) rather than `isLayerGroup`, so each shows as one non-expandable layers-panel row. All five (bolt/pin/pendulum-left/pendulum-right/crux) share one stroke-width constant, `DrawingTools._markerStroke() = 12`, so they read as one consistent visual family regardless of dot-size setting, rather than each having its own ad-hoc thickness. Unlike Arrow/Line, they also all default to green (`DrawingTools._markerColor()`, `#22C55E` unless `currentFillColor` is explicitly set) — same reasoning as Rappel: a fixed topo symbol shouldn't silently inherit whatever red/etc. the stroke picker happens to be set to.

Where a symbol has a hollow ring/circle joined to a straight shaft (Pin, Crux), the shaft's endpoint is computed as *exactly one ring-radius along the shaft's own direction from the ring's center* (`shaftEnd = center + direction.normalize() * radius`), rather than an approximated fixed offset — this guarantees a seamless tangent join with no visible gap at any size, since a fixed-offset approximation leaves a gap that scales right along with everything else (so it never "grows out of" being noticeable). The same `dir`/`perp` vector approach is reused for Crux's teeth so they're always exactly perpendicular to the shaft.

- **Bolt** (`add_bolt`/`resizeBolt`): two crossing strokes forming an "X", built and resized around the group's own centroid (which is the click point for a symmetric X). Uses its own bolder stroke reference, `_boltStroke() = 16` (vs the shared `_markerStroke() = 12` the hollow-ring markers use, which is capped lower so their rings don't collapse into blobs — a bolt has no such ceiling) and `strokeCap: 'square'` rather than `'round'`, since a round cap's semicircular bulge past each arm's endpoint becomes an obviously soft/blobby tip once the whole symbol is scaled up; square caps stay crisp and geometric at any size. `fa-times`.
- **Peg/pin/piton** (`add_pin`/`resizePin`): a hollow ring (the eye a biner clips into, radius `16k`) plus a diagonal shaft (the blade), read together as a stylized cursive "P". The click point is the shaft's tip, not the ring — `resizePin` keeps that tip fixed and replaces the ring (`Item.replaceWith()`, same reason as `resizeRappel`) while repositioning the shaft's segment points. `fa-thumb-tack`.
- **Pendulum, left/right** (`_buildPendulum(event, dir)` shared by `add_pendulum_left`/`add_pendulum_right`, resized by one shared `resizePendulum`): a **filled** anchor dot (matching Rappel's solid anchor point, not hollow) + short shaft + a curved swing path ending in an open chevron. The swing curve is built by `_buildSwingCurve(start, end, dir, pull)` as an explicit 2-segment Bezier (via `paper.Segment`'s `handleIn`/`handleOut`) rather than a 3-point circular arc (`paper.Path.Arc`, used in an earlier iteration) — a 3-point arc gives no control over how far the resulting circle sweeps, and the 3 reference points used previously happened to define a circle the path wrapped ~167° around, rendering as a tight spiral/hook glued to the dot instead of a shallow pendulum swing. The Bezier is fully controlled instead: `handleOut` at the start point continues straight down (matching the shaft's own direction) and `handleIn` at the end point is exactly horizontal in the swing direction, so the curve always reads as a clean "drop then swing sideways" hook regardless of size. The chevron itself is built by a shared `_arcArrowhead(curve, headLen, headAngle)` helper that reads the curve's actual end tangent via `Path#getTangentAt` (works the same for a Bezier as for a circular arc) and constructs the arrowhead from that — the same construction `add_arrow`/`updateArrow` use for a straight line, just fed a curve tangent instead of a fixed line angle. `dir` (`-1` left / `+1` right) is the only difference between the two toolbar buttons/actions (25/26) and is stored on the group as `data.direction` so `resizePendulum` can read it back and rebuild the correctly-mirrored geometry — there's no separate `resizePendulumLeft`/`Right`. `fa-reply` (left) / `fa-share` (right) — their built-in curved-hook shape already reads as a swing direction. **Resize is deliberately non-proportional**, unlike every other marker on this page: `resizePendulum` scales the shaft/swing-curve/chevron fully with `k` (same as always), but the anchor dot's radius is heavily damped — `R = _pendulumDotBaseR() + (k*30 - _pendulumDotBaseR()) * _pendulumDotGrowth()` (base `4`, growth factor `0.35`) — so enlarging an existing pendulum grows the swing shape a lot while the dot only grows a little, instead of the whole glyph just scaling up uniformly like a bigger version of Rappel/Bolt/Pin/Crux.
- **Sequence/crux-pitch** (`add_crux`/`resizeCrux`): a "key" — hollow bow (ring, radius `16k`, the part a keyring passes through) + shaft (the blade) + **two teeth of different lengths cutting into the same side of the shaft** near the tip (the bit). The original version had two ticks straddling both sides of the shaft, which read as random hatch marks rather than a key; real key teeth all cut into one side of the blade at varying depths, so both teeth here extend only in the `perp` direction (one `14k` long, one `9k`, closer to the tip) rather than symmetrically. `resizeCrux` keeps the bow's center fixed, replacing the bow and repositioning the shaft/teeth. `fa-key`.

All five fall through to `EditorComponent`'s combined arrow/rappel/fixed-marker branches in `_getItemColor`/`_setItemColor`/`_getItemWidth`/`_setItemWidth` (via a shared `_isFixedMarkerContainer` helper), and to the generic `fitBounds` fallback for canvas drag-resize (action 19), same as Rappel.

### Crop (action 15)

1. User drags to draw orange dashed rectangle.
2. On mouseUp: canvas view **zooms and pans** to show only the crop area (`view.zoom = min(viewW/boundsW, viewH/boundsH)`, `view.center = bounds.center`).
3. Emits `crop-save` event with a JPEG dataURL of the cropped composite.
4. Shows toast: "Crop applied. Press Save to upload to server."
5. Does **not** download anything. Does **not** modify the Paper.js project data.

---

## Events Flow

```
CanvasHandlers / CanvasManager
  $emit('canvas_data', json)      ← on every saveCanvasData() call
  $emit('layers_updated')         ← after any change to the layer structure
  $emit('layers_ready')           ← once on mount
  $emit('history-changed', h, r)  ← undo/redo stack lengths
  $emit('zoom-changed', zoom)     ← after zoom change
  $emit('crop-save', dataUrl)     ← after crop is applied

CanvasContainerComponent
  ← passes all events up transparently

EditorComponent
  ← handleCanvasData → $emit('canvas_data', event)   ← parent listens here
  ← handleCropSave   → $emit('crop-save', dataUrl)   ← parent saves if it wants
  ← updateLayersList  → rebuilds this.layers from Paper.js project
```

---

## Save Flow — Per Context

### Sector Local Images

**Component:** `sectorLocaleImageEditorComponent.vue`
**Endpoint:** `POST /api/set_sector/set_sector_local_images/save_canvas_data/{image_id}`

Payload:
```json
{
    "canvasData":    "<paper.js JSON string>",
    "sectorId":      123,
    "edited_image":  "<base64 JPEG of composite>",
    "canvas_width":  800,
    "canvas_height": 600
}
```

The save function:
1. Calls `canvasContainer.getCleanJson()` for the Paper.js JSON (excludes background and related layers).
2. Resets zoom/pan to 1:1, hides background and related layers.
3. Re-imports related layouts with original (non-tinted) colors.
4. Captures the canvas as PNG strokes-only.
5. Composites background photo + strokes via `compositeImages()`.
6. POSTs all data.

Backend (`SectorLocalImagesController@save_canvas_data`):
- Backs up original image once to `images/sector_local_img/origin_img/`.
- Overwrites main image with composite.
- Upserts `sector_local_images_jsons` by `(sector_local_image_id, sector_id)`.

### Climbing Routes

**Component:** `CanvasRouteEditorComponent.vue`
**Controller:** `RouteJsonController`
**Models:** `ClimbingRoutesJson` — `(route_id, sector_image_id, json)`

Each route stores its own JSON. Multiple routes can be drawn on the same sector image; `related-N` layers show the other routes as reference.

### MTP Pitches

**Component:** `CanvasPitchEditorComponent.vue`
**Controller:** `MTPPitchController`
**Model:** `MtpPitchJson` — `(mtp_pitch_id, sector_image_id, json)`

Routes `POST /set_mtp_pitch/save_pitch_drawing/{pitch_id}` and `DELETE /set_mtp_pitch/del_pitch_drawing/{pitch_id}`.

### Canvas Deletion Cleanup

**Service:** `app/Services/CanvasService.php`

Call these before deleting a sector image to avoid orphaned JSON records:

```php
// When deleting a sector image (sector_images table row):
CanvasService::deleteSectorImageCanvasData($sectorImageId);

// When deleting a sector local image (sector_local_images table row):
CanvasService::deleteSectorLocalImageCanvasData($sectorLocalImageId);
```

---

## Keyboard Shortcuts

Handled in `EditorComponent.mounted()` via `window.addEventListener('keydown', ...)`.

| Shortcut | Action |
|---|---|
| `Ctrl+Z` | Undo |
| `Ctrl+Y` / `Ctrl+Shift+Z` | Redo |
| `Delete` / `Backspace` | Erase selected item (if eraser active) |

---

## Common Pitfalls

| Pitfall | Fix |
|---|---|
| Storing Paper.js objects in `data()` | Put them in `mounted()` as plain instance properties |
| Bubble-up loop climbing to Layer | Add `!(item.parent instanceof paper.Layer)` to the while condition |
| `const item` in `dragResize` for circles | Use `let item` — circle recreation reassigns the variable |
| Portrait images making canvas very tall | `loadBackgroundRaster` caps height at 80vh and sets `canvasWidth` inline |
| Move / Resize buttons not responding when all items locked | `hasUnlockedDrawing` computed in EditorComponent gates these buttons |
| Duplicate JSON records per sector | `SectorLocalImagesJson` uses `updateOrCreate` on `(sector_local_image_id, sector_id)` |

---

[Back to Frontend docs](../FRONTEND.md)
