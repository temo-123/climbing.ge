<template>
    <div class="canvas-json-show-wrapper">
        <img
            v-if="image_src"
            ref="bgImg"
            :src="image_src"
            class="canvas-bg-img"
            @load="onImageLoad"
        />
        <canvas
            ref="canvas"
            class="canvas-overlay"
            :class="{ 'canvas-interactive': interactive }"
            @mousemove="onMouseMove"
            @mouseleave="onMouseLeave"
            @click="onCanvasClick"
        />
    </div>
</template>

<script>
import axios from 'axios';
import { drawCombinedLegend } from '../../services/canvas/legendRenderer.js';

export default {
    emits: ['item-click', 'item-hover'],

    props: {
        // Background image URL
        image_src:     { default: null },

        // Composite image whose pixel dimensions match the Paper.js coordinate space.
        // Pass when the background is a higher-res original so coordinates scale correctly.
        composite_src: { default: null },

        // ── Data source (pick one) ────────────────────────────────────────────────
        // Option A: pass pre-fetched data directly → [{ id, json }]. `json`
        // MUST already be a parsed object/array here — unlike Option B below,
        // this path does NOT parse anything (see the `items` computed). The
        // backend stores JSON.stringify(paper.exportJSON()), so a caller
        // reading straight from an API response needs its own double
        // `JSON.parse` first (see MTPModalComponent.vue's parsePitchJson() /
        // RouteModalComponent.vue's routeJsonItem() for the pattern) —
        // passing the raw string through un-parsed draws NOTHING at all with
        // no error (Array.isArray on a string is false, so drawItem's walker
        // no-ops on its very first check), which reads exactly like "the
        // composite image has the drawing baked in, but the interactive
        // canvas doesn't show it" — a real bug this shipped with once.
        json_items:    { default: null },

        // Option B: let the component auto-fetch — fetchData() below DOES
        // double-parse each item's json itself, so a raw API response works
        // here without the caller doing anything extra.
        fetch_url:     { default: null },   // e.g. 'get_route/get_route_jsons_for_sector_image'
        fetch_id:      { default: null },   // value passed as the fetch param
        fetch_param:   { default: 'sector_image_id' }, // query param name

        // ── Selection / interaction ────────────────────────────────────────────────
        // ID of the item to highlight green
        selected_id:   { default: null },
        // Enable mouse hover + click detection; emits 'item-click' and 'item-hover'
        interactive:   { type: Boolean, default: false },

        // ── Display modes ─────────────────────────────────────────────────────────
        // show_all   → draw every item dim + highlight selected_id green
        // preview_all → draw every item in color_default (admin overview)
        show_all:      { type: Boolean, default: false },
        preview_all:   { type: Boolean, default: false },

        // ── Bus event ─────────────────────────────────────────────────────────────
        // When this event fires the component re-fetches / re-renders.
        // Payload may include { sector_image_id }, { item_id }, or { id };
        // compared against fetch_id to decide whether this instance should react.
        refresh_event: { default: null },

        // ── Colors ────────────────────────────────────────────────────────────────
        color_default:  { default: '#cc2222' }, // non-selected items
        color_selected: { default: '#00e64d' }, // highlighted (selected) item
        color_hover:    { default: '#ffaa00' }, // hovered item
        color_dot:      { default: '#ffd700' }, // endpoint dots

        // ── Extra info layer ──────────────────────────────────────────────────────
        // A single general-purpose annotation drawing (SectorImageExtraDrawing /
        // SectorLocalImageExtraDrawing) — not tied to any one route/sector, so it's
        // not part of `items` and never selectable/hoverable. Shape:
        // { json, canvas_width, canvas_height, bg_left, bg_top, bg_width, bg_height }.
        extra_item:   { default: null },
        color_extra:  { default: '#2b6cff' }, // visually distinct from route red/green
    },

    data: () => ({
        fetchedItems:    {},
        canvasReady:     false,
        compositeWidth:  null,
        compositeHeight: null,
    }),

    computed: {
        // Each entry: { json, canvas_width, canvas_height, bg_left, bg_top, bg_width,
        // bg_height }. canvas_width/height are the Paper.js view size the drawing was
        // authored at; bg_* are the background photo's own actual position/size within
        // that view (see _itemScale/_itemOffset) — all null for legacy rows saved before
        // they were tracked, in which case rendering falls back to older, less precise scaling.
        items() {
            if (this.json_items) {
                const map = {};
                (this.json_items || []).forEach(item => {
                    if (item.id != null && item.json) {
                        map[item.id] = {
                            json: item.json,
                            canvas_width: item.canvas_width || null,
                            canvas_height: item.canvas_height || null,
                            bg_left: item.bg_left ?? null,
                            bg_top: item.bg_top ?? null,
                            bg_width: item.bg_width || null,
                            bg_height: item.bg_height || null,
                        };
                    }
                });
                return map;
            }
            return this.fetchedItems;
        },
    },

    watch: {
        fetch_id(v) {
            if (v) { this.fetchedItems = {}; this.canvasReady = false; this.fetchData(); }
        },
        json_items()   { this._hitPathsDirty = true; this.render(); },
        image_src()    { this.canvasReady = false; },
        selected_id()  { this.render(); },
        fetchedItems() { this._hitPathsDirty = true; this.render(); },
        show_all()     { this.render(); },
        extra_item()   { this.render(); },
        composite_src: {
            immediate: true,
            handler(src) {
                this.compositeWidth  = null;
                this.compositeHeight = null;
                if (!src) return;
                const img = new Image();
                img.onload = () => {
                    this.compositeWidth  = img.naturalWidth;
                    this.compositeHeight = img.naturalHeight;
                    if (this.canvasReady) this.render();
                };
                img.src = src;
                if (img.complete && img.naturalWidth > 0) {
                    this.compositeWidth  = img.naturalWidth;
                    this.compositeHeight = img.naturalHeight;
                }
            },
        },
    },

    mounted() {
        if (!this.json_items && this.fetch_url && this.fetch_id) this.fetchData();

        // Cached images may not fire @load — trigger manually if already complete.
        this.$nextTick(() => {
            const img = this.$refs.bgImg;
            if (img && img.complete && img.naturalWidth > 0) this.onImageLoad();
        });

        if (this.refresh_event) {
            this._refreshUnsub = this.$bus.$on(this.refresh_event, (payload) => {
                const payloadId = payload && (payload.sector_image_id ?? payload.item_id ?? payload.id);
                const matches   = payloadId == null || String(payloadId) === String(this.fetch_id);
                if (!matches) return;
                if (this.json_items) { this.render(); } else { this.fetchData(); }
            });
        }

        // Offscreen context for hit testing — 1×1 px, never drawn to screen
        const off = document.createElement('canvas');
        off.width = off.height = 1;
        this._hitCtx = off.getContext('2d');

        // Non-reactive internal state so hover doesn't trigger Vue re-renders
        this._hoveredId      = null;
        this._hitPaths       = {};   // { [id]: [{ type:'stroke'|'fill', path2d }] }
        this._hitPathsDirty  = true;
    },

    beforeUnmount() {
        if (this._refreshUnsub) this._refreshUnsub();
    },

    methods: {
        // ── Image load ────────────────────────────────────────────────────────────

        onImageLoad() {
            const img    = this.$refs.bgImg;
            const canvas = this.$refs.canvas;
            if (!img || !canvas) return;
            canvas.width  = img.naturalWidth  || 800;
            canvas.height = img.naturalHeight || 600;
            this.canvasReady = true;
            this._hitPathsDirty = true;
            this.render();
        },

        // ── Data fetch ────────────────────────────────────────────────────────────

        async fetchData() {
            if (!this.fetch_url || !this.fetch_id) return;
            try {
                const res = await axios.get(this.fetch_url, {
                    params: { [this.fetch_param]: this.fetch_id },
                });
                const map = {};
                (res.data || []).forEach(item => {
                    if (!item.route_id || !item.json) return;
                    try {
                        let parsed = typeof item.json === 'string' ? JSON.parse(item.json) : item.json;
                        if (typeof parsed === 'string') parsed = JSON.parse(parsed);
                        map[item.route_id] = {
                            json: parsed,
                            canvas_width: item.canvas_width || null,
                            canvas_height: item.canvas_height || null,
                            bg_left: item.bg_left ?? null,
                            bg_top: item.bg_top ?? null,
                            bg_width: item.bg_width || null,
                            bg_height: item.bg_height || null,
                        };
                    } catch (_) {}
                });
                this.fetchedItems = map;
            } catch (_) {}
        },

        // ── Matrix helpers ────────────────────────────────────────────────────────
        // All matrices are the 6-element canvas form [a, b, c, d, e, f]
        // representing: x' = a·x + c·y + e,  y' = b·x + d·y + f

        _mulM(a, b) {
            return [
                a[0]*b[0] + a[2]*b[1],
                a[1]*b[0] + a[3]*b[1],
                a[0]*b[2] + a[2]*b[3],
                a[1]*b[2] + a[3]*b[3],
                a[0]*b[4] + a[2]*b[5] + a[4],
                a[1]*b[4] + a[3]*b[5] + a[5],
            ];
        },
        _tPt(m, x, y)  { return { x: m[0]*x + m[2]*y + m[4], y: m[1]*x + m[3]*y + m[5] }; },
        _tVec(m, x, y) { return { x: m[0]*x + m[2]*y,         y: m[1]*x + m[3]*y         }; },

        // Each item may have been drawn in a different-sized browser container, so there is
        // no single scale that fits all of them — always resolve per item.
        //
        // The editor's Paper.js view fits the background photo with a uniform COVER
        // scale, centered in the view (see CanvasManager.vue's loadBackgroundRaster) —
        // the photo does NOT necessarily start at view-space (0,0) or exactly fill the
        // view's own width/height. Scaling by canvas_width/canvas_height (the raw view
        // size) alone silently assumed zero offset, which is exactly what let strokes
        // land in the wrong place once redrawn here. bg_width/bg_height (the photo's own
        // actual displayed size within that view) is the correct scale reference when
        // present; bg_left/bg_top (see _itemOffset) supplies the missing translation.
        // Falls back to the old view-size-only scale, then the composite-image-based
        // scale, for legacy rows saved before these were tracked.
        _itemScale(meta, canvas) {
            const bw = meta && meta.bg_width;
            const bh = meta && meta.bg_height;
            if (bw && bh) {
                return { sx: canvas.width / bw, sy: canvas.height / bh };
            }
            const cw = meta && meta.canvas_width;
            const ch = meta && meta.canvas_height;
            if (cw && ch) {
                return { sx: canvas.width / cw, sy: canvas.height / ch };
            }
            const sx = this.compositeWidth  ? (canvas.width  / this.compositeWidth)  : 1;
            const sy = this.compositeHeight ? (canvas.height / this.compositeHeight) : 1;
            return { sx, sy };
        },

        // The background photo's own top-left corner, in the same view-space
        // units the stroke coordinates are recorded in — zero for legacy rows
        // that predate this tracking (matches their old, offset-less behavior).
        _itemOffset(meta) {
            if (meta && meta.bg_left != null && meta.bg_top != null) {
                return { ox: meta.bg_left, oy: meta.bg_top };
            }
            return { ox: 0, oy: 0 };
        },

        // ── Hit-path building ─────────────────────────────────────────────────────
        // Builds Path2D objects in canvas-pixel space for each item so we can use
        // isPointInStroke / isPointInPath for accurate hit testing.

        _buildHitPaths() {
            const canvas = this.$refs.canvas;
            if (!canvas) return;

            const parseSeg = (s) => {
                if (!Array.isArray(s)) return null;
                if (Array.isArray(s[0])) {
                    const pt = s[0], hIn = s[1] || [0,0], hOut = s[2] || [0,0];
                    return { x: pt[0], y: pt[1], hIn, hOut };
                }
                return { x: s[0], y: s[1], hIn: [0,0], hOut: [0,0] };
            };

            const newHitPaths = {};

            Object.entries(this.items).forEach(([id, meta]) => {
                const json = meta.json;
                // Starting CTM converts this item's own Paper.js coords → canvas pixel coords.
                // Folds the background offset into e/f: translate-then-scale is equivalent to
                // (e,f) = (-sx*ox, -sy*oy), i.e. x' = sx*(x-ox), y' = sy*(y-oy).
                const { sx, sy } = this._itemScale(meta, canvas);
                const { ox, oy } = this._itemOffset(meta);
                const scaleM = [sx, 0, 0, sy, -sx * ox, -sy * oy];
                const paths = [];

                const walk = (item, ctm) => {
                    if (!Array.isArray(item) || item.length < 2) return;
                    const [type, data] = item;
                    if (!data || typeof data !== 'object') return;

                    if (type === 'Group' || type === 'CompoundPath') {
                        // A hidden item shouldn't be clickable either — it
                        // isn't drawn (see drawItem's own visible check
                        // below), so a stale hit region behind it would let
                        // the user click/hover an invisible shape.
                        if (data.visible === false) return;
                        const m   = data.matrix;
                        const ctm2 = (m && m.length >= 6) ? this._mulM(ctm, m) : ctm;
                        if (data.children) data.children.forEach(c => walk(c, ctm2));

                    } else if (type === 'Path') {
                        if (data.visible === false) return;
                        const segs = data.segments;
                        if (!segs || !segs.length) return;
                        const pm   = data.matrix;
                        const pCtm = (pm && pm.length >= 6) ? this._mulM(ctm, pm) : ctm;
                        const pts  = segs.map(parseSeg).filter(Boolean);
                        if (!pts.length) return;

                        const isDot = (data.name || '').startsWith('point ');
                        const p2d   = new Path2D();

                        if (isDot) {
                            // Reconstruct the bounding circle in canvas space
                            const wPts = pts.map(p => this._tPt(pCtm, p.x, p.y));
                            const minX = Math.min(...wPts.map(p => p.x));
                            const maxX = Math.max(...wPts.map(p => p.x));
                            const minY = Math.min(...wPts.map(p => p.y));
                            const maxY = Math.max(...wPts.map(p => p.y));
                            const r = Math.max(8, (maxX - minX) / 2 + 6); // slightly enlarged
                            p2d.arc((minX + maxX) / 2, (minY + maxY) / 2, r, 0, Math.PI * 2);
                            paths.push({ type: 'fill', path2d: p2d });
                        } else {
                            const p0 = this._tPt(pCtm, pts[0].x, pts[0].y);
                            p2d.moveTo(p0.x, p0.y);
                            for (let i = 1; i < pts.length; i++) {
                                const pr = pts[i - 1], cu = pts[i];
                                const prPt  = this._tPt(pCtm, pr.x, pr.y);
                                const cuPt  = this._tPt(pCtm, cu.x, cu.y);
                                const prOut = this._tVec(pCtm, pr.hOut[0], pr.hOut[1]);
                                const cuIn  = this._tVec(pCtm, cu.hIn[0],  cu.hIn[1]);
                                const cp1x = prPt.x + prOut.x, cp1y = prPt.y + prOut.y;
                                const cp2x = cuPt.x + cuIn.x,  cp2y = cuPt.y + cuIn.y;
                                if (cp1x === prPt.x && cp1y === prPt.y && cp2x === cuPt.x && cp2y === cuPt.y) {
                                    p2d.lineTo(cuPt.x, cuPt.y);
                                } else {
                                    p2d.bezierCurveTo(cp1x, cp1y, cp2x, cp2y, cuPt.x, cuPt.y);
                                }
                            }
                            if (data.closed && pts.length > 1) {
                                const la = pts[pts.length - 1], fi = pts[0];
                                const laPt  = this._tPt(pCtm, la.x, la.y);
                                const fiPt  = this._tPt(pCtm, fi.x, fi.y);
                                const laOut = this._tVec(pCtm, la.hOut[0], la.hOut[1]);
                                const fiIn  = this._tVec(pCtm, fi.hIn[0],  fi.hIn[1]);
                                p2d.bezierCurveTo(
                                    laPt.x + laOut.x, laPt.y + laOut.y,
                                    fiPt.x + fiIn.x,  fiPt.y + fiIn.y,
                                    fiPt.x, fiPt.y
                                );
                                p2d.closePath();
                                // Closed shape: hit on fill (inside) OR on its border stroke
                                paths.push({ type: 'fill',   path2d: p2d });
                            } else {
                                paths.push({ type: 'stroke', path2d: p2d });
                            }
                        }

                    } else if (type === 'PointText') {
                        if (data.visible === false) return;
                        if (!data.content || !data.matrix || data.matrix.length < 6) return;
                        // Text drawn at (matrix[4], matrix[5]) in parent space
                        const pos = this._tPt(ctm, data.matrix[4], data.matrix[5]);
                        // Paper.js's own default fontSize is 12 (omitted
                        // from exportJSON when left at that default).
                        const fs  = data.fontSize || 12;
                        // Approximate hit rect around the label
                        const w = fs * data.content.length * 0.65;
                        const h = fs * 1.2;
                        const p2d = new Path2D();
                        p2d.rect(pos.x - 4, pos.y - h, w + 8, h + 4);
                        paths.push({ type: 'fill', path2d: p2d });

                    } else if (type === 'Layer') {
                        const n = data.name || '';
                        if (n === 'background' || n.startsWith('related-')) return;
                        if (data.children) data.children.forEach(c => walk(c, ctm));
                    } else if (type === 'Project') {
                        if (data.layers) data.layers.forEach(l => walk(l, ctm));
                    }
                };

                if (Array.isArray(json) && json.length > 0 && Array.isArray(json[0])) {
                    json.forEach(item => walk(item, scaleM));
                } else {
                    walk(json, scaleM);
                }

                newHitPaths[id] = paths;
            });

            this._hitPaths = newHitPaths;
            this._hitPathsDirty = false;
        },

        // ── Hit testing ───────────────────────────────────────────────────────────

        _hitTest(canvasX, canvasY) {
            if (this._hitPathsDirty) this._buildHitPaths();
            const ctx = this._hitCtx;
            // lineWidth for stroke hit tolerance in canvas pixels
            ctx.lineWidth = 20;
            ctx.setTransform(1, 0, 0, 1, 0, 0);
            for (const [id, paths] of Object.entries(this._hitPaths)) {
                for (const { type, path2d } of paths) {
                    const hit = type === 'fill'
                        ? ctx.isPointInPath(path2d, canvasX, canvasY)
                        : ctx.isPointInStroke(path2d, canvasX, canvasY);
                    if (hit) return id;
                }
            }
            return null;
        },

        // Convert a DOM MouseEvent to canvas-pixel coordinates
        _eventToCanvas(e) {
            const canvas = this.$refs.canvas;
            if (!canvas) return null;
            const rect  = canvas.getBoundingClientRect();
            return {
                x: (e.clientX - rect.left) * (canvas.width  / rect.width),
                y: (e.clientY - rect.top)  * (canvas.height / rect.height),
            };
        },

        // ── Mouse handlers ────────────────────────────────────────────────────────

        onMouseMove(e) {
            if (!this.interactive) return;
            const pt = this._eventToCanvas(e);
            if (!pt) return;
            const hitId = this._hitTest(pt.x, pt.y);
            const canvas = this.$refs.canvas;
            if (hitId !== this._hoveredId) {
                this._hoveredId = hitId;
                this.render();
                this.$emit('item-hover', hitId ? { id: hitId } : null);
            }
            canvas.style.cursor = hitId ? 'pointer' : 'default';
        },

        onMouseLeave() {
            if (!this.interactive || !this._hoveredId) return;
            this._hoveredId = null;
            this.$refs.canvas && (this.$refs.canvas.style.cursor = 'default');
            this.render();
            this.$emit('item-hover', null);
        },

        onCanvasClick(e) {
            if (!this.interactive) return;
            const pt = this._eventToCanvas(e);
            if (!pt) return;
            const hitId = this._hitTest(pt.x, pt.y);
            if (hitId != null) this.$emit('item-click', { id: hitId });
        },

        // ── Canvas drawing ────────────────────────────────────────────────────────

        // `widthMul`/`fontMul` are multipliers applied to each path/text's OWN authored
        // strokeWidth/fontSize (as Paper.js recorded them, e.g. 3px / 20pt) — not
        // absolute final pixel sizes. The caller's ctx.scale(sx, sy) (see
        // drawItemScaled) then scales that local, pre-scale value up to the canvas's
        // pixel space in one step, exactly mirroring how Paper.js's own
        // item.transform(matrix) scales strokeWidth together with geometry. Using the
        // item's actual authored size (instead of a resolution-derived approximation)
        // is what makes this match the real composited/baked photo pixel-for-pixel.
        // NOTE: an item's own baked-in `isLegend` Paper.js group (see
        // DrawingTools.vue's rebuildLegend) is ALWAYS skipped here, regardless
        // of which item or how many are being drawn — this viewer draws its
        // own single combined legend instead (see drawCombinedLegendOverlay,
        // called once per render()), computed fresh from every item sharing
        // this photo. Drawing any ONE item's own possibly-stale legend used
        // to mean the legend shown depended entirely on which route/pitch
        // happened to be selected (some had one baked in, some didn't, and
        // whichever did could be stale) — a real bug, not a feature; see
        // legendRenderer.js's drawCombinedLegend for the full rationale.
        // `realColors` (default false) makes every color fall back to the
        // item's OWN authored Paper.js color instead of the passed-in
        // strokeStyle/dotFillStyle/textFillStyle whenever the caller doesn't
        // pass one — every regular route/pitch render call in render() below
        // always passes an explicit highlight color and leaves this false, so
        // behavior there is unchanged; the ONLY caller that passes true is
        // drawCombinedLegendOverlay's per-icon draw (with null styles), which
        // needs each symbol to keep its REAL color — a flat highlight-color
        // recolor would collapse a multi-colored legend card into one
        // indistinguishable color.
        // `legendOpts` ({ minStrokePx, minFontPx }) — see paperJsonRenderer.js's
        // drawItem for the full rationale: only ever passed when drawing one
        // legend icon SAMPLE shrunk to fit a small fixed icon box, where an
        // item's own authored proportions (correct at its natural on-photo
        // size) can otherwise shrink a fine inner detail below a pixel.
        drawItem(ctx, json, strokeStyle, dotFillStyle, textFillStyle, widthMul = 1, fontMul = 1, realColors = false, legendOpts) {
            const minStrokePx = legendOpts && legendOpts.minStrokePx;
            const minFontPx = legendOpts && legendOpts.minFontPx;
            const currentScale = () => {
                try {
                    const m = ctx.getTransform();
                    return Math.hypot(m.a, m.b) || 1;
                } catch (_) { return 1; }
            };
            const parseSeg = (s) => {
                if (!Array.isArray(s)) return null;
                if (Array.isArray(s[0])) {
                    const pt = s[0], hIn = s[1] || [0, 0], hOut = s[2] || [0, 0];
                    return { x: pt[0], y: pt[1], hIn, hOut };
                }
                return { x: s[0], y: s[1], hIn: [0, 0], hOut: [0, 0] };
            };

            // Paper.js exports colors as [r,g,b] or [r,g,b,a] floats in 0-1.
            const colorOf = (c, fallback) => {
                if (!c || !Array.isArray(c) || c.length < 3) return fallback;
                const [r, g, b, a] = c;
                const R = Math.round(r * 255), G = Math.round(g * 255), B = Math.round(b * 255);
                return a != null ? `rgba(${R},${G},${B},${a})` : `rgb(${R},${G},${B})`;
            };

            const walk = (item) => {
                if (!Array.isArray(item) || item.length < 2) return;
                const [type, data] = item;
                if (!data || typeof data !== 'object') return;

                if (type === 'Group' || type === 'CompoundPath') {
                    // An item's own baked-in isLegend group (see
                    // DrawingTools.vue's rebuildLegend) is ALWAYS skipped here
                    // — this viewer draws its own single combined legend
                    // instead (see drawCombinedLegendOverlay, called once per
                    // render()), computed fresh from every item sharing this
                    // photo. Drawing any ONE item's own possibly-stale legend
                    // used to mean the legend shown depended entirely on
                    // which route/pitch happened to be selected (some had one
                    // baked in, some didn't, and whichever did could be
                    // stale) — a real bug, not a feature; see
                    // legendRenderer.js's drawCombinedLegend for the full
                    // rationale.
                    // A hidden item ("hide selected" / any visibility
                    // toggle) must not be baked/drawn here either.
                    if (data.visible === false) return;
                    const gd = data.data || {};
                    if (gd.isLegend) return;

                    ctx.save();
                    const m = data.matrix;
                    if (m && Array.isArray(m) && m.length >= 6)
                        ctx.transform(m[0], m[1], m[2], m[3], m[4], m[5]);
                    if (data.children) data.children.forEach(walk);
                    ctx.restore();

                } else if (type === 'Path') {
                    if (data.visible === false) return;
                    const segs = data.segments;
                    if (!segs || !segs.length) return;
                    const pts = segs.map(parseSeg).filter(Boolean);
                    if (!pts.length) return;

                    ctx.save();
                    const pm = data.matrix;
                    if (pm && Array.isArray(pm) && pm.length >= 6)
                        ctx.transform(pm[0], pm[1], pm[2], pm[3], pm[4], pm[5]);

                    if ((data.name || '').startsWith('point ')) {
                        const minX = Math.min(...pts.map(p => p.x));
                        const maxX = Math.max(...pts.map(p => p.x));
                        const minY = Math.min(...pts.map(p => p.y));
                        const maxY = Math.max(...pts.map(p => p.y));
                        // add_point() (DrawingTools.vue) creates this circle with BOTH
                        // fillColor and strokeColor set, so Paper.js — both live in the
                        // editor and when baked into the saved composite via
                        // compositeImages() — renders a filled disc PLUS a stroke ring
                        // extending strokeWidth/2 further out, making the true visual
                        // radius bigger than the fill geometry's own bounding box. Filling
                        // only at the bare fill radius (as this used to) drew a visibly
                        // smaller dot here than what's baked into the composite image.
                        const fillRadius = Math.max(4, (maxX - minX) / 2);
                        const radius = (fillRadius + (data.strokeWidth || 0) / 2) * widthMul;
                        ctx.fillStyle = realColors ? colorOf(data.fillColor, dotFillStyle) : dotFillStyle;
                        ctx.beginPath();
                        ctx.arc((minX + maxX) / 2, (minY + maxY) / 2, radius, 0, Math.PI * 2);
                        ctx.fill();
                    } else {
                        // `realColors` mode (only drawCombinedLegendOverlay's
                        // per-icon draw, with `strokeStyle` null) must respect
                        // the item's REAL absence of a strokeColor — several
                        // DrawingTools.vue builders set `strokeWidth` on a
                        // fillColor-only path with no `strokeColor` purely to
                        // invisibly store a symbol's "size" for later resize
                        // (e.g. Parking's body, a POI/summit/tent marker's
                        // headCircle) — real Paper.js only draws a stroke
                        // when strokeColor is set. `colorOf(undefined, null)`
                        // here returns null/undefined, and this used to still
                        // `ctx.stroke()` unconditionally with whatever
                        // strokeStyle the canvas 2D context happened to have
                        // left over from the previous shape drawn — painting
                        // a stray, essentially random-colored ring around
                        // every such shape at legend-icon scale (mirrors the
                        // same bug fixed in paperJsonRenderer.js's drawItem;
                        // see there for the full writeup, including the
                        // Parking legend row's phantom border this caused).
                        // Outside `realColors` mode `strokeStyle` is always a
                        // real flat highlight color (route/pitch default/
                        // hover/selected), so `effectiveStroke` there is
                        // unaffected and always stroked, same as before.
                        const effectiveStroke = realColors ? colorOf(data.strokeColor, strokeStyle) : strokeStyle;
                        ctx.beginPath();
                        ctx.moveTo(pts[0].x, pts[0].y);
                        for (let i = 1; i < pts.length; i++) {
                            const prev = pts[i - 1], curr = pts[i];
                            const cp1x = prev.x + prev.hOut[0], cp1y = prev.y + prev.hOut[1];
                            const cp2x = curr.x + curr.hIn[0],  cp2y = curr.y + curr.hIn[1];
                            if (cp1x === prev.x && cp1y === prev.y && cp2x === curr.x && cp2y === curr.y) {
                                ctx.lineTo(curr.x, curr.y);
                            } else {
                                ctx.bezierCurveTo(cp1x, cp1y, cp2x, cp2y, curr.x, curr.y);
                            }
                        }
                        if (data.closed && pts.length > 1) {
                            const last = pts[pts.length - 1], first = pts[0];
                            ctx.bezierCurveTo(
                                last.x + last.hOut[0], last.y + last.hOut[1],
                                first.x + first.hIn[0], first.y + first.hIn[1],
                                first.x, first.y
                            );
                            ctx.closePath();
                        }
                        // Closed shapes with a recorded fillColor (a filled rectangle, or
                        // an arrow's solid triangular head) must be FILLED, not just
                        // outlined — Paper.js itself always fills before stroking when
                        // both are set. Skipping this left solid shapes like the
                        // arrowhead rendering as a hollow outline with a visible gap at
                        // the tip instead of a solid point touching the shaft.
                        if (data.closed && data.fillColor) {
                            ctx.fillStyle = realColors ? colorOf(data.fillColor, effectiveStroke) : strokeStyle;
                            ctx.fill();
                        }
                        if (effectiveStroke) {
                            ctx.strokeStyle = effectiveStroke;
                            // Paper.js's own default strokeWidth is 1 (not
                            // 3) and exportJSON() omits any property left at
                            // its class default — see paperJsonRenderer.js's
                            // drawItem for the full rationale (a hairline
                            // border, e.g. Parking's inset white square,
                            // round-trips with no strokeWidth field at all,
                            // and used to render 3x too thick here).
                            let lw = (data.strokeWidth || 1) * widthMul;
                            if (minStrokePx) lw = Math.max(lw, minStrokePx / currentScale());
                            ctx.lineWidth = lw;
                            ctx.lineCap = 'round';
                            ctx.lineJoin = 'round';
                            ctx.stroke();
                        }
                    }
                    ctx.restore();

                } else if (type === 'PointText') {
                    if (data.visible === false) return;
                    if (!data.content || !data.matrix || !Array.isArray(data.matrix) || data.matrix.length < 6) return;
                    // Paper.js's own default fontSize is 12 (omitted from
                    // exportJSON when left at that default).
                    let fs = (data.fontSize || 12) * fontMul;
                    if (minFontPx) fs = Math.max(fs, minFontPx / currentScale());
                    ctx.save();
                    ctx.fillStyle    = realColors ? colorOf(data.fillColor, textFillStyle) : textFillStyle;
                    ctx.font         = `bold ${fs}px Arial`;
                    ctx.textAlign    = data.justification === 'center' ? 'center' : 'left';
                    ctx.textBaseline = 'alphabetic';
                    ctx.fillText(data.content, data.matrix[4], data.matrix[5]);
                    ctx.restore();

                } else if (type === 'Layer') {
                    const n = data.name || '';
                    if (n === 'background' || n.startsWith('related-')) return;
                    if (data.children) data.children.forEach(walk);
                } else if (type === 'Project') {
                    if (data.layers) data.layers.forEach(walk);
                }
            };

            if (Array.isArray(json) && json.length > 0 && Array.isArray(json[0])) {
                json.forEach(walk);
            } else {
                walk(json);
            }
        },

        // Draws one item scaled from ITS OWN authored coordinate space into canvas-pixel
        // space. Each item may have been drawn in a differently-sized browser container,
        // so the scale must be resolved per item rather than once globally.
        //
        // ctx.scale(sx, sy) below scales every subsequent drawing coordinate — including
        // whatever ctx.lineWidth/ctx.font size drawItem() sets — by (sx, sy). Since
        // drawItem() now sets those from the item's own authored strokeWidth/fontSize
        // (a LOCAL, pre-scale value, same as what Paper.js itself stored), this one
        // transform is the only scaling that happens, so the result matches the actual
        // composited/baked photo exactly, regardless of what container width the route
        // was originally drawn in.
        drawItemScaled(ctx, meta, canvas, strokeStyle, dotFillStyle, textFillStyle, widthMul, fontMul) {
            const { sx, sy } = this._itemScale(meta, canvas);
            const { ox, oy } = this._itemOffset(meta);
            ctx.save();
            // Order matters: scale first, then translate — so the translate
            // amount is expressed in the ORIGINAL (unscaled) view-space units
            // the stroke coordinates and (ox,oy) are both recorded in.
            if (sx !== 1 || sy !== 1) ctx.scale(sx, sy);
            if (ox !== 0 || oy !== 0) ctx.translate(-ox, -oy);
            this.drawItem(ctx, meta.json, strokeStyle, dotFillStyle, textFillStyle, widthMul, fontMul);
            ctx.restore();
        },

        render() {
            if (!this.canvasReady) return;
            const canvas = this.$refs.canvas;
            if (!canvas) return;
            const ctx = canvas.getContext('2d');
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            const colorDef = this.color_default;
            const colorSel = this.color_selected;
            const colorHov = this.color_hover;
            const colorDot = this.color_dot;

            const hovId = this._hoveredId;
            const selId = this.selected_id != null ? String(this.selected_id) : null;

            // 1. Draw background items (all non-highlighted ones), at their own
            // authored size — preview_all boosts it slightly for overview visibility.
            if (this.preview_all || this.show_all) {
                const widthMul = this.preview_all ? 1.4 : 1;
                Object.entries(this.items).forEach(([id, meta]) => {
                    if (selId && id === selId) return;
                    if (hovId && id === String(hovId)) return;
                    this.drawItemScaled(ctx, meta, canvas, colorDef, colorDot, colorDef, widthMul, 1);
                });
            }

            // 2. Hover highlight (orange) — drawn below selected so selected stays on top
            if (hovId && this.items[hovId] && String(hovId) !== selId) {
                ctx.save();
                ctx.shadowColor = 'rgba(255,170,0,0.65)';
                ctx.shadowBlur  = 14;
                const widthMul = this.preview_all ? 1.4 : 2;
                this.drawItemScaled(ctx, this.items[hovId], canvas, colorHov, colorDot, colorHov, widthMul, 1);
                ctx.restore();
            }

            // 3. Selected highlight (green) — always on top
            if (selId && this.items[selId]) {
                ctx.save();
                ctx.shadowColor = 'rgba(0,220,60,0.55)';
                ctx.shadowBlur  = 10;
                this.drawItemScaled(ctx, this.items[selId], canvas, colorSel, colorDot, colorDef, 2.5, 1.2);
                ctx.restore();
            }

            // 4. General-purpose "extra info" layer (approach notes, hazards,
            // landmarks — not a route, never selectable/hoverable) — always on
            // top so it stays readable over any route lines it points at.
            if (this.extra_item && this.extra_item.json) {
                this.drawItemScaled(ctx, this.extra_item, canvas, this.color_extra, this.color_dot, this.color_extra, 1, 1);
            }

            ctx.setTransform(1, 0, 0, 1, 0, 0);

            // 5. ONE combined legend — the union of every distinct topo-symbol
            // type across EVERY item sharing this photo (not just the
            // selected one), drawn last so it stays on top. See
            // drawCombinedLegendOverlay for the full rationale.
            this.drawCombinedLegendOverlay(ctx, canvas);
        },

        // Draws the ONE combined legend for this photo — computed fresh from
        // every item's own untouched JSON (this.items + extra_item), same
        // union-of-symbols approach as SectorLocalImageCanvasComponent.vue's
        // drawLegends() and every admin editor's live preview
        // (canvasOverlaysMixin's computeEditorLegend). Always drawn (never
        // gated by selected_id/hover), so switching which route/pitch is
        // highlighted no longer changes what the legend shows — it shows
        // everything on the photo, always, exactly like a printed topo would.
        drawCombinedLegendOverlay(ctx, canvas) {
            const metas = Object.values(this.items).slice();
            if (this.extra_item && this.extra_item.json) metas.push(this.extra_item);
            const jsons = metas.map(m => m.json).filter(Boolean);
            if (!jsons.length) return;

            // Scale the legend by the SAME reference width the strokes
            // themselves are scaled by (see _itemScale) — the legend's icons
            // are literal copies of symbols drawn via the same drawItem, so
            // this keeps the legend the exact size it was authored at in the
            // admin editor, matching every other symbol already on the
            // photo. Deliberately NOT based on canvas.getBoundingClientRect()
            // (an earlier version was) — that reads the CURRENT on-screen
            // CSS width, which inside a modal can be measured before the
            // modal has finished its open transition/layout, silently
            // producing an oversized legend that never corrects itself since
            // nothing re-renders after the transition settles.
            const refMeta = metas.find(m => m && (m.bg_width || m.canvas_width));
            const refWidth = refMeta ? (refMeta.bg_width || refMeta.canvas_width) : canvas.width;

            try {
                drawCombinedLegend(ctx, canvas.width, canvas.height, jsons, refWidth, {
                    drawItem: (c, json, s, d, t, wm, fm, opts) => this.drawItem(c, json, s, d, t, wm, fm, true, opts),
                    translate: (key) => this.$t ? this.$t('admin.articles.canvas_editor.' + key) : key,
                });
            } catch (e) {
                // Deliberately logged (not silently swallowed) — a thrown
                // error here previously meant "no legend, no visible sign
                // why", which read as the same bug as the legend simply
                // having nothing to show.
                console.error('drawCombinedLegendOverlay failed:', e);
            }
        },
    },
}
</script>

<style scoped>
.canvas-json-show-wrapper {
    /* Shrink-wrap to the image's own rendered size (not the modal's full width) so
       a tall/portrait photo doesn't get stretched to full width and blow up in
       height — it now scales down by height instead and the wrapper (and the
       canvas overlay, sized to 100%/100% of it) shrinks right along with it. */
    position: relative;
    display: inline-block;
    max-width: 100%;
    line-height: 0;
    background: #111;
}
.canvas-bg-img {
    display: block;
    max-width: 100%;
    max-height: 65vh;
    width: auto;
    height: auto;
}
.canvas-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
}
.canvas-interactive {
    pointer-events: auto;
    cursor: default;
}

@media (max-width: 767px) {
    .canvas-bg-img {
        max-height: 45vh;
    }
}
</style>
