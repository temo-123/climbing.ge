import { drawItem } from '../../../../../services/canvas/paperJsonRenderer.js'
import { collectSymbolSamples, findLegendMeta, drawLegendCard } from '../../../../../services/canvas/legendRenderer.js'
import { TOPO_SYMBOL_TYPES } from '../assets/canvas/tools/topoSymbolTypes.js'
import paper from 'paper'

// Shared "item label + leader line" and "combined topo-symbol legend preview"
// behavior for every canvas-editor page/modal that lets several items (sectors,
// routes, or MTP pitches) share ONE background image — originally built for
// sectorLocaleImageEditorComponent.vue, then kept in sync by hand in
// spotRockImageEditorComponent.vue. Extracted here so every host gets the same
// behavior (and the same bug fixes) from one place, instead of N copies
// drifting apart.
//
// HOST CONTRACT — a component using this mixin MUST:
//
// Template:
//   - Root element: `ref="canvasOverlayAnchor"` + a `position` other than
//     static (e.g. class="position-relative") — the shared clip wrappers are
//     positioned `absolute` against this ancestor. It does not need to be the
//     immediate parent of the overlay markup, just an ancestor with no other
//     positioned element in between.
//   - `<Editor ref="editorComponent" :disable_auto_legend="true" .../>` — the
//     CanvasContainerComponent ref chain this mixin reads via
//     `getCanvasScope()`/`getBackgroundBounds()`. `disable_auto_legend` is NOT
//     optional once this mixin is in use: without it, DrawingTools.vue still
//     bakes its own REAL Paper.js legend Group into the drawing (and into the
//     saved composite image) on top of this mixin's live-preview overlay —
//     two contradicting legends, one of which gets permanently saved. See
//     DrawingTools.vue's `_positionLegend`/legend-building code for the full
//     rationale (search `disableAutoLegend`).
//   - Render `<CanvasOverlaysComponent ref="canvasOverlays" :editor-labels="editorLabels"
//     :labels-clip-style="labelsClipStyle" :legend-preview-style="legendPreviewStyle"
//     :legend-clip-style="legendClipStyle" />` (see that component) somewhere
//     inside the same `canvasOverlayAnchor` ancestor.
//
// Data/computed the host must expose:
//   - `activeJsonProp` — the CURRENTLY-edited item's live Paper.js JSON (string
//     or object), whatever mode the host is in right now. Required always —
//     the legend preview needs it even on a host with no label-creation UI.
//   - `relatedJsons` (or `related_jsons`) — array of sibling items' own JSON,
//     same array already fed to `<Editor :related_jsons>`. Required always,
//     same reason as `activeJsonProp`.
//
// The rest of this contract — `itemLabelConfig`, `_currentItemLabelName()`,
// `relatedItemNames`, and the "Add/Update Sector Label" button that calls
// `addOrUpdateItemLabel()` — is OPTIONAL: only needed on a host that lets the
// admin create/rename the CURRENTLY-edited item's own label (currently
// sectorLocaleImageEditorComponent.vue and spotRockImageEditorComponent.vue —
// a route/pitch already gets its own name on every layer row via the
// `route_name` prop instead, see CANVAS_EDITOR.md's Layers Panel section, so
// it has no separate "sector label" to create). A host that omits all three
// simply never calls `addOrUpdateItemLabel()` and `computeEditorLabels()`
// safely falls back to an empty `relatedItemNames` array — the legend preview
// still works normally either way.
//   - `itemLabelConfig` — `{ drawSomethingAlertKey, selectItemFirstAlertKey }`,
//     full i18n key paths (this host's own translation namespace) for the two
//     alerts `addOrUpdateItemLabel()` can show.
//   - `_currentItemLabelName()` — returns the display name to stamp on the
//     label (sector name / route name / pitch name), or a falsy value if
//     nothing is selected yet.
//   - `relatedItemNames` (computed, or a method) — array aligned with the
//     `related-N` Paper.js layers, giving each sibling's own display name
//     (used only to label the READ-ONLY reference overlay for other items,
//     not the current item's own label).
//
// Everything else (state, positioning math, the label's own Paper.js item
// shape) lives entirely in this mixin.
export default {
    data() {
        return {
            // Item-name label + leader-line overlay shown over the live canvas
            // for every OTHER item's own label (see computeEditorLabels) —
            // [{ text, anchorX, anchorY, boxX, boxY, boxW, boxH, boxCenterX,
            // boxCenterY, boxBottomY, fontSize }] in coordinates relative to
            // the Paper.js canvas element's own top-left corner (i.e. relative
            // to labelsClipStyle's own box, NOT the viewport).
            editorLabels: [],
            // Inline style for the labels overlay's CLIPPING wrapper — sized/
            // positioned to exactly match the Paper.js canvas element's own
            // bounding rect, relative to the root element's own
            // position-relative wrapper (ref="canvasOverlayAnchor").
            labelsClipStyle: { display: 'none' },
            // Inline style for the legend-preview <canvas> overlay (see
            // computeEditorLegend) — position is relative to legendClipStyle's
            // own box (its clipping wrapper), or display:none while there's
            // nothing to show.
            legendPreviewStyle: { display: 'none' },
            // Inline style for the legend-preview's CLIPPING wrapper — sized/
            // positioned to exactly match the Paper.js canvas element's own
            // bounding rect, relative to the root element's own
            // position-relative wrapper.
            legendClipStyle: { display: 'none' },
            // Whether ANY sibling (including the currently-edited item)
            // sharing this photo has a topo-symbol/POI marker at all — i.e.
            // whether the combined legend has anything to show. Host pages
            // pass this straight through to `<Editor :has_legend_symbols>`,
            // which hides the whole Legend Position/Size toolbar group when
            // false (fixed September 2026, round 13 — "if i dont have a
            // sign item dont show position and size selection") instead of
            // showing two controls that visibly do nothing yet. Computed the
            // same way computeEditorLegend() itself decides whether to draw
            // anything, so this can never disagree with what the preview
            // actually shows.
            hasLegendSymbols: false,
        };
    },

    mounted() {
        // requestAnimationFrame rather than a slower setInterval — pan/zoom
        // changes need to track instantly. Actual page SCROLL does NOT
        // depend on this loop (the overlay is `position:absolute` inside the
        // same normal-flow ancestor as the canvas — see CanvasOverlaysComponent
        // — so the browser scrolls both in perfect lockstep on its own); a
        // `position:fixed` overlay resynced only once per animation frame
        // previously needed the scroll case too, and being only ever one
        // frame behind actual native scroll compositing, visibly lagged/
        // blinked while scrolling.
        this._overlaysSyncLoop();
    },

    beforeUnmount() {
        if (this._overlaysSyncFrame) cancelAnimationFrame(this._overlaysSyncFrame);
    },

    methods: {
        _overlaysSyncLoop() {
            this.computeEditorLabels();
            this.computeEditorLegend();
            this._overlaysSyncFrame = requestAnimationFrame(() => this._overlaysSyncLoop());
        },

        // Sibling-json list, tolerant of either naming convention already in
        // use across the various host pages.
        _overlaySiblingJsons() {
            return this.relatedJsons || this.related_jsons || [];
        },

        // Finds the shape whose bounds anchor a new/existing item label — a
        // sector/spot-rock item draws a boxed area (rectangle/circle/ellipse),
        // but a route/pitch draws a topo ascent line with no such shape, so
        // fall back to the bounding box of everything drawn in 'main' when no
        // dedicated region shape exists.
        _findLabelAnchorBounds(mainLayer) {
            const regionItem = mainLayer.children.find(item => item.data &&
                (item.data.isRectangle || item.data.isCircle || item.data.isEllipse));
            if (regionItem) return regionItem.bounds;

            const drawnItems = mainLayer.children.filter(item =>
                !(item.data && (item.data.isSectorLabel || item.data.isSectorLabelLine)));
            if (!drawnItems.length) return null;
            return drawnItems.reduce((acc, it) => acc ? acc.unite(it.bounds) : it.bounds, null);
        },

        // Explicit, button-triggered creation of the CURRENTLY-edited item's
        // own name label — a real Paper.js item in the 'main' layer: a
        // [background, text] Group (data.isSectorLabel) plus a separate
        // sibling leader-line item (data.isSectorLabelLine, kept OUTSIDE the
        // group so dragging the label via the editor's "Move item" tool
        // doesn't rigidly translate both ends of the line with it; see
        // CanvasHandlers.vue's action-8 drag handler, which instead
        // live-follows the label with the line's label-side endpoint and
        // re-anchors the other end to the closest region-shape corner/side).
        // Being real Paper.js content in 'main' means it's saved/loaded for
        // free through the exact same JSON path every other shape already
        // uses — no backend change needed — and it shows up in the Layers
        // panel and behaves like any other item: created only when the user
        // asks for it, no background process auto-creating or
        // auto-repositioning it behind their back.
        //
        // NOTE: keeps the `isSectorLabel`/`isSectorLabelLine`/`sectorLabelBox`/
        // `sectorLabelAnchor` `.data` flag names exactly as they were when this
        // was sector_local_image-only — already-saved layouts across every
        // context use these names, so they must not be renamed even though
        // this mixin is no longer sector-specific.
        //
        // Idempotent: if the label already exists, clicking the button again
        // only refreshes its text (in case the item was renamed) — its
        // position/anchor are left exactly where the user put them.
        addOrUpdateItemLabel() {
            if (this.extra_drawing_mode) return; // no "current item" while editing the extra-info layer
            const canvasContainer = this.$refs.editorComponent && this.$refs.editorComponent.$refs.canvasContainer;
            const scope = canvasContainer && typeof canvasContainer.getCanvasScope === 'function'
                ? canvasContainer.getCanvasScope() : null;
            if (!scope || !scope.project) return;
            scope.activate();
            const mainLayer = scope.project.layers.find(l => l.name === 'main');
            if (!mainLayer) return;

            const anchorBounds = this._findLabelAnchorBounds(mainLayer);
            const name = this._currentItemLabelName ? this._currentItemLabelName() : null;

            if (!anchorBounds) { alert(this.$t(this.itemLabelConfig.drawSomethingAlertKey)); return; }
            if (!name) { alert(this.$t(this.itemLabelConfig.selectItemFirstAlertKey)); return; }

            const existingLabel = mainLayer.children.find(item => item.data && item.data.isSectorLabel);
            const existingLine  = mainLayer.children.find(item => item.data && item.data.isSectorLabelLine);

            if (existingLabel && existingLine) {
                const text = existingLabel.children.find(c => c instanceof paper.PointText);
                if (text && text.content !== name) {
                    text.content = name;
                    this._resizeItemLabelBg(existingLabel, text);
                }
                scope.view.update();
                // Bumps this into the undo history and syncs the host's own
                // canvas-data state via the existing @canvas_data listener —
                // same as any other edit — but the actual PERSIST-to-backend
                // still only happens when the user clicks Save, exactly like
                // every other shape.
                if (typeof canvasContainer.saveCanvasData === 'function') canvasContainer.saveCanvasData();
                return;
            }

            // Partially missing (e.g. only the line survived some manual
            // edit) — clear out whichever half remains and create fresh
            // rather than trying to patch a mismatched pair back together.
            if (existingLabel) existingLabel.remove();
            if (existingLine) existingLine.remove();

            // Background bounds (project-space) decide which side of the box
            // the label defaults to — see _createItemLabel for why.
            const bgBounds = canvasContainer.getBackgroundBounds ? canvasContainer.getBackgroundBounds() : null;
            this._createItemLabel(mainLayer, anchorBounds, bgBounds, name);
            scope.view.update();
            if (typeof canvasContainer.saveCanvasData === 'function') canvasContainer.saveCanvasData();
        },

        // Builds a fresh [bg, text] label Group positioned a fixed distance
        // above the region's box by default, plus its separate leader-line
        // sibling connecting box to label — UNLESS the box sits too close to
        // the top of the photo for that to fit (see below), in which case it
        // defaults below instead.
        _createItemLabel(mainLayer, anchorBounds, bgBounds, name) {
            mainLayer.activate();
            const fontSize = 15, padX = 10, padY = 6, defaultOffsetY = 70;

            const text = new paper.PointText({
                point: new paper.Point(0, 0),
                content: name,
                fillColor: '#1a1a1a',
                fontFamily: 'Arial',
                fontWeight: 'bold',
                fontSize,
                justification: 'center',
                name: 'sector-label-text'
            });
            // Stays black regardless of any recolor of the label box (see
            // EditorComponent.vue's _setItemColor isSectorLabelContainer branch).
            text.data = { isHole: true };
            const boxW = text.bounds.width + padX * 2;
            const boxH = fontSize + padY * 2;

            const topAnchor = new paper.Point(anchorBounds.left + anchorBounds.width / 2, anchorBounds.top);
            // Room actually available above the box, inside the photo itself
            // — a box cropped right against the top edge of the photo (e.g.
            // a sector drawn near the top of a drone shot) has near-zero
            // room there, so the old "always above" default placed the
            // label ABOVE the photo entirely: invisible past the canvas'
            // own top edge, cut off behind the toolbar (fixed September
            // 2026, reported as "sector laible is out of image" when a
            // sector sits close to the top). Falls back to "always fits"
            // (Infinity) when the background bounds aren't known yet, so
            // behavior is unchanged wherever this can't be determined.
            const spaceAbove = bgBounds ? (topAnchor.y - bgBounds.top) : Infinity;
            const placeBelow = spaceAbove < (defaultOffsetY + boxH);

            const lineAnchor = placeBelow
                ? new paper.Point(anchorBounds.left + anchorBounds.width / 2, anchorBounds.top + anchorBounds.height)
                : topAnchor;
            const labelCenter = placeBelow
                ? new paper.Point(lineAnchor.x, lineAnchor.y + defaultOffsetY + boxH / 2)
                : new paper.Point(lineAnchor.x, lineAnchor.y - defaultOffsetY - boxH / 2);
            const lineLabelEnd = placeBelow
                ? new paper.Point(labelCenter.x, labelCenter.y - boxH / 2)
                : new paper.Point(labelCenter.x, labelCenter.y + boxH / 2);

            const bg = new paper.Path({
                closed: true, fillColor: '#ffe100', strokeColor: '#1a1a1a', strokeWidth: 1.5,
                name: 'sector-label-bg'
            });
            bg.add(new paper.Point(labelCenter.x - boxW / 2, labelCenter.y - boxH / 2));
            bg.add(new paper.Point(labelCenter.x + boxW / 2, labelCenter.y - boxH / 2));
            bg.add(new paper.Point(labelCenter.x + boxW / 2, labelCenter.y + boxH / 2));
            bg.add(new paper.Point(labelCenter.x - boxW / 2, labelCenter.y + boxH / 2));

            text.point = new paper.Point(labelCenter.x, labelCenter.y + fontSize * 0.35);

            const line = new paper.Path({ strokeColor: '#1a1a1a', strokeWidth: 1.5, name: 'sector-label-line' });
            line.add(lineAnchor);
            line.add(lineLabelEnd);
            // anchorAuto: true — the region-side endpoint auto-tracks whichever
            // corner/side-center is closest to the label LIVE as it is dragged
            // (see CanvasHandlers.vue's action-8 onMouseDrag) until the user
            // manually drags this endpoint via the "Edit Points" tool, which
            // flips this off (see action-16 onMouseUp).
            line.data = { isSectorLabelLine: true, anchorAuto: true };

            const group = new paper.Group([bg, text]);
            group.name = 'sector-label';
            group.data = { isSectorLabel: true };

            mainLayer.addChild(line);
            mainLayer.addChild(group);
        },

        // Re-centers an existing label's background rect on its (possibly
        // resized, after a rename) text — keeps the label's CENTER fixed
        // rather than its top-left, so a rename doesn't visibly shift it.
        _resizeItemLabelBg(labelGroup, text) {
            const bg = labelGroup.children.find(c => c !== text);
            if (!bg || bg.segments.length < 4) return;
            const center = bg.bounds.center;
            const padX = 10;
            const boxW = text.bounds.width + padX * 2;
            const boxH = bg.bounds.height;
            bg.segments[0].point = new paper.Point(center.x - boxW / 2, center.y - boxH / 2);
            bg.segments[1].point = new paper.Point(center.x + boxW / 2, center.y - boxH / 2);
            bg.segments[2].point = new paper.Point(center.x + boxW / 2, center.y + boxH / 2);
            bg.segments[3].point = new paper.Point(center.x - boxW / 2, center.y + boxH / 2);
            text.point = new paper.Point(center.x, center.y + text.fontSize * 0.35);
        },

        // Rebuilds the OTHER items' name-label + leader-line overlay from the
        // LIVE Paper.js scene — the read-only reference shapes shown in their
        // own 'related-N' layers. The CURRENTLY-edited item's own label is a
        // real, draggable Paper.js item instead (see addOrUpdateItemLabel)
        // since it needs to be persisted/movable — these reference ones are
        // read-only, so a lightweight non-interactive overlay is enough and
        // can't drift from the live 'related-N' data by construction
        // (recomputed every tick).
        computeEditorLabels() {
            const canvasContainer = this.$refs.editorComponent && this.$refs.editorComponent.$refs.canvasContainer;
            const scope = canvasContainer && typeof canvasContainer.getCanvasScope === 'function'
                ? canvasContainer.getCanvasScope() : null;
            if (!scope || !scope.view || !scope.project) { this.editorLabels = []; this.labelsClipStyle = { display: 'none' }; return; }

            const canvasEl = scope.view.element;
            if (!canvasEl || !canvasEl.isConnected) { this.editorLabels = []; this.labelsClipStyle = { display: 'none' }; return; }
            const anchorEl = this.$refs.canvasOverlayAnchor;
            if (!anchorEl) { this.editorLabels = []; this.labelsClipStyle = { display: 'none' }; return; }
            const rect = canvasEl.getBoundingClientRect();
            const anchorRect = anchorEl.getBoundingClientRect();
            // Offset between the canvas's own top-left and the shared
            // position-relative ancestor's (ref="canvasOverlayAnchor") — the
            // labels clip wrapper gets positioned at this offset, so that
            // everything drawn INSIDE it (see toScreen) can be expressed
            // purely relative to the CANVAS's own top-left.
            const offLeft = rect.left - anchorRect.left;
            const offTop  = rect.top  - anchorRect.top;
            // The canvas is CSS `width:100%` (responsive) while Paper.js's
            // OWN logical view size (view.viewSize, in CSS-pixel-equivalent
            // units — what projectToView() outputs) was fixed at whatever
            // the container's width was when the background image loaded —
            // a later window resize changes the CSS-rendered size without
            // touching that logical size, so this extra scale is needed to
            // land on the right on-screen pixel.
            //
            // IMPORTANT: compare against view.viewSize here, NOT the raw
            // canvasEl.width/height HTML attributes — on any HiDPI display
            // Paper.js sets those attributes to viewSize * devicePixelRatio
            // (see CanvasView#_setElementSize), so dividing by them instead
            // silently shrunk every computed coordinate by ~1/devicePixelRatio.
            const viewSize = scope.view.viewSize;
            const scaleX = viewSize && viewSize.width  ? rect.width  / viewSize.width  : 1;
            const scaleY = viewSize && viewSize.height ? rect.height / viewSize.height : 1;
            const toScreen = (x, y) => {
                const v = scope.view.projectToView({ x, y });
                return { x: v.x * scaleX, y: v.y * scaleY };
            };

            // Recursive so a region shape nested inside a group (e.g.
            // accidentally grouped via the Layers panel) still counts —
            // matches how the public viewer's extractShapes() stays robust
            // to nesting too.
            const collectBoxes = (layer) => {
                const boxes = [];
                const visit = (item) => {
                    if (!item) return;
                    if (item.data && (item.data.isRectangle || item.data.isCircle || item.data.isEllipse)) {
                        const b = item.bounds;
                        boxes.push({ x: b.left, y: b.top, w: b.width, h: b.height });
                        return;
                    }
                    if (item.children) item.children.forEach(visit);
                };
                if (layer) layer.children.forEach(visit);
                return boxes;
            };

            // A sibling item's REAL saved label + leader line (captured onto
            // the related layer's own `.data` before being stripped as a live
            // item — see importRelatedJsons in CanvasHandlers.vue) takes
            // priority over collectBoxes' generic shape-derived guess, same
            // as the public viewer's extractShapes/drawSectorLabels. Without
            // this, a sibling's label always showed at its auto-computed
            // top-center default here even after the admin dragged it
            // elsewhere while editing THAT item.
            const entries = [];
            const relatedNames = this.relatedItemNames || [];
            scope.project.layers
                .filter(l => l.name && l.name.startsWith('related-'))
                .forEach(layer => {
                    const idx = parseInt(layer.name.replace('related-', ''), 10);
                    const name = relatedNames[idx];
                    if (!name) return;
                    const realBox    = layer.data && layer.data.sectorLabelBox;
                    const realAnchor = layer.data && layer.data.sectorLabelAnchor;
                    if (realBox && realAnchor) {
                        entries.push({ name, realBox, realAnchor });
                    } else {
                        collectBoxes(layer).forEach(box => entries.push({ name, box }));
                    }
                });

            if (!entries.length) { this.editorLabels = []; this.labelsClipStyle = { display: 'none' }; return; }

            const fontSize = 13, padX = 10, padY = 6, gap = 6;
            const labelH = fontSize + padY * 2;
            // Canvas-relative (toScreen adds no viewport/anchor offset) — 10px
            // below the canvas's OWN top edge.
            const marginTop = 10;
            const measureCtx = this._labelMeasureCtx || (this._labelMeasureCtx = document.createElement('canvas').getContext('2d'));
            measureCtx.font = `bold ${fontSize}px Arial`;
            // Matches the editor's own box-height/font-size ratio at creation
            // time (fontSize=15, padY=6 -> boxH=27 -> ratio 15/27).
            const REAL_LABEL_FONT_RATIO = 15 / 27;

            // No hard anchor-distance filter here — the labels clip wrapper
            // (CanvasOverlaysComponent) clips anything outside the canvas's
            // own box the same way a real drawing item would be, so an
            // out-of-view label just smoothly disappears/reappears via normal
            // CSS overflow instead of a per-frame v-if toggle (which
            // flickered right at the boundary — the "blinking while
            // scrolling/zooming" bug an earlier version of this had).
            const labels = entries.map(({ name, box, realBox, realAnchor }) => {
                if (realBox && realAnchor) {
                    const tl = toScreen(realBox.x, realBox.y);
                    const anchor = toScreen(realAnchor.x, realAnchor.y);
                    const w = realBox.w * scaleX, h = realBox.h * scaleY;
                    return {
                        text: name,
                        anchorX: anchor.x, anchorY: anchor.y,
                        w, h, x: tl.x, y: tl.y,
                        fontSize: Math.max(8, h * REAL_LABEL_FONT_RATIO),
                        isReal: true,
                    };
                }
                const anchor = toScreen(box.x + box.w / 2, box.y);
                const boxW = measureCtx.measureText(name).width + padX * 2;
                return {
                    text: name,
                    anchorX: anchor.x, anchorY: anchor.y,
                    w: boxW, h: labelH,
                    x: anchor.x - boxW / 2,
                    y: Math.max(marginTop, anchor.y - labelH - fontSize * 1.6),
                    fontSize,
                    isReal: false,
                };
            });

            // Simple left-to-right de-collision pass, same as the public
            // viewer's drawSectorLabels — only applied to auto-computed
            // labels; a real saved label keeps the exact position the admin
            // placed it at while editing that item.
            const autoLabels = labels.filter(l => !l.isReal);
            const realLabels = labels.filter(l => l.isReal);
            autoLabels.sort((a, b) => a.x - b.x);
            for (let i = 1; i < autoLabels.length; i++) {
                const prev = autoLabels[i - 1], cur = autoLabels[i];
                if (cur.x < prev.x + prev.w + gap) cur.x = prev.x + prev.w + gap;
            }

            this.editorLabels = [...realLabels, ...autoLabels].map(l => ({
                text: l.text,
                anchorX: l.anchorX, anchorY: l.anchorY,
                boxX: l.x, boxY: l.y, boxW: l.w, boxH: l.h,
                boxCenterX: l.x + l.w / 2, boxCenterY: l.y + l.h / 2, boxBottomY: l.y + l.h,
                fontSize: l.fontSize,
            }));

            this.labelsClipStyle = {
                display: 'block',
                left: offLeft + 'px',
                top: offTop + 'px',
                width: rect.width + 'px',
                height: rect.height + 'px',
            };
        },

        // Recomputes the combined-legend PREVIEW (see legendPreviewStyle) —
        // the union of every distinct topo-symbol type across the current
        // item's own live JSON plus every sibling's own last-saved JSON,
        // drawn with each symbol's REAL color (every document here is read as
        // its own untouched JSON, never a tinted live reference-overlay item,
        // so there's nothing to contaminate the colors). Nothing here is ever
        // written into any item's own document — purely a "here's the full
        // picture" preview.
        //
        // Positioned/sized via the SAME pan/zoom-aware projectToView
        // transform as computeEditorLabels() above — the card's project-space
        // box is converted to on-screen pixels every tick, so it moves and
        // scales exactly like a real drawing element as the admin pans/zooms.
        computeEditorLegend() {
            const canvasContainer = this.$refs.editorComponent && this.$refs.editorComponent.$refs.canvasContainer;
            const scope = canvasContainer && typeof canvasContainer.getCanvasScope === 'function'
                ? canvasContainer.getCanvasScope() : null;
            if (!scope || !scope.view) { this.legendPreviewStyle = { display: 'none' }; this.legendClipStyle = { display: 'none' }; this.hasLegendSymbols = false; return; }

            const canvasEl = scope.view.element;
            const previewEl = this.$refs.canvasOverlays && typeof this.$refs.canvasOverlays.getLegendCanvasEl === 'function'
                ? this.$refs.canvasOverlays.getLegendCanvasEl() : null;
            if (!canvasEl || !canvasEl.isConnected || !previewEl) {
                this.legendPreviewStyle = { display: 'none' };
                this.legendClipStyle = { display: 'none' };
                this.hasLegendSymbols = false;
                return;
            }

            const bgBounds = canvasContainer.getBackgroundBounds ? canvasContainer.getBackgroundBounds() : null;
            if (!bgBounds) { this.legendPreviewStyle = { display: 'none' }; this.legendClipStyle = { display: 'none' }; this.hasLegendSymbols = false; return; }

            const samples = {};
            // See legendRenderer.js's drawCombinedLegend for the full
            // rationale: among several real (non-hidden) positions, prefer
            // whichever document's `legendUpdatedAt` is latest; only fall
            // back to an explicit "hidden" when NO sibling — including the
            // item currently being edited — set a real one. Otherwise this
            // item's own "hidden" (a holdover from the old per-item legend)
            // would blank the live preview even while a sibling on the same
            // photo has a real symbol + position.
            let meta = null;
            let hiddenMeta = null;
            const collectFrom = (rawJson) => {
                if (!rawJson) return;
                let json = rawJson;
                try {
                    if (typeof json === 'string') json = JSON.parse(json);
                    if (typeof json === 'string') json = JSON.parse(json);
                } catch (_) { return; }
                collectSymbolSamples(json, TOPO_SYMBOL_TYPES, samples);
                const m = findLegendMeta(json);
                if (!m) return;
                if (m.position === 'hidden') {
                    if (!hiddenMeta || (m.updatedAt || 0) > (hiddenMeta.updatedAt || 0)) hiddenMeta = m;
                } else if (!meta || (m.updatedAt || 0) > (meta.updatedAt || 0)) {
                    meta = m;
                }
            };
            // Order here no longer decides the outcome on its own (bug fixed
            // September 2026, round 6, reported as "legend position and size
            // is not changing"): `legendUpdatedAt` (see DrawingTools.vue's
            // rebuildLegend) only advances on an EXPLICIT toolbar choice, so
            // whichever item the admin actually just touched — sibling or
            // the one currently being edited — wins regardless of which gets
            // collected first. Siblings are still collected before
            // `activeJsonProp` so two legacy documents with no timestamp at
            // all (both `updatedAt` 0) keep resolving to the SAME sibling
            // call after call, same stable fallback the previous fix relied
            // on entirely.
            this._overlaySiblingJsons().forEach(collectFrom);
            collectFrom(this.activeJsonProp);

            const entries = TOPO_SYMBOL_TYPES.filter(t => samples[t.key]).map(t => ({ ...t, sample: samples[t.key] }));
            // Independent of `resolvedMeta.position === 'hidden'` below on
            // purpose — the toolbar's Position/Size controls should stay
            // visible whenever there's SOME symbol to legend, even while its
            // resolved position happens to be "hidden" (the admin may well
            // want to use those very controls to un-hide it).
            this.hasLegendSymbols = entries.length > 0;
            const resolvedMeta = meta || hiddenMeta || { position: 'top-right', scale: 1 };
            if (!entries.length || resolvedMeta.position === 'hidden') {
                this.legendPreviewStyle = { display: 'none' };
                this.legendClipStyle = { display: 'none' };
                return;
            }

            const ctx = previewEl.getContext('2d');
            const translate = (key) => this.$t(`admin.articles.canvas_editor.${key}`);
            // PROJECT-space multiplier (same units the real per-item legend
            // used to use) — the Paper.js canvas' own zoom (applied below via
            // toScreen) is what makes this preview grow/shrink with the
            // photo; this only reflects the toolbar's Size picker.
            let scale = Math.max(0.3, resolvedMeta.scale || 1);

            // Measure-then-draw: draw once (to a scratch buffer) to learn the
            // card's real (project-space) size at this scale.
            previewEl.width = 400; previewEl.height = 400;
            let size = drawLegendCard(ctx, entries, { scale, drawItem, translate });
            // Never let the live preview render bigger than the photo it's
            // overlaid on — a real bug, fixed September 2026, round 11
            // (reported directly: "editor [legend] is very big... make
            // editor legend sizing like [the public viewer]", which already
            // got this same cap in round 9/10 — see
            // SectorLocalImageCanvasComponent.vue's `_legendLayout`). Unlike
            // the viewer, this preview has no `cssScale`/native-resolution
            // correction at all — it's already meant to sit in the SAME
            // logical (CSS-pixel-equivalent) space `bgBounds` is measured
            // in, so this scale should normally just be the toolbar's own
            // Size picker (0.3-2.5) — but a container/view-size mismatch
            // (e.g. the editor's layout reflowing after the Paper.js view
            // was first sized, without the view being re-fit to match) can
            // still inflate it well past that. Capping directly against
            // `bgBounds` (already computed above, same project-space units
            // `size` is in) is robust to the exact cause: whatever inflated
            // it, the preview still ends up no bigger than the photo.
            const overflow = Math.max(1, size.width / (bgBounds.width * 0.9), size.height / (bgBounds.height * 0.9));
            if (overflow > 1) {
                scale = scale / overflow;
                size = drawLegendCard(ctx, entries, { scale, drawItem, translate });
            }
            previewEl.width = Math.ceil(size.width);
            previewEl.height = Math.ceil(size.height);
            drawLegendCard(ctx, entries, { scale, drawItem, translate });

            // Position the card's PROJECT-space top-left corner relative to
            // the background photo's own bounds — same 8-corner convention
            // as DrawingTools.vue's _positionLegend.
            const margin = 12;
            const right = bgBounds.left + bgBounds.width, bottom = bgBounds.top + bgBounds.height;
            let px, py;
            switch (resolvedMeta.position) {
                case 'top-left':  px = bgBounds.left + margin;                          py = bgBounds.top + margin; break;
                case 'top-mid':   px = bgBounds.left + (bgBounds.width - size.width)/2; py = bgBounds.top + margin; break;
                case 'top-right': px = right - size.width - margin;                     py = bgBounds.top + margin; break;
                case 'left-mid':  px = bgBounds.left + margin;                          py = bgBounds.top + (bgBounds.height - size.height)/2; break;
                case 'right-mid': px = right - size.width - margin;                     py = bgBounds.top + (bgBounds.height - size.height)/2; break;
                case 'bot-left':  px = bgBounds.left + margin;                          py = bottom - size.height - margin; break;
                case 'bot-mid':   px = bgBounds.left + (bgBounds.width - size.width)/2; py = bottom - size.height - margin; break;
                case 'bot-right': px = right - size.width - margin;                     py = bottom - size.height - margin; break;
                default:          px = right - size.width - margin;                     py = bgBounds.top + margin;
            }

            // Same devicePixelRatio-safe project->screen transform as
            // computeEditorLabels() above — toScreen returns coordinates
            // relative to the CANVAS's own top-left (no viewport/anchor
            // offset added); the clip wrapper below is positioned at that
            // offset instead, so (0,0) inside it always equals the canvas's
            // own (0,0).
            const anchorEl = this.$refs.canvasOverlayAnchor;
            if (!anchorEl) { this.legendPreviewStyle = { display: 'none' }; this.legendClipStyle = { display: 'none' }; return; }
            const rect = canvasEl.getBoundingClientRect();
            const anchorRect = anchorEl.getBoundingClientRect();
            const offLeft = rect.left - anchorRect.left;
            const offTop  = rect.top  - anchorRect.top;
            const viewSize = scope.view.viewSize;
            const scaleX = viewSize && viewSize.width  ? rect.width  / viewSize.width  : 1;
            const scaleY = viewSize && viewSize.height ? rect.height / viewSize.height : 1;
            const toScreen = (x, y) => {
                const v = scope.view.projectToView({ x, y });
                return { x: v.x * scaleX, y: v.y * scaleY };
            };

            const topLeft = toScreen(px, py);
            const bottomRight = toScreen(px + size.width, py + size.height);

            // A real drawing element would be clipped by the canvas's own
            // edges — this plain overlay wasn't, so zooming in enough to push
            // the card's anchor corner above/beside the visible canvas let it
            // float over unrelated page UI (the top navbar) instead of just
            // scrolling out of view. Fix: nest the preview canvas inside an
            // overflow:hidden wrapper sized to exactly match the Paper.js
            // canvas element's own bounding rect (positioned absolute,
            // relative to the shared position-relative ancestor, so it also
            // scrolls in native lockstep with the canvas), so the browser
            // clips anything that would spill outside it.
            this.legendClipStyle = {
                display: 'block',
                left: offLeft + 'px',
                top: offTop + 'px',
                width: rect.width + 'px',
                height: rect.height + 'px',
            };

            this.legendPreviewStyle = {
                display: 'block',
                left: topLeft.x + 'px',
                top: topLeft.y + 'px',
                width: Math.max(1, bottomRight.x - topLeft.x) + 'px',
                height: Math.max(1, bottomRight.y - topLeft.y) + 'px',
            };
        },
    },
};
