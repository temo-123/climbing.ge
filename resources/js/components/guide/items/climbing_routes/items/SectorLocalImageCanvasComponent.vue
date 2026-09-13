<template>
    <div class="sector-local-canvas-wrap" ref="wrap">
        <!-- The SAME canvas element (drawing, hover, click all shared) moves to a
             fullscreen backdrop when expanded instead of being replaced by a plain
             <img> — Teleport's :disabled prop renders it in place when not open, so
             this needs no separate "big view" markup or duplicated draw logic, and
             the shape overlay + click-to-scroll-to-sector keep working when expanded. -->
        <Teleport to="body" :disabled="!open_img">
            <div :class="open_img ? 'open_img' : null" @click="open_img && close_image()">
                <div v-if="open_img" class="close_bottom cursor_zoom_out" @click.stop="close_image()">X</div>
                <canvas
                    ref="canvas"
                    :class="open_img ? 'sector-local-canvas-big cursor_zoom_out' : 'sector-local-canvas'"
                    @mousemove="onMouseMove"
                    @mouseleave="onMouseLeave"
                    @click="onClick"
                />
                <!-- Tooltip: position:fixed uses raw viewport coords, which stay
                     correct whether the canvas is inline or teleported fullscreen. -->
                <div
                    v-if="hoveredSector"
                    class="canvas-sector-tooltip"
                    :style="{ left: tooltipX + 'px', top: tooltipY + 'px' }"
                >
                    {{ hoveredSector.sectorName }}
                </div>

                <button
                    v-if="image_src && !open_img"
                    type="button"
                    class="canvas-open-image-btn"
                    :title="$t('guide.open_image')"
                    :aria-label="$t('guide.open_image')"
                    @click.stop="open_image()"
                >
                    <i class="fa fa-expand"></i>
                </button>
            </div>
        </Teleport>
    </div>
</template>

<script>
import { drawItem, drawItemScaled } from '../../../../../services/canvas/paperJsonRenderer.js';
import { TOPO_SYMBOL_TYPES, LEGEND_CATEGORIES } from '../../../../user/items/canvas/assets/canvas/tools/topoSymbolTypes.js';

