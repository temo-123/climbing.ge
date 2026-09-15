// Shared Paper.js-JSON-to-canvas renderer.
//
// This is the SAME algorithm used by CanvasJsonDataShowComponent.vue (the public
// guidebook viewer) to redraw a saved route/drawing straight from its exported
// Paper.js JSON onto a plain <canvas> — no Paper.js runtime involved. It is
// extracted here so the admin editor's save flow (captureAllDrawingStrokes /
// compositeImages) can bake composites the exact same way instead of capturing
// a small raster from the live editing canvas and stretching it up to the
// photo's full resolution, which:
//   1. blurs every line/dot/text edge (bitmap upscale of an already-rasterized,
//      anti-aliased small capture, then re-encoded as JPEG), and
//   2. can shift colors slightly (re-encoding an already-lossy intermediate).
// Drawing the vector JSON directly at the target resolution is crisp by
// construction, and reusing this exact module guarantees the baked composite
// and the live viewer always render identically — not just "close enough".

function itemScale(meta, canvasWidth, canvasHeight) {
    const bw = meta && meta.bg_width, bh = meta && meta.bg_height;
    if (bw && bh) return { sx: canvasWidth / bw, sy: canvasHeight / bh };
    const cw = meta && meta.canvas_width, ch = meta && meta.canvas_height;
    if (cw && ch) return { sx: canvasWidth / cw, sy: canvasHeight / ch };
    return { sx: 1, sy: 1 };
}

function itemOffset(meta) {
    if (meta && meta.bg_left != null && meta.bg_top != null) {
        return { ox: meta.bg_left, oy: meta.bg_top };
    }
    return { ox: 0, oy: 0 };
}

// Paper.js exports colors as [r,g,b] or [r,g,b,a] floats in 0-1. Converts to a
// CSS color string, or null if absent/malformed (caller falls back to a default).
function paperColorToCss(c) {
    if (!c || !Array.isArray(c) || c.length < 3) return null;
    const [r, g, b, a] = c;
    const R = Math.round(r * 255), G = Math.round(g * 255), B = Math.round(b * 255);
    return a != null ? `rgba(${R},${G},${B},${a})` : `rgb(${R},${G},${B})`;
}

function parseSeg(s) {
    if (!Array.isArray(s)) return null;
    if (Array.isArray(s[0])) {
        const pt = s[0], hIn = s[1] || [0, 0], hOut = s[2] || [0, 0];
        return { x: pt[0], y: pt[1], hIn, hOut };
    }
    return { x: s[0], y: s[1], hIn: [0, 0], hOut: [0, 0] };
}

