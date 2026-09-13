# Canvas Editor — Frontend Documentation

The canvas editor is a Paper.js-based drawing tool embedded in the admin panel. It is used across **five host pages/modals** — **sector local images**, **spot rock images**, **climbing routes**, **MTP pitches**, and the unified **sector routes-drawing page** (which itself covers both routes and pitches) — listed in full under [Host Pages / Modals](#host-pages--modals). All five share the same underlying editor component tree, and (since the September 2026 unification) the same two host-side mixins for the two features that used to be copy-pasted per page: the item-name-label/legend overlay and "extra drawing" annotation layer. See [Host Pages / Modals](#host-pages--modals) for exactly what each host wires up.

---

## Table of Contents

- [Component Tree](#component-tree)
- [Host Pages / Modals](#host-pages--modals)
- [Shared Host Mixins](#shared-host-mixins)
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

## Host Pages / Modals

Every host below renders `<Editor ref="editorComponent" :disable_auto_legend="true" .../>` and wires up `canvasExtraDrawingMixin` (see [Shared Host Mixins](#shared-host-mixins)). "Extra drawing" here means the toggleable general-annotation layer tied to the shared background *image* rather than to any one sector/route/pitch — see [Save Flow — Per Context](#save-flow--per-context) for its own endpoints.

| Host | File | Item being drawn | Extra drawing | Create item-name label |
|---|---|---|---|---|
| Sector Local Image editor | `pages/sector_local_images/sectorLocaleImageEditorComponent.vue` | One layout per sector, on a shared "local approach photo" | ✅ | ✅ |
| Spot Rock Image editor | `pages/articles/spotRockImageEditorComponent.vue` | One drawing per sector, on a shared spot-rock photo | ✅ | ✅ |
| Route drawing modal | `pages/routes_and_sectors/routes/assets/CanvasRouteEditorComponent.vue` | One route's own drawing, on a sector image | ✅ | ❌ |
| Sector routes-drawing page | `pages/routes_and_sectors/routes/sectorRouteDrawingsEditorComponent.vue` | A route's drawing, **or** (independently) an MTP pitch's drawing, on a sector image | ✅ (available in both route and pitch mode — keyed by `selectedImageId`, the shared sector image, so it works the same regardless of which mode picked that image; toggling it exits whichever mode was active and restores it on toggling back off) | ❌ |
| Pitch drawing modal | `pages/routes_and_sectors/MTP/pitchs/Modals/CanvasPitchEditorComponent.vue` | One MTP pitch's own drawing, on a sector image | ✅ | ❌ |

**"Create item-name label" is deliberately sector-only** — the button is literally titled "Add/Update Sector Label" (`admin.articles.sector_local_image_editor.add_sector_label_btn`), and a route/pitch already gets its own name baked into every one of its layer rows via the `route_name` prop (see [Layers Panel](#layers-panel)), so a separate draggable "sector label" doesn't apply there. Route/pitch hosts still use `canvasOverlaysMixin` for the combined-legend preview (hence still needing `disable_auto_legend`), just without `itemLabelConfig`/`_currentItemLabelName()`/`relatedItemNames` or the button — the mixin's `computeEditorLabels()` simply never finds anything to render for these three.

All five render `<CanvasOverlaysComponent>` (see below) as a sibling of `<Editor>`, inside a `position-relative` root wrapper carrying `ref="canvasOverlayAnchor"`.

**Button row layout** — two flex groups, `justify-content-between`, identical on all five hosts:

- **Left group:** Toggle extra-drawing mode, then Add/Update Sector Label (sector hosts only).
- **Right group:** Save, then Delete, then the save-status text/badge.

**Button color convention** (per [Bootstrap 4 buttons](https://getbootstrap.com/docs/4.0/components/buttons/)):

| Action | Class |
|---|---|
| Save | `btn-success` (solid) |
| Toggle extra-drawing mode | `btn-success` off / `btn-info` on — active/on state is `btn-info`, inactive/off state is `btn-success`, by explicit request (not the usual "solid = on" toggle convention used elsewhere in this app) |
| Delete | `btn-danger` (solid) — every host now has one, including `sectorLocaleImageEditorComponent.vue`/`spotRockImageEditorComponent.vue`, which previously only offered deletion via the per-row ✕ in the layout list; see `deleteCurrentDrawing()` in both, which deletes the extra drawing or the currently-selected layout depending on mode |
| Add/Update Sector Label (sector hosts only) | `btn-warning` (solid) |
| Back / neutral row actions (reorder, layout list "New") | `btn-secondary` |
| Open/close a fullscreen editor modal | `btn-primary` closed / `btn-secondary` open — NOT `btn-danger`, since closing isn't a destructive action |

---

## Shared Host Mixins

Two mixins under `resources/js/components/user/items/canvas/mixins/` extract behavior that used to be copy-pasted (and drift out of sync) across every host page above. Both are plain host-contract mixins — the host component supplies a small set of data fields/computed/method hooks, documented in full in each mixin file's own header comment.

### `canvasOverlaysMixin.js`

Item-name label + leader-line overlay, and the combined topo-symbol legend preview, drawn OVER the live Paper.js canvas as a plain SVG/canvas overlay (never a real Paper.js item — see its own header comment for why). Supplies:

- `data()`: `editorLabels`, `labelsClipStyle`, `legendPreviewStyle`, `legendClipStyle`
- `mounted()`/`beforeUnmount()`: starts/stops a `requestAnimationFrame` sync loop
- `methods`: `addOrUpdateItemLabel()` (bound to each host's "Add/Update Sector Label" button), `computeEditorLabels()`, `computeEditorLegend()`

**Host must provide:** `itemLabelConfig` (two i18n alert keys), `_currentItemLabelName()`, `relatedItemNames` (computed, aligned with `related_jsons`/`relatedJsons`), a `ref="canvasOverlayAnchor"` root wrapper, `<CanvasOverlaysComponent ref="canvasOverlays" ...>` in the template, and **`<Editor :disable_auto_legend="true" ...>`** — see the mixin's own header comment for why this one is not optional (without it, `DrawingTools.vue` still bakes its own real Paper.js legend into the drawing on top of this mixin's live preview, which then gets permanently saved into the composite image).

**`CanvasOverlaysComponent.vue`** (`items/canvas/assets/canvas/CanvasOverlaysComponent.vue`) is the presentational half — a stateless component that just renders the labels SVG and legend `<canvas>` from props the mixin computes, and exposes `getLegendCanvasEl()` so the mixin can draw into it without reaching into the DOM directly.

### `canvasExtraDrawingMixin.js`

The "extra drawing" toggle/save/delete state machine — see [Save Flow — Per Context](#save-flow--per-context) below for what it persists to. Supplies:

- `data()`: `extra_drawing_mode`, `extra_drawing_json`, `extra_drawing_meta`, `extra_drawing_loading`, `deletingExtraDrawing`, `saving`, `saveStatus`, `_mainDrawingDirty`, `_extraDrawingDirty`
- `methods`: `loadExtraDrawing()`, `toggleExtraDrawingMode()`, `saveExtraDrawing()`, `deleteExtraDrawing()`, `markDrawingDirty()`, `confirmSaveIfDirty()`

**Host must provide:** `extraDrawingEndpoints` (`{ getForEditor, save, delete }`, each `(id) => url`), `extraDrawingAlertKeys` (four i18n keys — `noResourceSelected`, `noDrawingData`, `confirmDelete`, `confirmSaveBeforeSwitch`), `_extraDrawingResourceId()` (the id to interpolate into those URLs), and optionally `_buildExtraDrawingComposite(json)` (returns `{ editedImageData, canvasWidth, canvasHeight, bgBoundsPayload }` — each host already has its own composite-render helper), `_onExtraDrawingSaved(responseData)`, `_onExtraDrawingDeleted()`, `_saveMainDrawing()`. See the mixin's own header comment for the exact contract.

**Confirm-before-switch (added September 2026):** every host's own "Save" button method (`saveChanges`/`saveRouteDrawing`/`savePitchDrawing`) already starts with `if (this.extra_drawing_mode) return this.saveExtraDrawing();`, so it doubles as `_saveMainDrawing()` when called while `extra_drawing_mode` is still `false`. The host must call `this.markDrawingDirty()` as the very first line of `handleCanvasData()`, unconditionally — the mixin reads `this.extra_drawing_mode` at that instant to file the edit under `_mainDrawingDirty` or `_extraDrawingDirty`. `toggleExtraDrawingMode()` checks the flag for the side being LEFT before switching: if dirty, it shows `confirm($t(extraDrawingAlertKeys.confirmSaveBeforeSwitch))` — **OK** saves that side first (`_saveMainDrawing()` or `saveExtraDrawing()`) then switches, **Cancel** aborts the switch entirely (stays on the current side). Both flags are cleared automatically by the mixin's own save/delete/load methods; a host whose OWN methods reset the main drawing (switching route/pitch/image/sector) must also clear `this._mainDrawingDirty = false` there, or a stale edit from a previously viewed item can trigger a pointless prompt later. This matters because `canvasData`/`pitch_json`/etc. are kept in sync with every live edit via the `@canvas_data` event (see `CanvasManager.saveCanvasData()`, fired on essentially every draw action) — they are NOT "last saved" snapshots, so comparing them against the live canvas can never detect dirtiness; only an explicit flag can.

**Bug fixed (September 2026, round 2) — a save that failed still let the switch through, discarding the edit anyway:** `_saveMainDrawing()`/`saveExtraDrawing()` MUST resolve `false` on any failure or validation-guard early-return (every host's `saveChanges`/`saveRouteDrawing`/`savePitchDrawing`, and the mixin's own `saveExtraDrawing()`, now do). The first version of the confirm-before-switch flow `await`ed the save and proceeded to switch modes UNCONDITIONALLY regardless of the outcome — so a save that threw, or hit one of the host's own validation alerts (e.g. a transiently-unready canvas ref), still let the switch go through; the Editor immediately reimports the OTHER side's json onto the same canvas, permanently discarding the very edits the confirm was supposed to protect. Reported as "the route's drawing was simply lost/reverted after confirming save and switching." `toggleExtraDrawingMode()` now checks the save's return value and aborts the switch (`if (saved === false) return;`) when it's exactly `false`, leaving the user on the still-dirty side with the error visible in `saveStatus` so they can retry.

**Bug fixed (September 2026, round 3) — the confirm-before-switch protection only covered the toggle BUTTON, not picking a different item:** the dirty-check/save logic was inlined into `toggleExtraDrawingMode()` alone, so switching AWAY from a dirty extra drawing (or a dirty route/pitch drawing) by clicking a different route/pitch/sector/image in the SAME editor — not via the toggle button — silently discarded it, since none of those selection methods checked anything. The mixin now exposes a general-purpose `confirmSaveIfDirty()` (`toggleExtraDrawingMode()` itself just calls it) that any host method can `await` at its own entry point:
```js
async selectSomething(id) {
    if (this.currentId === id) return;         // no-op guard first
    if (!(await this.confirmSaveIfDirty())) return;  // then the dirty-check
    ... actually switch ...
}
```
It reads whichever side (`extra_drawing_mode` ? extra : main) is CURRENTLY active, and — if dirty — shows the confirm, saves via `saveExtraDrawing()`/`_saveMainDrawing()`, and returns `false` (caller MUST bail out, leaving all state untouched) on Cancel or a failed save. All 11 selection entry points across the five hosts now call it: `sectorLocaleImageEditorComponent.vue`'s `selectSector()`/`selectLayout()`/`newLayout()`, `spotRockImageEditorComponent.vue`'s `selectSector()`/`selectLayout()`, `sectorRouteDrawingsEditorComponent.vue`'s `selectRoute()`/`selectMtp()`/`selectPitch()`/`selectImage()`, and `CanvasRouteEditorComponent.vue`/`CanvasPitchEditorComponent.vue`'s shared-shape `selectSectorImageTab()`. A radio `<input>` bound with a plain `v-model` updates its underlying data SYNCHRONOUSLY on click, before any handler gets a chance to `await` a confirm — every one of these pickers had to be converted from `v-model="foo"` to `:checked="foo === value" @change="selectFoo(value)"` so the guarded method can reject the pick by simply not writing to `foo` (the radio's checked state then correctly reflects the old value on next render, since it's driven by data instead of native DOM state).

**Bug fixed (September 2026, round 3) — MTP pitch extra-drawing was invisible:** `sectorRouteDrawingsEditorComponent.vue`'s `activeJsonProp`/`activeJsonMeta`/`relatedFirstLabel`/`relatedJsons`/`relatedJsonsMeta`/`canDeleteCurrent`/`handleCanvasData`/`saveChanges`/`deleteDrawing` all checked `mtp_pitch_mode` BEFORE `extra_drawing_mode` — so entering extra-drawing mode from a selected pitch kept showing/editing/saving the PITCH's own drawing instead of the extra layer (an earlier version relied on a `extra_drawing_mode` watcher to clear `mtp_pitch_mode` first, which raced the very same computeds against Vue's own reactivity flush timing). Fixed by reordering every one of them to check `extra_drawing_mode` FIRST unconditionally — it's an orthogonal overlay on top of route/pitch mode, not a third exclusive mode — and removing the watcher entirely: `mtp_pitch_mode` is no longer touched by toggling extra-drawing mode at all, so "was this extra session entered from route or pitch mode" is never lost (needed by `relatedJsons`/`relatedFirstLabel` to show the left-behind pitch, not the route, as reference).

**Bug fixed (September 2026, round 3) — pitch mode never showed the extra drawing as a reference overlay:** even outside the invisibility bug above, `relatedJsons`/`relatedJsonsMeta`/`relatedFirstLabel` had a SEPARATE gap in the non-extra branch: route mode always prepended `extra_drawing_json` (so the sector's shared annotations show dimmed while editing any one route on that image), but pitch mode's branch returned `pitch_related_jsons` outright with no equivalent check at all — editing a pitch never showed the same image's extra annotations as reference, even though editing a route on the exact same photo already did. Reported as "extra drawing doesn't show in other drawing layouts." Fixed by using `mtp_pitch_mode ? pitch_related_jsons : otherRoutesJson` as the base list in all three computeds, then unshifting `extra_drawing_json`/`extra_drawing_meta` unconditionally on top either way.

**Bug fixed (September 2026, round 3) — the MTP public viewer never fetched or displayed the extra drawing at all:** `MTPModalComponent.vue`'s `canvas-json-show` never received an `extra_item` prop — `SectorCanvasModalComponent.vue` (the sector page's own modal) fetches `SectorImageExtraDrawing` via `GET /get_sector/get_sector_image_extra_drawing/get/{sector_image_id}` and passes it through, but the MTP modal had no equivalent code path whatsoever, so an admin's saved extra drawing for that sector image never appeared when viewing it through an MTP's own page — even though the exact same drawing showed fine on the sector page and in the admin editor. Fixed by adding the same `fetchExtraDrawing()`/`extraDrawing` pattern, keyed off a new `pitchSectorImageId` computed (reads `sector_image_id` off whichever pitch `pitchImageSrc`/`pitchCompositeSrc` already use as the background), watched so it refetches whenever a different MTP's modal opens.

**Common pitfall:** a host component must NOT redefine any of `loadExtraDrawing`/`toggleExtraDrawingMode`/`saveExtraDrawing`/`deleteExtraDrawing`/`computeEditorLabels`/`computeEditorLegend`/`addOrUpdateItemLabel` as its own method — Vue's mixin merge strategy for `methods` lets a component's own definition silently override the mixin's, so a leftover copy from before a migration to these mixins will shadow the shared implementation instead of erroring. This exact bug shipped once (fixed September 2026) — the host still had its own full copies of these methods (some referencing a since-renamed computed property) sitting alongside the mixin import, so the mixin's fixed/shared logic never actually ran.

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

**`route_name` prop → every item's `displayName`:** `EditorComponent._formatLayerName()` appends the host's `route_name` prop to EVERY drawn item's display name (e.g. raw name `"line 3"` + `route_name="Gveleti"` → displayed as `"line 3 Gveleti"`). Despite the name, this isn't route-specific — every host is expected to pass its own currently-edited item's display name here (a sector name, a route name, `"Pitch " + pitch name`, or `"extra info"` while extra-drawing mode is on), so the Layers panel always shows which item's drawing you're looking at. **All five host pages/modals must pass this** — omitting it (as `sectorLocaleImageEditorComponent.vue`/`spotRockImageEditorComponent.vue` did until this was unified) silently leaves every layer showing just its bare raw name with no item context, which reads as an inconsistent/incomplete editor next to the other four hosts that do pass it.

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

### Spot Rock Images

**Component:** `spotRockImageEditorComponent.vue`
**Endpoint:** `POST /api/set_sector/set_spot_rock_images/save_drawing/{image_id}`
**Model:** `SpotRocksImageJson` — `(spot_rocks_image_id, sector_id, json)`

Same shape as Sector Local Images above — one drawing per sector on a shared photo, backend backs up the original once to `images/spot_rocks_img/origin_img/` before overwriting the main file with the composite.

### Climbing Routes

**Component:** `CanvasRouteEditorComponent.vue` (route drawing modal) and `sectorRouteDrawingsEditorComponent.vue` (unified routes/pitches page, route mode)
**Controller:** `RouteJsonController`
**Models:** `ClimbingRoutesJson` — `(route_id, sector_image_id, json)`

Each route stores its own JSON. Multiple routes can be drawn on the same sector image; `related-N` layers show the other routes as reference.

### MTP Pitches

**Component:** `CanvasPitchEditorComponent.vue` (pitch drawing modal) and `sectorRouteDrawingsEditorComponent.vue` (unified routes/pitches page, pitch mode)
**Controller:** `MTPPitchController`
**Model:** `MtpPitchJson` — `(mtp_pitch_id, sector_image_id, json)`

Routes `POST /set_mtp_pitch/save_pitch_drawing/{pitch_id}` and `DELETE /set_mtp_pitch/del_pitch_drawing/{pitch_id}`.

### Extra Drawing (all sector-image-based hosts)

A toggleable general-annotation layer tied only to the shared sector image (not to any one route/pitch), used by `CanvasRouteEditorComponent.vue`, `sectorRouteDrawingsEditorComponent.vue` (both route AND pitch mode), and `CanvasPitchEditorComponent.vue` — all three share the SAME `SectorImageExtraDrawing` record, keyed only by `sector_image_id`, since a pitch is drawn on the same kind of sector image a route would be.

**Endpoints:** `GET/POST/DELETE /api/set_sector/set_sector_image_extra_drawing/{get_for_editor|save|delete}/{sector_image_id}`

`sectorLocaleImageEditorComponent.vue` and `spotRockImageEditorComponent.vue` each have their own equivalent, keyed by their own image id instead: `SectorLocalImageExtraDrawing` (`set_sector_local_image_extra_drawing`) and `SpotRocksImageExtraDrawing` (`set_spot_rocks_image_extra_drawing`) respectively — three separate backend tables/controllers by design (see `docs/BACKEND/CANVAS.md`), unified only on the frontend via `canvasExtraDrawingMixin.js`.

**Bug fixed (September 2026) — `CanvasPitchEditorComponent.vue` operated on the WRONG sector image after switching pitches:** `EditPitchModalComponent.vue` reuses one `CanvasPitchEditorComponent` instance across every pitch edited in a session — `close_modal()` never resets `editing_pitch_id` back to falsy, and the `<CanvasPitchEditor v-if="editing_pitch_id">` guard therefore never toggles false→true between pitches, so `mounted()`/`data()` only ever run once. `images_tab_num` (which feeds `_extraDrawingResourceId()`), `extra_drawing_mode`/`extra_drawing_json`, `otherPitchesJson`, and the dirty-tracking flags all silently carried over from whichever pitch was open previously — toggling or saving "extra drawing" for a newly opened pitch actually read/wrote the PREVIOUS pitch's sector image. A separate, smaller bug in the same component compounded this: `handleCanvasData()` wrote live edits to a local `canvas_json` property that nothing ever read (`activeJsonProp` reads the `pitch_json_prop` v-model prop instead), so a live-drawn pitch that got reimported mid-session (e.g. by toggling extra-drawing mode and back) could show a stale version. Fixed by adding a `pitch_id_prop` watcher that resets all per-pitch local state and re-derives `images_tab_num` from the new pitch's own `sector_image_id_prop`, and by emitting `update:pitch_json_prop` from `handleCanvasData()` like every other host does.

### Item-Name Label + Combined Legend (all hosts)

Handled entirely by `canvasOverlaysMixin.js` (see [Shared Host Mixins](#shared-host-mixins)) — no dedicated backend endpoint. The CURRENTLY-edited item's own label is a real Paper.js item saved as part of that item's normal JSON document (`data.isSectorLabel`/`data.isSectorLabelLine`); every OTHER item's label is a read-only overlay recomputed from the live `related-N` Paper.js layers, never persisted separately.

**The combined legend is never a per-item thing** — `resources/js/services/canvas/legendRenderer.js`'s `drawCombinedLegend()` is the ONE shared implementation of "compute the union of every topo-symbol type across a set of jsons and draw one card", used by three different callers that each used to (or, for the public route/pitch viewer, still had a real bug because they didn't) implement this separately:

| Caller | What it draws into | When |
|---|---|---|
| `canvasOverlaysMixin.js`'s `computeEditorLegend()` | A small live-preview `<canvas>` overlay, never saved | While editing (sector-local/spot-rock hosts only — see [Host Pages / Modals](#host-pages--modals)) |
| Every admin editor's `renderCompositeAtFullResolution()` | The full-resolution composite JPEG/PNG, baked in permanently | On every save (all five hosts) |
| `CanvasJsonDataShowComponent.vue`'s `render()` (public route/MTP-pitch viewer) | The live interactive `<canvas>`, redrawn on every selection/hover change | Every page view |
| `SectorLocalImageCanvasComponent.vue`'s `drawLegends()` (public sector-overview viewer) | Same live interactive `<canvas>` pattern, own inline copy of the same logic (predates the shared module) | Every page view |

**Bug fixed (September 2026):** `CanvasJsonDataShowComponent.vue` used to draw whichever `isLegend` Paper.js Group happened to be baked into the SELECTED item's own saved JSON (by `DrawingTools.vue`'s `rebuildLegend()`, at that item's own last save) — so switching which route/pitch was selected changed (or entirely hid) the legend, and any one item's baked-in copy could be stale relative to its own current symbols. It now calls `drawCombinedLegend()` itself, unconditionally, once per render, from the union of every item sharing the photo — consistent with every other legend consumer above. `paperJsonRenderer.js`'s `drawItem()` (and `CanvasJsonDataShowComponent.vue`'s own copy) still unconditionally skip any item's own baked-in `isLegend` group — that per-item Paper.js Group is legacy write-only content at this point: `DrawingTools.vue` still creates/updates it (so the toolbar's Position/Size picker has something to store its choice on — see `findLegendMeta`), but nothing anywhere reads it as content to render.

**Bug fixed (September 2026, round 2) — a single hidden sibling could blank the WHOLE combined legend:** `legendPosition`/`legendScale` (see `findLegendMeta()`) lives on each item's own `main` Layer `.data`, a holdover from the old per-item legend where `"hidden"` meant "I don't want MY OWN legend showing." All three combining call sites (`legendRenderer.js`'s `drawCombinedLegend()`, `canvasOverlaysMixin.js`'s `computeEditorLegend()`, and `SectorLocalImageCanvasComponent.vue`'s `_resolveLegendMeta()`) originally picked whichever sibling's meta was found FIRST (in fetch/iteration order) and used it as-is — so a route/pitch/sector that happened to have `legendPosition: "hidden"` set (typically from back before a shared combined legend existed, when hiding was a purely local, single-item decision) silently vetoed the legend for every OTHER sibling sharing that same photo too, EVEN when a sibling had a real symbol and a real position. Reproduced on sector "Ananuri" (`sector_image_id` 104): route 419 (no symbols, `legendPosition: "hidden"`, lowest route_id so it sorted first) blanked the legend in the interactive viewer even though sibling route 420 has a real pin symbol and `legendPosition: "top-left"` — while the composite JPEG on disk still showed the legend correctly, because whichever route was `own` at ITS last save (420, not 419) happened to check its own non-hidden meta first. Fixed by preferring the first sibling with a REAL (non-hidden) position across ALL siblings, and only falling back to `"hidden"` when literally every sibling that set anything at all asked to hide it.

**The composite image itself never had a legend at all**, even before this fix — `renderCompositeAtFullResolution()` only ever drew strokes/symbols, never a legend, so a plain `<img>` of the saved photo (outside the interactive viewer) never showed one. Every admin editor's composite-bake now calls `drawCombinedLegend()` too, right after drawing all strokes, so the saved image file itself carries the legend permanently. See `drawCombinedLegend()`'s own header comment for why this is safe against the accumulation risk the original "never bake" design was written to avoid.

**Follow-up bug fixed (same week):** `CanvasJsonDataShowComponent.vue`'s first version of `drawCombinedLegendOverlay` sized the legend from `canvas.getBoundingClientRect().width` (the canvas element's CURRENT on-screen CSS width) — inside a `StackModal`, that can be read before the modal has finished its open transition/layout, silently producing a garbage ratio. A too-small measured width inflates the scale factor (clamped at 10×), which can push the legend's computed box position (`w - boxW - margin`, etc.) far outside the canvas entirely — reading as "no legend at all" (route/sector viewer) or "legend way too big" (MTP pitch viewer) depending on how far off the one bad measurement happened to land. Fixed by sizing off `refMeta.bg_width`/`.canvas_width` instead — the SAME per-item reference width `_itemScale` already uses to scale that item's own strokes onto this canvas — which has no DOM-timing dependency at all and guarantees the legend renders at the exact size it was authored at in the admin editor, regardless of modal transition timing.

**Bug fixed (September 2026, round 3) — a doubled legend (and doubled shapes) on the public sector-local-images page:** `SectorsAndAreaLocalImageComponrnt.vue` passed `SectorLocalImageCanvasComponent.vue` the plain `sector_local_img/{filename}` as `image_src` — the admin's own baked COMPOSITE file, which (since the "composite bake now includes the legend" fix above) already has every sector's shapes AND the combined legend permanently drawn into its pixels. `SectorLocalImageCanvasComponent.vue`'s own `draw()`/`drawLegends()` then drew its OWN freshly-computed shapes + legend on top of that — visibly as two overlapping, slightly-offset copies of the SAME legend card (the baked one was sized/positioned at whatever `bg_width`/container-width the ADMIN's browser had at its last save; the live one is sized at the PUBLIC page's own container width — rarely identical, hence the "ghosted, offset" look rather than a perfect overlap). Every other drawing viewer avoids this by using the `has_original` flag to fall back to the CLEAN `origin_img/` backup once one exists (route/pitch viewers via `CanvasJsonDataShowComponent.vue`'s clean background + live JSON overlay; `spot_rocks_image`'s own public viewer, `SectorAndRoutesTabComponent.vue`, already did this correctly) — `sector_local_images`' "area local images" path was the one gap: `SectorController@get_area_local_images` never computed `has_original` at all (unlike `get_spot_rocks_images`, which already did, with a comment noting every other public controller does the same). Fixed by adding the same `file_exists(origin_img/...)` computation there and switching `SectorsAndAreaLocalImageComponrnt.vue`'s `image_src` to prefer it, exactly mirroring `spotImageSrc()` in `SectorAndRoutesTabComponent.vue`.

**Bug fixed (September 2026, round 3) — Points-of-interest legend rows were indistinguishable from each other:** a POI marker (`DrawingTools.vue`'s `_buildPoiParts`) is a big teardrop pin (`headCircle`/`tail`, filled, `data.isPoiMarker`) around a small white disc (`iconBg`) holding a thin pictogram/letter sized relative to the pin (e.g. `_drawPoiHikingIcon`'s limbs use `strokeWidth = r*0.17`, `_drawPoiBedIcon` a `PointText` "H"). Correct at the marker's normal on-photo size, but every legend card (`legendRenderer.js`'s `drawLegendCard`, and `SectorLocalImageCanvasComponent.vue`'s own duplicate implementation) uniformly shrinks the WHOLE sample to fit a small fixed `iconBox` (~22px) based on the outer pin's bounding box — for a POI marker authored at a much larger `R` than a typical topo symbol (rappel/bolt/pin, built with an absolute, non-proportional stroke width via `_markerStroke()`/`_boltStroke()`), that shrink factor pushes the ALREADY-thin inner glyph below a pixel, leaving only the pin's big filled shapes visible — every POI kind reading as the same plain red teardrop. Fixed by adding an optional `legendOpts` (`{ minStrokePx, minFontPx }`) parameter to every `drawItem()` implementation (`paperJsonRenderer.js`, `CanvasJsonDataShowComponent.vue`'s own copy) that clamps `ctx.lineWidth`/font size to a legible ON-SCREEN minimum via `ctx.getTransform()`'s current scale, regardless of how much the icon has been shrunk — passed only from the three legend-drawing call sites (`legendRenderer.js`'s `drawLegendCard`, `SectorLocalImageCanvasComponent.vue`'s own `drawItem` call, and `CanvasJsonDataShowComponent.vue`'s `drawCombinedLegendOverlay` wrapper), never from normal drawing (composite bake / interactive viewer / editor canvas), where an item's own authored proportions are already correct at the scale it's actually shown.

**Bug fixed (September 2026, round 4) — `SectorLocalImageCanvasComponent.vue` never drew any of the actual topo symbols/POI markers/lines, only the sector-boundary rectangle:** this component's `extractShapes()` was written ONLY to find the rectangle/circle/ellipse `data.isRectangle`/`isCircle`/`isEllipse` a sector's boundary is drawn with (for hover-highlighting) plus one sample per symbol type for the legend — it never collected the rest of a layout's content, and `draw()` correspondingly only ever rendered the background photo, that one boundary shape, the sector-name label, and the legend. Once `sectorLocaleImageEditorComponent.vue` gained full topo-symbol/POI-marker drawing (bolts, rappel points, POI pins, freehand lines — visible in its own Layers panel and live canvas), the PUBLIC page never caught up: every one of those symbols was completely invisible on the actual guidebook page, on both the sector-local-images and spot-rocks-images public viewers (`SectorAndRoutesTabComponent.vue`/`SectorsAndAreaLocalImageComponrnt.vue` both render this same component). Fixed by having `parseLayouts()` also keep each layout's full untouched json + its own `bg_*`/`canvas_*` meta (`rawMeta`), and `draw()` render it with `drawItemScaled()` (real authored colors, same as the admin editor) for every layout, skipping `isSectorLabel`/`isSectorLabelLine` via a new `skipDataFlags` param on `drawItem()`/`drawItemScaled()` (`paperJsonRenderer.js`) so the sector-name label — already drawn separately by `drawSectorLabels()` with its own styling/leader-line — doesn't get doubled, the same class of duplication the `isLegend` skip already prevents. Also relaxed the `.filter(l => l.shapes.length > 0)` gate to `.filter(l => l.shapes.length > 0 || l.rawMeta)`, since a layout with only symbols and no boundary rectangle used to be dropped before `draw()` ever saw it.

**Bug fixed (September 2026, round 4) — legend icons looked crude/indistinguishable compared to the admin editor:** the `minStrokePx`/`minFontPx` floor from the previous round kept POI icons from vanishing entirely, but leaning on a hard floor alone can still look thick/blobby rather than a faithful small copy of the real symbol, and does nothing at all for a FILLED (no-stroke) glyph like the water-drop/medical-cross icons. Bumped `iconBox`/`rowH` up twice (22px → 30px → 38px / 30px → 34px → 40px, in BOTH `legendRenderer.js`'s `drawLegendCard` and `SectorLocalImageCanvasComponent.vue`'s own duplicate; `minStrokePx`/`minFontPx` also nudged up, 1.4/9 → 1.6/10) so every symbol keeps more of its natural on-photo proportions before the floor ever needs to kick in — closer to how it actually looks drawn on the canvas, per both editor live-preview and every public viewer.

**Changed (September 2026) — the plain "Rappel" toolbar button moved from the Topo Symbols group to the Anchors group** (`ToolbarComponent.vue`, action 22), next to the `rappel-anchor-*` family cluster (actions 34-36) — matches the legend-category move above: a rappel/lower-off point is conceptually an anchor, and sits better next to the other anchor variants than next to bolt/pin/pendulum/crux. Purely a toolbar layout change — the action id, emitted event name (`rappel`), and everything `DrawingTools.vue`'s `add_rappel()` does are untouched.

**Bug fixed (September 2026, round 4) — MTP legend position wasn't synced between pitches:** `rebuildLegend()` (`DrawingTools.vue`) writes SOME `legendPosition`/`legendScale` into EVERY item's own saved json on EVERY save — whether or not the admin ever touched the toolbar's position picker for that specific item — so nearly every sibling "has a real (non-hidden) position" by the round-2 fix's own check. `canvasOverlaysMixin.js`'s `computeEditorLegend()` collected `activeJsonProp` (whichever pitch/route is CURRENTLY selected) FIRST, so the winning position flip-flopped to match whatever the just-selected item's own (often just incidental/default) value happened to be — reported as "if I cross between the pitches, legend position changes." The same "own first" ordering existed in every host's `renderCompositeAtFullResolution()`, which is worse: since a save always overwrites the SAME shared photo file regardless of which sibling triggered it, the BAKED position could shift depending on whichever item was saved LAST. Fixed by collecting/ordering siblings BEFORE the active/own item in both places — `_overlaySiblingJsons()` stays in the same backend-fetched order regardless of selection, so checking it first resolves to the same sibling's position call after call for the common case of one shared, intentionally-chosen position (a genuine 3+-way disagreement between different items' saved positions still has no fully "correct" answer without a dedicated per-image setting, which doesn't exist yet).

**Changed (September 2026) — "Rappel / lower-off" moved from the Topo Symbols section to Anchors:** `topoSymbolTypes.js`'s `rappel` entry's `category` changed from `'topo'` to `'anchor'` — a rappel/lower-off point is conceptually an anchor, grouping it under Anchors alongside the anchor-quality variants and rescue anchor reads more consistently than under Topo Symbols next to bolt/pin/pendulum/crux.

### Canvas Deletion Cleanup

**Service:** `app/Services/CanvasService.php`

Call these before deleting a sector image to avoid orphaned JSON records:

```php
// When deleting a sector image (sector_images table row):
CanvasService::deleteSectorImageCanvasData($sectorImageId);

// When deleting a sector local image (sector_local_images table row):
CanvasService::deleteSectorLocalImageCanvasData($sectorLocalImageId);

// When deleting a spot rocks image (spot_rocks_images table row):
CanvasService::deleteSpotRocksImageCanvasData($spotRocksImageId);
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
| Host redefines a method the shared mixin already provides (e.g. `computeEditorLabels`) | Vue's mixin merge silently lets the host's own method win — delete the host's copy instead of leaving it alongside the mixin import |
| Relative import path off by one `../` inside `items/canvas/mixins/*.js` | These files live one level deeper than `items/canvas/*.vue` — count directories from `resources/js/services/...`, not from a sibling `.vue` file's own working import |
| Trying to detect unsaved changes by diffing `canvasData`/`pitch_json`/etc. against `getCleanJson()` | They're already identical — `CanvasManager.saveCanvasData()` emits `canvas_data` on essentially every draw action, so the host prop mirrors the live canvas continuously. Use `canvasExtraDrawingMixin.js`'s explicit `_mainDrawingDirty`/`_extraDrawingDirty` flags (set via `markDrawingDirty()` in `handleCanvasData()`, cleared on save/delete/fresh-load) instead |
| A host adds `mtp_pitch_mode`-style extra state to `sectorRouteDrawingsEditorComponent.vue`'s toggle button `v-if` | Extra-drawing is keyed by `selectedImageId` (the shared sector image), not by which mode picked it — gating the button on another mode flag just hides a feature that would otherwise work fine in that mode too |
| Picking a combined-legend `legendPosition`/`legendScale` via "first sibling found wins" | A sibling with no symbols of its own can carry a leftover `legendPosition: "hidden"` from the old per-item legend and, if it sorts first, blank the legend for every OTHER sibling sharing the photo too — prefer the first REAL (non-hidden) position across all siblings, only falling back to hidden when none exists (see the Item-Name Label + Combined Legend section's second "Bug fixed" note) |
| A modal-style canvas editor (`v-if="someId"` gating, no `:key`) reused by its host across different records | If the host clears its "which record" id back to falsy before opening a NEW one (or binds `:key`), the component remounts and `data()` resets normally; if not (see `EditPitchModalComponent.vue`'s `close_modal()`, which never resets `editing_pitch_id`), the SAME instance persists across records and every piece of local state — `images_tab_num`, extra-drawing mode/json, the dirty flags — silently carries over from whichever record was open previously. Add a watcher on the record-id prop that resets all of it (see `CanvasPitchEditorComponent.vue`'s `pitch_id_prop` watcher, fixed September 2026) rather than relying on `mounted()`/`data()` defaults, which only ever run once for a reused instance |
| `await`ing a host's save method in `toggleExtraDrawingMode()` without checking its result | A save that fails (throws, or hits the host's own validation `alert()`+early-return) must resolve `false`, not `undefined` — otherwise the confirm-before-switch flow can't tell success from failure and proceeds to switch anyway, immediately reimporting the OTHER side's json and discarding the edit that failed to save. Every `saveChanges`/`saveRouteDrawing`/`savePitchDrawing`/`saveExtraDrawing` must `return false` on every failure/guard path (fixed September 2026) |
| A public viewer passes the admin's baked COMPOSITE file as `image_src` to a component that ALSO draws its own shapes/legend live from JSON on top | Doubles every shape and the legend card (two overlapping, usually slightly-offset copies) once the composite starts baking a legend in too — check for a `has_original` flag and fall back to the clean `origin_img/{filename}` backup, exactly like every other drawing viewer already does (`CanvasJsonDataShowComponent.vue` callers, `SectorAndRoutesTabComponent.vue`'s `spotImageSrc()`) |
| Assuming a small fixed legend icon box (~22px) is fine for every symbol type | It is for topo symbols (built with an absolute, non-proportional stroke width), but a POI marker's inner pictogram is sized RELATIVE to its own much-larger outer pin — shrinking the whole marker uniformly can push that thin detail below a pixel, leaving indistinguishable plain pins. Pass `legendOpts: { minStrokePx, minFontPx }` into `drawItem()` from the legend-drawing call sites only |
| Checking `mtp_pitch_mode` (or any other host-specific "which item type" flag) before `extra_drawing_mode` in a computed/method the two can both be true for | `extra_drawing_mode` is an orthogonal overlay, not a third exclusive mode — check it FIRST everywhere, or the extra layer silently shows/saves into whichever OTHER mode happens to be checked first instead (invisible extra drawing, or a save that corrupts the other mode's own data) |
| A `v-model`-bound radio `<input>` inside a method you want to guard with `confirmSaveIfDirty()` | `v-model` updates the bound data SYNCHRONOUSLY on click, before any `@change` handler can `await` a confirm — convert to `:checked="foo === value"` + `@change="guardedSetFoo(value)"` so a rejected pick leaves `foo` (and the radio's rendered checked state) untouched |

---

[Back to Frontend docs](../FRONTEND.md)