export default {
    props: {
        image_src:  { default: null },   // sector local image URL
        layouts:    { default: () => [] }, // array of {id, json, sector_id, sector: {id, name, url_title}}
    },
    emits: ['sector-click'],
    data() {
        return {
            ctx:            null,
            imgEl:          null,
            imgW:           0,
            imgH:           0,
            parsedLayouts:  [],  // [{sectorId, sectorName, sectorUrlTitle, shapes:[{type,x,y,w,h,rx,ry}], symbolSamples}]
            hoveredSector:  null,
            hoveredIdx:     -1,
            tooltipX:       0,
            tooltipY:       0,

            open_img:       false,
        };
    },
    watch: {
        layouts(newVal) {
            if (newVal && newVal.length) this.parseLayouts();
        },
        image_src() { this.loadImage(); },
    },
    mounted() {
        this.ctx = this.$refs.canvas.getContext('2d');
        if (this.image_src) this.loadImage();

        this._closeHandler = () => {
            if (this.open_img) {
                this.open_img = false;
                document.body.classList.remove('body_hiden');
            }
        };
        window.addEventListener('imageclose', this._closeHandler);

        // The legend's size (see drawLegends) is computed relative to this
        // canvas element's CURRENT css-rendered width — anything that
        // changes that width (a window resize, or toggling fullscreen below,
        // which swaps this same canvas to a much wider CSS class via
        // Teleport) needs a redraw, since neither happens through the
        // layouts/image_src props this component already watches.
        this._resizeHandler = () => this.draw(-1);
        window.addEventListener('resize', this._resizeHandler);
    },
    beforeUnmount() {
        window.removeEventListener('imageclose', this._closeHandler);
        window.removeEventListener('resize', this._resizeHandler);
        this._removePopstateHandler();
        if (this.open_img) {
            window.__imageOpen = false;
            document.body.classList.remove('body_hiden');
        }
    },
    methods: {
        open_image() {
            this.open_img = true;
            window.__imageOpen = true;
            document.body.classList.add('body_hiden');
            this._savedUrl = location.href;
            history.pushState({ imageOpen: true }, '', location.href);
            this._popstateHandler = () => {
                // Back button pressed while image is open — close image and stay on same URL
                history.pushState(null, '', this._savedUrl);
                this.close_image();
            };
            window.addEventListener('popstate', this._popstateHandler);
            // Teleporting to the fullscreen backdrop changes this canvas'
            // CSS class (and so its rendered width) without touching any
            // prop this component watches — redraw once the DOM reflects it.
            this.$nextTick(() => this.draw(-1));
        },
        close_image() {
            if (!this.open_img) return;
            this.open_img = false;
            window.__imageOpen = false;
            document.body.classList.remove('body_hiden');
            this._removePopstateHandler();
            this.$nextTick(() => this.draw(-1));
        },
        _removePopstateHandler() {
            if (this._popstateHandler) {
                window.removeEventListener('popstate', this._popstateHandler);
                this._popstateHandler = null;
            }
        },
        loadImage() {
            const img = new Image();
            img.onload = () => {
                this.imgEl = img;
                this.imgW  = img.naturalWidth;
                this.imgH  = img.naturalHeight;
                const canvas = this.$refs.canvas;
                canvas.width  = this.imgW;
                canvas.height = this.imgH;
                if (this.layouts && this.layouts.length) this.parseLayouts();
                else this.draw(-1);
            };
            img.onerror = () => {
                // Image not found — show a minimal placeholder canvas
                const canvas = this.$refs.canvas;
                canvas.width  = 800;
                canvas.height = 400;
                const ctx = this.ctx;
                ctx.fillStyle = '#e9ecef';
                ctx.fillRect(0, 0, 800, 400);
                ctx.fillStyle = '#adb5bd';
                ctx.font = '20px sans-serif';
                ctx.textAlign = 'center';
                ctx.fillText(this.$t('guide.image_not_available'), 400, 200);
            };
            img.src = this.image_src;
        },

        parseLayouts() {
            this.parsedLayouts = this.layouts
                .filter(l => l.json)
                .map(l => {
                    let json = l.json;
                    try {
                        if (typeof json === 'string') json = JSON.parse(json);
                        if (typeof json === 'string') json = JSON.parse(json);
                    } catch(_) { json = null; }

                    // Scale Paper.js canvas coordinates to natural image coordinates.
                    // The editor fits the background photo with a uniform COVER scale,
                    // centered in the Paper.js view — the photo does not necessarily
                    // start at view-space (0,0) or exactly fill canvas_width/canvas_height
                    // (see CanvasManager.vue's loadBackgroundRaster). bg_width/bg_height
                    // is the photo's own actual size within that view, and bg_left/bg_top
                    // its own top-left offset — prefer those; canvas_width/canvas_height
                    // alone (assuming zero offset) is only a fallback for legacy rows
                    // saved before bg_* was tracked. Same formula as
                    // CanvasJsonDataShowComponent's _itemScale/_itemOffset.
                    const bw = l.bg_width  || l.canvas_width  || 0;
                    const bh = l.bg_height || l.canvas_height || 0;
                    const scaleX = (bw > 0 && this.imgW > 0) ? this.imgW / bw : 1;
                    const scaleY = (bh > 0 && this.imgH > 0) ? this.imgH / bh : 1;
                    const offsetX = l.bg_left || 0;
                    const offsetY = l.bg_top  || 0;

                    const raw = json ? this.extractShapes(json) : { shapes: [], label: null, line: null, symbolSamples: {} };
                    const scalePoint = (x, y) => ({ x: (x - offsetX) * scaleX, y: (y - offsetY) * scaleY });
                    const shapes = raw.shapes.map(s => {
                        if (s.type === 'rect') {
                            return { type: 'rect', x: (s.x - offsetX) * scaleX, y: (s.y - offsetY) * scaleY, w: s.w * scaleX, h: s.h * scaleY };
                        }
                        if (s.type === 'ellipse') {
                            return { type: 'ellipse', x: (s.x - offsetX) * scaleX, y: (s.y - offsetY) * scaleY, rx: s.rx * scaleX, ry: s.ry * scaleY };
                        }
                        return s;
                    });

                    // The REAL sector-name label + leader line the admin actually
                    // placed/dragged in the editor (see sectorLocaleImageEditorComponent's
                    // addOrUpdateSectorLabel) — rescaled into the same natural-image
                    // coordinate space as the shapes above, same formula. Only present
                    // once an admin has clicked "Add/Update Sector Label" for this
                    // layout; older/never-labeled layouts fall back to an auto-computed
                    // position in drawSectorLabels().
                    let sectorLabel = null;
                    if (raw.label) {
                        const tl = scalePoint(raw.label.x, raw.label.y);
                        sectorLabel = { x: tl.x, y: tl.y, w: raw.label.w * scaleX, h: raw.label.h * scaleY };
                    }
                    let sectorLabelLine = null;
                    if (raw.line) {
                        const p1 = scalePoint(raw.line.x1, raw.line.y1);
                        sectorLabelLine = { x: p1.x, y: p1.y };
                    }

                    return {
                        layoutId:       l.id,
                        sectorId:       l.sector_id,
                        sectorName:     l.sector ? l.sector.name     : '',
                        sectorUrlTitle: l.sector ? l.sector.url_title : '',
                        shapes,
                        sectorLabel,
                        sectorLabelLine,
                        // One representative raw JSON node per DISTINCT topo-
                        // symbol type found in THIS layout's own saved JSON
                        // (see extractShapes) — real, untouched colors, since
                        // this is this sector's OWN document, never a tinted
                        // reference overlay. Combined across every layout in
                        // _buildCombinedLegendEntries() to draw ONE legend for
                        // the whole photo, computed fresh on every render
                        // (never baked/saved anywhere, so it can't go stale
                        // or disagree with itself between sectors).
                        symbolSamples: raw.symbolSamples,
                        // This sector's own saved legend position/scale
                        // preference (see extractShapes' Layer-branch
                        // capture) — used to position the ONE combined
                        // legend (first layout in array order wins).
                        legendMeta: raw.legendMeta,
                        // The FULL untouched saved JSON + its own bg_*/canvas_*
                        // meta (needed by drawItemScaled to redo the SAME
                        // scale/offset math this method already did above for
                        // shapes/labels, but against this photo's native
                        // resolution) — used by draw() to render every topo
                        // symbol/POI marker/line the admin actually drew, not
                        // just the sector-boundary rectangle extracted into
                        // `shapes` above. Bug fixed (September 2026): this
                        // component only ever drew the boundary rect/ellipse +
                        // the legend — every individual bolt/rappel/POI marker
                        // visible in the admin editor was completely invisible
                        // on the public page.
                        rawMeta: json ? {
                            json,
                            bg_width: l.bg_width, bg_height: l.bg_height,
                            bg_left: l.bg_left, bg_top: l.bg_top,
                            canvas_width: l.canvas_width, canvas_height: l.canvas_height,
                        } : null,
                    };
                })
                // Keep a layout that has raw drawing content (topo symbols/POI
                // markers/lines) even without a sector-boundary rectangle —
                // the boundary shape was previously required for a layout to
                // render at ALL, which silently hid every symbol on a layout
                // the admin never bothered drawing a boundary box for.
                .filter(l => l.shapes.length > 0 || l.rawMeta);
            this.draw(-1);
        },

        // Walk Paper.js exported JSON and extract bounding shapes for hit detection,
        // plus (when present) the real saved sector-name label box + leader-line
        // anchor. DrawingTools creates all shapes as paper.Path (rect, circle,
        // ellipse) with data.isRectangle / data.isCircle / data.isEllipse flags.
        extractShapes(json) {
            const shapes = [];
            let label = null;
            let line  = null;
            let legendMeta = null;
            const symbolSamples = {};

            const anchorPoint = (s) => {
                if (!Array.isArray(s)) return { x: 0, y: 0 };
                // [[x,y], handleIn, handleOut] or [x, y]
                const p = Array.isArray(s[0]) ? s[0] : s;
                return Array.isArray(p) ? { x: p[0], y: p[1] } : { x: p.x || 0, y: p.y || 0 };
            };

            const walk = (item) => {
                if (!Array.isArray(item) || item.length < 2) return;
                const [type, data] = item;
                if (!data || typeof data !== 'object') return;

                if (type === 'Layer' || type === 'Project') {
                    const name = data.name || '';
                    if (name === 'background' || name.startsWith('related-')) return;
                    // The admin's own position/scale choice for THIS sector's
                    // legend (see DrawingTools.vue's rebuildLegend, which
                    // stores it on the 'main' layer's own .data regardless of
                    // whether a legend is actually present) — used as the
                    // combined legend's position/scale in drawLegends() below
                    // (first layout found wins), so the toolbar picker the
                    // admin already sees and uses isn't a dead control that
                    // silently does nothing for what's actually shown
                    // publicly.
                    const ld = data.data || {};
                    if (!legendMeta && (ld.legendPosition || ld.legendScale)) {
                        legendMeta = { position: ld.legendPosition || 'top-right', scale: ld.legendScale || 1 };
                    }
                    if (data.layers)   data.layers.forEach(walk);
                    if (data.children) data.children.forEach(walk);
                } else if (type === 'Group' || type === 'CompoundPath') {
                    // The sector-name label (see sectorLocaleImageEditorComponent.vue's
                    // _createSectorLabel) is a real UI element living in the same
                    // 'main' layer as the sector's own rectangle — never a
                    // sector-boundary shape, so its subtree is never walked for
                    // rectangle candidates. Its bg box IS extracted here though —
                    // this is the actual saved position the admin dragged it to,
                    // used by drawSectorLabels() instead of a freshly recomputed
                    // default (see that method for why this was a real bug: two
                    // independent systems disagreeing on where the label sits).
                    const gd = data.data || {};
                    if (gd.isSectorLabel) {
                        const bgChild = (data.children || []).find(c => Array.isArray(c) && c[0] === 'Path' && c[1] && c[1].closed);
                        const segs = bgChild ? (bgChild[1].segments || []) : [];
                        if (segs.length >= 3) {
                            const pts = segs.map(anchorPoint);
                            const xs = pts.map(p => p.x), ys = pts.map(p => p.y);
                            const x = Math.min(...xs), y = Math.min(...ys);
                            label = { x, y, w: Math.max(...xs) - x, h: Math.max(...ys) - y };
                        }
                        return;
                    }
                    // A leftover per-sector legend from before the combined-
                    // legend system (see DrawingTools.vue's rebuildLegend) —
                    // skip its whole subtree: it's derived/inert content, and
                    // its cloned icons must never be picked up below as if
                    // they were a real instance of that symbol.
                    if (gd.isLegend) {
                        return;
                    }
                    // One representative raw node per distinct topo-symbol
                    // type (see topoSymbolTypes.js) — captured here (Group
                    // level) since every multi-part symbol (rappel/anchor/
                    // POI/landmark) carries its role flags on the GROUP, not
                    // its child paths. First match wins; combined across
                    // every sector's own layout in _buildCombinedLegendEntries().
                    TOPO_SYMBOL_TYPES.forEach(t => {
                        if (!symbolSamples[t.key] && t.match(gd)) symbolSamples[t.key] = item;
                    });
                    if (data.children) data.children.forEach(walk);
                } else if (type === 'Path') {
                    const d = data.data || {};
                    if (d.isSectorLabelLine) {
                        const segs = data.segments || [];
                        if (segs.length >= 1) {
                            const p0 = anchorPoint(segs[0]);
                            line = { x1: p0.x, y1: p0.y };
                        }
                        return;
                    }
                    // A handful of symbol types could in principle be a bare
                    // Path rather than a Group — same capture as above, at
                    // the Path level.
                    TOPO_SYMBOL_TYPES.forEach(t => {
                        if (!symbolSamples[t.key] && t.match(d)) symbolSamples[t.key] = item;
                    });

                    if (!data.closed) return;
                    const segs = data.segments || [];
                    if (segs.length < 3) return;

                    // BUG FIX: this previously treated ANY closed Path with 3+
                    // segments as a sector rectangle unless flagged circle/
                    // ellipse — it never actually checked for isRectangle. That
                    // was harmless while a layout only ever contained the one
                    // rectangle the admin drew, but the sector-name label added
                    // a SECOND closed shape (its yellow background box) into
                    // the exact same saved JSON, which this then misidentified
                    // as a second "sector boundary" — a phantom overlay
                    // rectangle floating near the label, and corrupted hit-
                    // testing/bounding-box math (_layoutBounds) for the real
                    // one. Only shapes explicitly drawn with the rectangle/
                    // circle/ellipse tool (data.isRectangle/isCircle/isEllipse)
                    // are sector-boundary shapes now.
                    if (!(d.isRectangle || d.isCircle || d.isEllipse)) return;

                    // Compute bounding box from anchor points
                    const pts = segs.map(anchorPoint);
                    const xs = pts.map(p => p.x), ys = pts.map(p => p.y);
                    const x = Math.min(...xs), y = Math.min(...ys);
                    const w = Math.max(...xs) - x, h = Math.max(...ys) - y;

                    if (d.isCircle || d.isEllipse) {
                        shapes.push({ type: 'ellipse', x: x + w / 2, y: y + h / 2, rx: w / 2, ry: h / 2 });
                    } else {
                        shapes.push({ type: 'rect', x, y, w, h });
                    }
                }
            };

            if (Array.isArray(json) && json.length && Array.isArray(json[0])) json.forEach(walk);
            else walk(json);
            return { shapes, label, line, symbolSamples, legendMeta };
        },

        draw(highlightIdx) {
            if (!this.ctx || !this.imgEl) return;
            const ctx = this.ctx;
            const w   = this.imgW, h = this.imgH;
            ctx.clearRect(0, 0, w, h);
            ctx.drawImage(this.imgEl, 0, 0, w, h);

            this.parsedLayouts.forEach((layout, i) => {
                const isHover = i === highlightIdx;
                layout.shapes.forEach(shape => {
                    ctx.save();
                    ctx.strokeStyle = isHover ? '#00e64d' : 'rgba(255,200,0,0.9)';
                    ctx.lineWidth   = isHover ? Math.max(3, w * 0.004) : Math.max(2, w * 0.003);
                    ctx.fillStyle   = isHover ? 'rgba(0,230,77,0.18)' : 'rgba(255,200,0,0.12)';

                    if (shape.type === 'rect') {
                        ctx.fillRect(shape.x, shape.y, shape.w, shape.h);
                        ctx.strokeRect(shape.x, shape.y, shape.w, shape.h);
                    } else if (shape.type === 'ellipse') {
                        ctx.beginPath();
                        ctx.ellipse(shape.x, shape.y, shape.rx, shape.ry, 0, 0, Math.PI * 2);
                        ctx.fill();
                        ctx.stroke();
                    }
                    ctx.restore();
                });
            });

            // Every topo symbol/POI marker/line the admin actually drew —
            // previously this component only ever drew the sector-boundary
            // rectangle (above) and the legend (below), so anything drawn
            // with the topo-symbol/POI/freehand tools in the admin editor
            // was completely invisible here (a real bug, fixed September
            // 2026). Drawn in each layout's own REAL authored colors (null
            // style overrides), same as the admin editor shows them — this
            // page has no "reference vs. selected" highlight concept, every
            // sector's content is just always shown. Skips isSectorLabel/
            // isSectorLabelLine — drawSectorLabels() below draws that with
            // its own styling/leader-line; drawing it again here would
            // double it, the same class of bug the shared isLegend skip
            // already prevents for the legend.
            this.parsedLayouts.forEach(layout => {
                if (!layout.rawMeta) return;
                try {
                    drawItemScaled(ctx, layout.rawMeta, w, h, null, null, null, 1, 1, ['isSectorLabel', 'isSectorLabelLine']);
                } catch (_) {}
            });

            this.drawSectorLabels(highlightIdx);
            this.drawLegends();
        },

        // Builds the ONE combined legend for this whole photo — the union of
        // every DISTINCT topo-symbol type found across ALL sectors' own
        // saved layouts (see parseLayouts/extractShapes' symbolSamples),
        // first-found-wins per type in `this.layouts`' own order. Computed
        // fresh on every draw() call, straight from each sector's own
        // untouched JSON — never baked/saved as a snapshot anywhere, so it
        // can never go stale, disagree between sectors, get corrupted with
        // reference-overlay tint colors (there IS no reference-overlay
        // concept on this public viewer), or fail to clear itself once every
        // symbol is deleted.
        _buildCombinedLegendEntries() {
            const samples = {};
            this.parsedLayouts.forEach(l => {
                const s = l.symbolSamples;
                if (!s) return;
                Object.keys(s).forEach(key => {
                    if (!samples[key]) samples[key] = s[key];
                });
            });
            return TOPO_SYMBOL_TYPES
                .filter(t => samples[t.key])
                .map(t => ({ ...t, sample: samples[t.key] }));
        },

        // Bounding box of an arbitrary raw Paper.js JSON node (Group/
        // CompoundPath/Path), in that node's OWN local coordinate space —
        // used to fit a symbol sample into a fixed-size legend icon
        // regardless of which sector's own (possibly differently-scaled)
        // JSON it came from.
        _rawNodeBounds(node) {
            let minX = Infinity, minY = Infinity, maxX = -Infinity, maxY = -Infinity;
            const anchorPoint = (s) => {
                if (!Array.isArray(s)) return { x: 0, y: 0 };
                const p = Array.isArray(s[0]) ? s[0] : s;
                return Array.isArray(p) ? { x: p[0], y: p[1] } : { x: p.x || 0, y: p.y || 0 };
            };
            const visit = (n) => {
                if (!Array.isArray(n) || n.length < 2) return;
                const [type, data] = n;
                if (!data) return;
                if (type === 'Group' || type === 'CompoundPath') {
                    if (data.children) data.children.forEach(visit);
                } else if (type === 'Path') {
                    const segs = data.segments || [];
                    const sw = (data.strokeWidth || 0) / 2;
                    segs.map(anchorPoint).forEach(p => {
                        minX = Math.min(minX, p.x - sw); maxX = Math.max(maxX, p.x + sw);
                        minY = Math.min(minY, p.y - sw); maxY = Math.max(maxY, p.y + sw);
                    });
                }
            };
            visit(node);
            if (!isFinite(minX)) return null;
            return { left: minX, top: minY, right: maxX, bottom: maxY };
        },

        // Resolves the ONE combined legend's position/scale — the first
        // sector (in `this.layouts` order) that has EVER had its own legend
        // position/scale picker touched (see extractShapes' Layer-branch
        // capture) AND set it to something other than "hidden" wins; falls
        // back to 'top-right'/1 if none has. This is what makes the SAME
        // toolbar picker the admin already sees in every sector's own editor
        // actually control what shows up publicly, instead of that picker
        // silently doing nothing for the combined legend (which otherwise
        // always rendered top-right regardless).
        //
        // "hidden" is deliberately NOT preferred over a real position from a
        // LATER sector, even when an earlier one set it first — "hidden" is a
        // holdover from the old per-item legend, where it meant "I don't want
        // MY OWN legend showing." Letting whichever sector happens to sort
        // first veto the combined legend for every OTHER sector sharing this
        // photo was a real bug (fixed September 2026): a sector with no
        // symbols of its own, saved "hidden" back when a shared legend wasn't
        // a concept, silently blanked the legend for every sibling sector too.
        // Only suppress here when EVERY sector that set anything at all asked
        // to hide it (i.e. no real position exists anywhere).
        _resolveLegendMeta() {
            const real = this.parsedLayouts.find(l => l.legendMeta && l.legendMeta.position && l.legendMeta.position !== 'hidden');
            if (real) return real.legendMeta;
            const hidden = this.parsedLayouts.find(l => l.legendMeta && l.legendMeta.position === 'hidden');
            return (hidden && hidden.legendMeta) || { position: 'top-right', scale: 1 };
        },

        // Draws this photo's ONE combined legend, at the resolved position
        // (see _resolveLegendMeta) and auto-scaled by photo width, using the
        // shared paperJsonRenderer walker with null color overrides so every
        // icon keeps its REAL authored color (a route-line-style flat
        // recolor would collapse the whole multi-colored card into one
        // color — see CanvasJsonDataShowComponent.vue's drawItem for the
        // same class of bug this avoids). Nothing is drawn at all once no
        // sector has any symbol left, or the resolved position is 'hidden'.
        // Mirrors legendRenderer.js's groupEntriesByCategory (see there for
        // the full rationale) — this component draws the legend directly
        // onto the shared photo raster rather than importing that module, so
        // the grouping logic is duplicated here to keep the two visually in
        // sync (same convention already used for the rest of this legend
        // drawing code, see the other comments in drawLegends()).
        _groupLegendEntriesByCategory(rows) {
            const present = new Set(rows.map(r => r.category));
            if (present.size <= 1) return [{ category: null, rows }];
            const groups = [];
            LEGEND_CATEGORIES.forEach(cat => {
                const catRows = rows.filter(r => r.category === cat.key);
                if (catRows.length) groups.push({ category: cat, rows: catRows });
            });
            const uncategorized = rows.filter(r => !LEGEND_CATEGORIES.some(c => c.key === r.category));
            if (uncategorized.length) groups.push({ category: null, rows: uncategorized });
            return groups;
        },

        drawLegends() {
            const entries = this._buildCombinedLegendEntries();
            if (!entries.length) return;

            const meta = this._resolveLegendMeta();
            if (meta.position === 'hidden') return;

            const ctx = this.ctx, w = this.imgW, h = this.imgH;
            // This canvas is a flat raster at the photo's NATIVE resolution,
            // CSS-scaled (usually down) to fit whatever width its container
            // happens to be. The admin editor, by contrast, sizes the legend
            // in Paper.js's LOGICAL (CSS-pixel-equivalent) units — completely
            // independent of the photo's native resolution — so it always
            // looks the same relative size regardless of how big the source
            // photo is. Scaling by native-px-per-CSS-px here reproduces that:
            // drawing BIGGER in raw canvas pixels whenever the CSS-displayed
            // size is smaller than native, so after the browser's own
            // downscale it lands back at a roughly CONSTANT ~13 CSS-pixel
            // font — matching the editor instead of drifting with photo
            // resolution or container width (which a native-pixel-width-based
            // formula, e.g. "w / 1200", has no way to account for).
            const canvasEl = this.$refs.canvas;
            const cssWidth = canvasEl ? canvasEl.getBoundingClientRect().width : 0;
            // Clamp range widened from the original [0.5, 6] — a common large
            // source photo (e.g. ~3000px, this app's own upload cap) shown at
            // a normal narrow content-column CSS width (e.g. ~500-600px)
            // already needs a ratio above 6, so the old ceiling was clipping
            // in exactly the common case, making the legend land smaller than
            // the editor's own ~13px-at-default-zoom baseline instead of
            // matching it exactly.
            const cssScale = cssWidth > 0 ? Math.max(0.3, Math.min(10, w / cssWidth)) : 1;
            const scale = cssScale * (meta.scale || 1);
            // iconBox/rowH bumped up (twice) to match legendRenderer.js's own
            // drawLegendCard (fixed September 2026, kept in sync per this
            // file's own header comment) — see its comment for why: more
            // room keeps a FILLED (no-stroke) glyph like the water-drop/
            // medical-cross icons recognizable, since minStrokePx/minFontPx
            // below can't help a shape with no stroke at all.
            const pad = 12 * scale, rowH = 40 * scale, iconBox = 38 * scale, gap = 10 * scale;
            const fontSize = 13 * scale, titleSize = 15 * scale, titleGap = 10 * scale;
            const headingSize = 11 * scale, headingH = 22 * scale;
            const margin = 12;

            const titleText = this.$t ? this.$t('admin.articles.canvas_editor.legend_title') : 'Legend';
            ctx.font = `bold ${titleSize}px Arial`;
            const titleWidth = ctx.measureText(titleText).width;

            const rows = entries.map(e => ({
                ...e,
                labelText: this.$t ? this.$t(`admin.articles.canvas_editor.${e.labelKey}`) : e.key,
            }));
            ctx.font = `bold ${fontSize}px Arial`;
            const labelWidths = rows.map(r => ctx.measureText(r.labelText).width);

            // Group into labeled sections (Topo symbols / Anchors / Landmarks
            // / Points of interest) so a combined legend spanning several
            // sectors' worth of symbols doesn't read as one undifferentiated
            // wall of rows — see _groupLegendEntriesByCategory above and
            // legendRenderer.js's drawLegendCard (the admin editor's own
            // preview mirrors this same grouping).
            const groups = this._groupLegendEntriesByCategory(rows).map(g => ({
                ...g,
                headingText: g.category
                    ? (this.$t ? this.$t(`admin.articles.canvas_editor.${g.category.labelKey}`) : g.category.key).toUpperCase()
                    : null,
            }));

            const contentWidth = Math.max(titleWidth, ...rows.map((r, i) => iconBox + gap + labelWidths[i]));
            const boxW = contentWidth + pad * 2;
            const contentH = groups.reduce((acc, g) => acc + (g.headingText ? headingH : 0) + g.rows.length * rowH, 0);
            const boxH = pad + titleSize + titleGap + contentH + pad;

            let x, y;
            switch (meta.position) {
                case 'top-left':    x = margin;                   y = margin; break;
                case 'top-mid':     x = (w - boxW) / 2;            y = margin; break;
                case 'top-right':   x = w - boxW - margin;         y = margin; break;
                case 'left-mid':    x = margin;                   y = (h - boxH) / 2; break;
                case 'right-mid':   x = w - boxW - margin;         y = (h - boxH) / 2; break;
                case 'bot-left':    x = margin;                   y = h - boxH - margin; break;
                case 'bot-mid':     x = (w - boxW) / 2;            y = h - boxH - margin; break;
                case 'bot-right':   x = w - boxW - margin;         y = h - boxH - margin; break;
                default:            x = w - boxW - margin;         y = margin;
            }

            ctx.save();
            ctx.fillStyle = 'rgba(255,255,255,0.9)';
            ctx.strokeStyle = '#333333';
            ctx.lineWidth = 1;
            ctx.fillRect(x, y, boxW, boxH);
            ctx.strokeRect(x, y, boxW, boxH);

            ctx.fillStyle = '#1a1a1a';
            ctx.textAlign = 'left';
            ctx.textBaseline = 'alphabetic';
            ctx.font = `bold ${titleSize}px Arial`;
            ctx.fillText(titleText, x + pad, y + pad + titleSize * 0.8);

            let rowY = y + pad + titleSize + titleGap;
            let firstGroup = true;
            groups.forEach(g => {
                if (g.headingText) {
                    // A thin rule above every heading EXCEPT the first (which
                    // already sits right under the title, where a second
                    // rule would just double up against it).
                    if (!firstGroup) {
                        ctx.strokeStyle = '#dddddd';
                        ctx.lineWidth = 1;
                        ctx.beginPath();
                        ctx.moveTo(x + pad, rowY);
                        ctx.lineTo(x + boxW - pad, rowY);
                        ctx.stroke();
                    }
                    ctx.fillStyle = '#777777';
                    ctx.font = `bold ${headingSize}px Arial`;
                    ctx.textAlign = 'left';
                    ctx.textBaseline = 'alphabetic';
                    ctx.fillText(g.headingText, x + pad, rowY + headingH - headingSize * 0.55);
                    rowY += headingH;
                }
                firstGroup = false;

                g.rows.forEach(r => {
                    const rowCenterY = rowY + rowH / 2;
                    const iconCenterX = x + pad + iconBox / 2;

                    const bounds = this._rawNodeBounds(r.sample);
                    if (bounds) {
                        const bw = bounds.right - bounds.left, bh = bounds.bottom - bounds.top;
                        const maxDim = Math.max(bw, bh) || 1;
                        const iconScale = iconBox / maxDim;
                        const localCenterX = (bounds.left + bounds.right) / 2;
                        const localCenterY = (bounds.top + bounds.bottom) / 2;

                        ctx.save();
                        ctx.translate(iconCenterX, rowCenterY);
                        ctx.scale(iconScale, iconScale);
                        ctx.translate(-localCenterX, -localCenterY);
                        // minStrokePx/minFontPx — see paperJsonRenderer.js's
                        // drawItem for the full rationale: without this, a
                        // POI marker's thin inner pictogram/letter (correct
                        // at its normal on-photo size) shrinks below a pixel
                        // once the whole marker is fit into this small fixed
                        // icon box, leaving every POI kind looking like the
                        // same plain teardrop.
                        try { drawItem(ctx, r.sample, null, null, null, 1, 1, { minStrokePx: 1.6, minFontPx: 10 }); } catch (_) {}
                        ctx.restore();
                    }

                    ctx.fillStyle = '#1a1a1a';
                    ctx.font = `bold ${fontSize}px Arial`;
                    ctx.textAlign = 'left';
                    ctx.textBaseline = 'alphabetic';
                    ctx.fillText(r.labelText, x + pad + iconBox + gap, rowCenterY + fontSize * 0.35);

                    rowY += rowH;
                });
            });

            ctx.restore();
        },

        // Bounding box covering ALL of a layout's shapes (usually just one
        // rectangle, but nothing stops a sector having more than one drawn
        // region on the same photo).
        _layoutBounds(shapes) {
            let minX = Infinity, minY = Infinity, maxX = -Infinity, maxY = -Infinity;
            shapes.forEach(s => {
                if (s.type === 'rect') {
                    minX = Math.min(minX, s.x);        minY = Math.min(minY, s.y);
                    maxX = Math.max(maxX, s.x + s.w);  maxY = Math.max(maxY, s.y + s.h);
                } else if (s.type === 'ellipse') {
                    minX = Math.min(minX, s.x - s.rx); minY = Math.min(minY, s.y - s.ry);
                    maxX = Math.max(maxX, s.x + s.rx); maxY = Math.max(maxY, s.y + s.ry);
                }
            });
            return { x: minX, y: minY, w: maxX - minX, h: maxY - minY };
        },

        // Permanent "guidebook overview" name labels — a yellow box connected
        // by a leader line up to each sector's rectangle, always visible (not
        // just on hover), matching how a printed crag-overview photo labels
        // every wall. Recomputed fresh on every draw() (cheap — a handful of
        // sectors at most) rather than cached, so a hover/resize redraw never
        // shows stale label boxes.
        //
        // Two sources, in priority order:
        //  1. layout.sectorLabel/sectorLabelLine — the REAL label+line the
        //     admin actually placed/dragged in the editor (see extractShapes).
        //     Drawn exactly where saved, no recomputation, so this always
        //     matches the editor. This used to be entirely ignored here —
        //     the public page recomputed its own default top-center guess
        //     regardless of what the admin had done, which looked like edits
        //     "not saving" even though the JSON was correct all along.
        //  2. Auto-computed default (top-center of the rectangle) — legacy
        //     fallback for a layout that has a rectangle but no saved label
        //     yet (never clicked "Add/Update Sector Label" in the editor).
        drawSectorLabels(highlightIdx) {
            const ctx = this.ctx, w = this.imgW;
            const fontSize = Math.max(14, Math.round(w * 0.013));
            ctx.font = `bold ${fontSize}px Arial`;
            const padX = fontSize * 0.7, padY = fontSize * 0.5;
            const labelH = fontSize + padY * 2;
            const gap = fontSize * 0.4;
            const marginTop = fontSize;
            // Matches the editor's own box-height/font-size ratio at creation
            // time (fontSize=15, padY=6 -> boxH=27 -> ratio 15/27) so text
            // drawn into a real saved box (whatever size it rescaled to) is
            // proportioned the same as it looked in the editor.
            const REAL_LABEL_FONT_RATIO = 15 / 27;

            const labels = this.parsedLayouts
                .map((layout, i) => {
                    if (!layout.sectorName) return null;

                    if (layout.sectorLabel && layout.sectorLabelLine) {
                        const box = layout.sectorLabel;
                        return {
                            idx: i,
                            text: layout.sectorName,
                            anchor: { x: layout.sectorLabelLine.x, y: layout.sectorLabelLine.y },
                            w: box.w, h: box.h,
                            x: box.x, y: box.y,
                            fontSize: Math.max(9, box.h * REAL_LABEL_FONT_RATIO),
                            isReal: true,
                        };
                    }

                    if (!layout.shapes.length) return null;
                    const b = this._layoutBounds(layout.shapes);
                    const boxW = ctx.measureText(layout.sectorName).width + padX * 2;
                    const anchor = { x: b.x + b.w / 2, y: b.y };
                    return {
                        idx: i,
                        text: layout.sectorName,
                        anchor,
                        w: boxW,
                        h: labelH,
                        x: anchor.x - boxW / 2,
                        y: Math.max(marginTop, anchor.y - labelH - fontSize * 1.6),
                        fontSize,
                        isReal: false,
                    };
                })
                .filter(Boolean);

            // Simple left-to-right de-collision pass so adjacent labels don't
            // overlap when their sectors sit close together horizontally —
            // only applied to auto-computed labels. Real saved labels keep the
            // EXACT position the admin placed them at; nudging them here would
            // defeat the whole point of making them draggable/persisted.
            const autoLabels = labels.filter(l => !l.isReal);
            const realLabels = labels.filter(l => l.isReal);

            autoLabels.sort((a, b) => a.x - b.x);
            for (let i = 1; i < autoLabels.length; i++) {
                const prev = autoLabels[i - 1], cur = autoLabels[i];
                if (cur.x < prev.x + prev.w + gap) cur.x = prev.x + prev.w + gap;
            }
            // Keep the auto-computed row inside the canvas.
            const lastAuto = autoLabels[autoLabels.length - 1];
            const overflow = lastAuto ? (lastAuto.x + lastAuto.w) - w : 0;
            if (overflow > 0) autoLabels.forEach(l => { l.x -= overflow; });
            autoLabels.forEach(l => { if (l.x < 0) l.x = 0; });

            [...realLabels, ...autoLabels].forEach(label => {
                const isHover = label.idx === highlightIdx;
                const labelCenterX = label.x + label.w / 2;

                ctx.save();
                ctx.strokeStyle = isHover ? '#00e64d' : '#1a1a1a';
                ctx.lineWidth   = Math.max(1.5, w * 0.0015);
                ctx.beginPath();
                ctx.moveTo(label.anchor.x, label.anchor.y);
                ctx.lineTo(labelCenterX, label.y + label.h);
                ctx.stroke();

                ctx.fillStyle   = isHover ? '#baffcf' : '#ffe100';
                ctx.strokeStyle = '#1a1a1a';
                ctx.lineWidth   = Math.max(1.5, w * 0.0018);
                ctx.fillRect(label.x, label.y, label.w, label.h);
                ctx.strokeRect(label.x, label.y, label.w, label.h);

                ctx.fillStyle    = '#1a1a1a';
                ctx.textAlign    = 'center';
                ctx.textBaseline = 'middle';
                ctx.font         = `bold ${Math.round(label.fontSize)}px Arial`;
                ctx.fillText(label.text, labelCenterX, label.y + label.h / 2);
                ctx.restore();
            });
        },

        hitTest(canvasX, canvasY) {
            for (let i = this.parsedLayouts.length - 1; i >= 0; i--) {
                for (const shape of this.parsedLayouts[i].shapes) {
                    if (shape.type === 'rect') {
                        if (canvasX >= shape.x && canvasX <= shape.x + shape.w &&
                            canvasY >= shape.y && canvasY <= shape.y + shape.h) return i;
                    } else if (shape.type === 'ellipse') {
                        const dx = (canvasX - shape.x) / shape.rx;
                        const dy = (canvasY - shape.y) / shape.ry;
                        if (dx*dx + dy*dy <= 1) return i;
                    }
                }
            }
            return -1;
        },

        canvasCoords(e) {
            const rect  = this.$refs.canvas.getBoundingClientRect();
            const scaleX = this.imgW / rect.width;
            const scaleY = this.imgH / rect.height;
            return { x: (e.clientX - rect.left) * scaleX, y: (e.clientY - rect.top) * scaleY };
        },

        onMouseMove(e) {
            const { x, y } = this.canvasCoords(e);
            const idx = this.hitTest(x, y);
            if (idx !== this.hoveredIdx) {
                this.hoveredIdx = idx;
                this.draw(idx);
                this.hoveredSector = idx >= 0 ? this.parsedLayouts[idx] : null;
                this.$refs.canvas.style.cursor = idx >= 0 ? 'pointer' : 'default';
            }
            if (idx >= 0) {
                // position:fixed tooltip — raw viewport coords stay correct whether
                // the canvas is inline (small) or teleported fullscreen (expanded).
                this.tooltipX = e.clientX + 12;
                this.tooltipY = e.clientY - 28;
            }
        },

        onMouseLeave() {
            this.hoveredIdx    = -1;
            this.hoveredSector = null;
            this.$refs.canvas.style.cursor = 'default';
            this.draw(-1);
        },

        onClick(e) {
            const { x, y } = this.canvasCoords(e);
            const idx = this.hitTest(x, y);
            if (idx < 0) {
                // Missed every shape — when expanded, this is a plain "click the
                // photo to close" (matches the old plain-<img> modal's behavior).
                if (this.open_img) this.close_image();
                return;
            }
            const layout = this.parsedLayouts[idx];
            this.$emit('sector-click', layout);
            // Close the expanded view first so the scroll animation is visible
            // against the real page instead of racing behind the fixed overlay.
            if (this.open_img) this.close_image();
            const sectorId = layout.sectorId;
            if (sectorId) {
                const el = document.querySelector('#sector-' + sectorId);
                if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        },
    },
};
</script>

<style scoped>
.sector-local-canvas-wrap {
    position: relative;
    display: block;
    line-height: 0;
}
.sector-local-canvas {
    width: 100%;
    height: auto;
    display: block;
    border-radius: 8px;
}
.canvas-sector-tooltip {
    position: fixed;
    background: rgba(0,0,0,0.75);
    color: #fff;
    padding: 4px 10px;
    border-radius: 4px;
    font-size: 13px;
    pointer-events: none;
    white-space: nowrap;
    z-index: 10;
}
.canvas-open-image-btn {
    position: absolute;
    top: 10px;
    right: 10px;
    z-index: 5;
    background: rgba(0,0,0,0.55);
    color: #fff;
    border: none;
    border-radius: 4px;
    width: 34px;
    height: 34px;
    cursor: zoom-in;
    transition: background .15s linear;
}
.canvas-open-image-btn:hover {
    background: rgba(0,0,0,0.8);
}
.cursor_zoom_out {
    cursor: zoom-out;
}
.close_bottom {
    float: right;
    cursor: pointer;
    color: #b3b2b2d9;
    font-size: 2em;
    margin-right: 0.4em;
    margin-top: 0.4em;
}
@media (max-width: 756px) {
    .close_bottom {
        position: absolute;
        left: 50%;
        right: 0;
        cursor: pointer;
        color: #b3b2b2d9;
        font-size: 2em;
        margin-right: 0.4em;
        margin-top: 0.4em;
    }
}
.open_img {
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    z-index: 1050;
    background: #000000d9;
    transition: opacity .15s linear;
}
.sector-local-canvas-big {
    max-width: 96%;
    max-height: 80%;
    width: auto;
    height: auto;
    display: block;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    border-radius: 8px;
    cursor: pointer;
}
</style>