// Draws one item's own JSON tree onto ctx, in that item's own (pre-scale)
// coordinate space — caller is expected to have already applied ctx.scale/
// ctx.translate for this item's sx/sy/ox/oy (see drawItemScaled below).
//
// `legendOpts` ({ minStrokePx, minFontPx }) is only ever passed by
// legendRenderer.js's drawLegendCard, drawing one symbol SAMPLE shrunk to
// fit a small fixed legend icon box. A symbol authored at its normal
// on-photo size (e.g. a POI marker's inner pictogram/letter, drawn relative
// to a comparatively LARGE outer pin shape) has thin strokes/small text that
// are perfectly legible at that native size, but shrinking the WHOLE symbol
// uniformly to fit a ~22px icon box can push those already-thin details
// below a pixel — the outer pin shape (large filled areas) stays visible,
// but the identifying inner glyph vanishes, and every POI kind ends up
// looking like the same plain teardrop (a real bug, fixed September 2026:
// "Points of interest" legend rows were indistinguishable from each other).
// Clamps the ON-SCREEN result to a legible minimum regardless of how much
// the current ctx transform has shrunk it — never used for normal drawing
// (composite bake / interactive viewer / editor canvas), where an item's own
// authored proportions are already correct at the scale it's actually shown.
// `skipDataFlags` (array of `.data.<flag>` names, e.g. `['isSectorLabel',
// 'isSectorLabelLine']`) lets a caller exclude specific UI-editing-aid
// subtrees beyond the always-skipped `isLegend` — used by
// SectorLocalImageCanvasComponent.vue when it draws a layout's full raw
// content (every topo symbol/POI marker/line, not just the sector-boundary
// rectangle it already extracted): the sector-name label is a REAL saved
// Paper.js item (unlike the legend, which is never baked anywhere), so
// composite-bake callers must keep drawing it (they pass no skipDataFlags,
// same as before) — only the live public-viewer draw, which already renders
// that label itself via its own drawSectorLabels() with its own styling/
// leader-line, needs to skip it here to avoid drawing it twice.
function drawItem(ctx, json, strokeStyle, dotFillStyle, textFillStyle, widthMul = 1, fontMul = 1, legendOpts, skipDataFlags) {
    const minStrokePx = legendOpts && legendOpts.minStrokePx;
    const minFontPx = legendOpts && legendOpts.minFontPx;
    const isSkipped = (d) => skipDataFlags && skipDataFlags.some(f => d[f]);
    const currentScale = () => {
        try {
            const m = ctx.getTransform();
            return Math.hypot(m.a, m.b) || 1;
        } catch (_) { return 1; }
    };
    const walk = (item) => {
        if (!Array.isArray(item) || item.length < 2) return;
        const [type, data] = item;
        if (!data || typeof data !== 'object') return;

        if (type === 'Group' || type === 'CompoundPath') {
            // The auto-generated topo-symbol legend (see DrawingTools.vue's
            // rebuildLegend) is a live-computed overlay now (see
            // SectorLocalImageCanvasComponent.vue's drawLegends) — it must
            // NEVER be baked into a composite raster. A legend baked into
            // pixels here is permanent: it can't be removed/repositioned by
            // any later live-overlay fix, survives forever in that one saved
            // image file, and (since a composite draws EVERY sibling
            // sector's own JSON onto the SAME shared photo) accumulates one
            // baked-in legend per sector that ever got saved, each frozen at
            // whatever position/content it had at save time.
            // A hidden item (single-item or bulk "hide selected" toggle)
            // must not still be baked into the composite / drawn by the
            // public viewer — exportJSON() emits `visible: false` right on
            // the node itself, not inside `data` (fixed September 2026).
            if (data.visible === false) return;
            const gd = data.data || {};
            if (gd.isLegend) return;
            if (isSkipped(gd)) return;

            ctx.save();
            const m = data.matrix;
            if (m && Array.isArray(m) && m.length >= 6) ctx.transform(m[0], m[1], m[2], m[3], m[4], m[5]);
            if (data.children) data.children.forEach(walk);
            ctx.restore();

        } else if (type === 'Path') {
            if (data.visible === false) return;
            if (isSkipped(data.data || {})) return;
            const segs = data.segments;
            if (!segs || !segs.length) return;
            const pts = segs.map(parseSeg).filter(Boolean);
            if (!pts.length) return;

            ctx.save();
            const pm = data.matrix;
            if (pm && Array.isArray(pm) && pm.length >= 6) ctx.transform(pm[0], pm[1], pm[2], pm[3], pm[4], pm[5]);

            if ((data.name || '').startsWith('point ')) {
                const minX = Math.min(...pts.map(p => p.x));
                const maxX = Math.max(...pts.map(p => p.x));
                const minY = Math.min(...pts.map(p => p.y));
                const maxY = Math.max(...pts.map(p => p.y));
                // add_point() (DrawingTools.vue) sets both fillColor and strokeColor on
                // this circle, so Paper.js's own rendering draws a filled disc PLUS a
                // stroke ring extending strokeWidth/2 further out — the true visual
                // radius is bigger than the fill geometry's bounding box alone.
                const fillRadius = Math.max(4, (maxX - minX) / 2);
                const radius = (fillRadius + (data.strokeWidth || 0) / 2) * widthMul;
                ctx.fillStyle = dotFillStyle || paperColorToCss(data.fillColor) || '#ffd700';
                ctx.beginPath();
                ctx.arc((minX + maxX) / 2, (minY + maxY) / 2, radius, 0, Math.PI * 2);
                ctx.fill();
            } else {
                // Several DrawingTools.vue builders set `strokeWidth` on a
                // path with NO `strokeColor` purely to invisibly store a
                // symbol's "size" for later resize (e.g. _buildParkingParts'
                // `parking-body`, _buildPoiParts'/_buildSummitParts'/
                // _buildTentParts' headCircle/body) — real Paper.js only
                // ever draws a stroke when `strokeColor` is actually set, so
                // that trick relies on it staying invisible. This renderer
                // used to always `ctx.stroke()` regardless, falling back to
                // a hardcoded red (`#cc2222`) whenever `strokeColor` was
                // absent — painting an unwanted red ring around every such
                // shape (most visibly Parking's square, and, at legend-icon
                // scale, a POI pin's head/tail glyphs, which are ALSO
                // fillColor-only paths with no strokeColor: the very same
                // unwanted red stroke was being drawn right on top of their
                // small black glyph fills, at low contrast against the pin's
                // own red, effectively hiding them — a real bug, fixed
                // September 2026, root cause of both the Parking legend row's
                // phantom red border and the "invisible" water/medical/bike
                // glyph rows). Only stroke when there's an ACTUAL color to
                // stroke with — an explicit override, or the item's own real
                // authored `strokeColor` — exactly matching Paper.js's own
                // "no strokeColor means no stroke" rule.
                const realStroke = paperColorToCss(data.strokeColor);
                const pathStroke = strokeStyle || realStroke;
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
                if (data.closed && data.fillColor) {
                    ctx.fillStyle = strokeStyle || paperColorToCss(data.fillColor) || pathStroke || '#cc2222';
                    ctx.fill();
                }
                if (pathStroke) {
                    ctx.strokeStyle = pathStroke;
                    // Paper.js's OWN default strokeWidth is 1 (not 3) — and
                    // exportJSON() omits any property left at its class
                    // default, so a path deliberately given a hairline
                    // border (e.g. _buildParkingParts' `parking-inner-border`,
                    // `strokeWidth: Math.max(1, R*0.1)`, which clamps to
                    // exactly 1 for a typical marker size) round-trips with
                    // NO `strokeWidth` field at all. Falling back to 3 here
                    // tripled that hairline's real thickness — at legend-icon
                    // scale (~3x) that swelled a subtle inset accent line
                    // into a band thick enough to swallow most of the
                    // surrounding fill, reading as a broken/wrong-colored
                    // outline (a real bug, fixed September 2026: root cause
                    // of the Parking legend row still looking wrong even
                    // after the phantom-red-stroke fix above).
                    let lw = (data.strokeWidth || 1) * widthMul;
                    if (minStrokePx) lw = Math.max(lw, minStrokePx / currentScale());
                    ctx.lineWidth = lw;
                    ctx.lineCap = 'round';
                    ctx.lineJoin = 'round';
                    ctx.stroke();
                } else if (!(data.closed && data.fillColor)) {
                    // No real stroke color AND nothing was filled either —
                    // there is genuinely nothing else to show this path with
                    // (shouldn't normally happen for real drawn content),
                    // so fall back to the old default rather than rendering
                    // it fully invisible.
                    ctx.strokeStyle = '#cc2222';
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
            // exportJSON when left at that default) — matches the
            // strokeWidth default fix above, same underlying quirk.
            let fs = (data.fontSize || 12) * fontMul;
            if (minFontPx) fs = Math.max(fs, minFontPx / currentScale());
            ctx.save();
            ctx.fillStyle    = textFillStyle || paperColorToCss(data.fillColor) || '#cc2222';
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
}

// Draws one item scaled from ITS OWN authored coordinate space into canvas-pixel
// space (see itemScale/itemOffset) — the item may have been drawn in a
// differently-sized browser container than the current target canvas.
function drawItemScaled(ctx, meta, canvasWidth, canvasHeight, strokeStyle, dotFillStyle, textFillStyle, widthMul = 1, fontMul = 1, skipDataFlags) {
    const { sx, sy } = itemScale(meta, canvasWidth, canvasHeight);
    const { ox, oy } = itemOffset(meta);
    ctx.save();
    if (sx !== 1 || sy !== 1) ctx.scale(sx, sy);
    if (ox !== 0 || oy !== 0) ctx.translate(-ox, -oy);
    let json = meta.json;
    if (typeof json === 'string') json = JSON.parse(json);
    if (typeof json === 'string') json = JSON.parse(json);
    drawItem(ctx, json, strokeStyle, dotFillStyle, textFillStyle, widthMul, fontMul, undefined, skipDataFlags);
    ctx.restore();
}

export { itemScale, itemOffset, drawItem, drawItemScaled, paperColorToCss };
