import { TOPO_SYMBOL_TYPES, LEGEND_CATEGORIES } from '../../components/user/items/canvas/assets/canvas/tools/topoSymbolTypes.js';

// Shared helpers for computing/drawing a "combined topo-symbol legend" (the
// union of every distinct symbol type found across several sectors' own
// saved Paper.js JSON documents) straight from raw JSON — no Paper.js
// runtime involved, no live/tinted reference-overlay items touched, so the
// result always uses each symbol's REAL authored color and never goes stale.
//
// Used by:
//  - SectorLocalImageCanvasComponent.vue (the public guidebook viewer) —
//    draws the combined legend onto the same raster canvas as the photo.
//  - sectorLocaleImageEditorComponent.vue (the admin editor) — draws a
//    live PREVIEW of that same combined legend onto a small overlay
//    <canvas>, so the admin can see the full picture while editing just one
//    sector, without that preview ever being saved into any one sector's
//    own document (which is what caused the color-contamination/staleness
//    bugs an earlier version of this feature had).

// Walks a raw Paper.js exported JSON tree, populating `samples` (mutated in
// place) with one representative raw node per DISTINCT topo-symbol type
// found (see topoSymbolTypes.js) — first match wins per key. Skips
// 'background'/'related-*' layers and any isLegend/isSectorLabel subtree
// (derived/inert content, never a real symbol instance).
function collectSymbolSamples(json, symbolTypes, samples) {
    const walk = (item) => {
        if (!Array.isArray(item) || item.length < 2) return;
        const [type, data] = item;
        if (!data || typeof data !== 'object') return;

        if (type === 'Layer' || type === 'Project') {
            const name = data.name || '';
            if (name === 'background' || name.startsWith('related-')) return;
            if (data.layers)   data.layers.forEach(walk);
            if (data.children) data.children.forEach(walk);
        } else if (type === 'Group' || type === 'CompoundPath') {
            // A hidden item (bulk "hide selected", or any single-item
            // visibility toggle) must not still count as "present" for the
            // legend — exportJSON() emits `visible: false` right alongside
            // `data`/`matrix` on the node itself (not inside `data`).
            if (data.visible === false) return;
            const gd = data.data || {};
            if (gd.isSectorLabel || gd.isLegend) return;
            symbolTypes.forEach(t => {
                if (!samples[t.key] && t.match(gd)) samples[t.key] = item;
            });
            if (data.children) data.children.forEach(walk);
        } else if (type === 'Path') {
            if (data.visible === false) return;
            const d = data.data || {};
            if (d.isSectorLabelLine) return;
            symbolTypes.forEach(t => {
                if (!samples[t.key] && t.match(d)) samples[t.key] = item;
            });
        }
    };

    if (Array.isArray(json) && json.length && Array.isArray(json[0])) json.forEach(walk);
    else walk(json);
}

// Finds the first 'main' (non-background/non-related) Layer's own
// legendPosition/legendScale (see DrawingTools.vue's rebuildLegend, which
// stores this on every save regardless of whether a legend is present) —
// or null if this document never had one set. Also carries `updatedAt`
// (DrawingTools.vue's `legendUpdatedAt`, only advanced on an explicit
// toolbar choice) so callers combining several documents' meta (see
// drawCombinedLegend below) can prefer whichever one was chosen most
// recently instead of an arbitrary fixed document.
function findLegendMeta(json) {
    let found = null;
    const walk = (item) => {
        if (found || !Array.isArray(item) || item.length < 2) return;
        const [type, data] = item;
        if (!data || typeof data !== 'object') return;
        if (type === 'Layer' || type === 'Project') {
            const name = data.name || '';
            if (name === 'background' || name.startsWith('related-')) return;
            const ld = data.data || {};
            if (ld.legendPosition || ld.legendScale) {
                found = { position: ld.legendPosition || 'top-right', scale: ld.legendScale || 1, updatedAt: ld.legendUpdatedAt || 0 };
                return;
            }
            if (data.layers)   data.layers.forEach(walk);
            if (data.children) data.children.forEach(walk);
        }
    };
    if (Array.isArray(json) && json.length && Array.isArray(json[0])) json.forEach(walk);
    else walk(json);
    return found;
}

// Bounding box of an arbitrary raw JSON node (Group/CompoundPath/Path), in
// that node's own local coordinate space — used to fit a symbol sample into
// a fixed-size legend icon regardless of which document it came from.
function rawNodeBounds(node) {
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
            // Only pad by half the stroke width when this path actually HAS
            // a stroke (see paperJsonRenderer.js's drawItem for the full
            // rationale) — several DrawingTools.vue builders set
            // `strokeWidth` on a fillColor-only path with no `strokeColor`
            // purely to invisibly store a symbol's "size" for later resize
            // (e.g. Parking's body, a POI/summit/tent marker's headCircle).
            // Padding for a stroke that's never actually drawn inflated the
            // computed bounds well past the shape's real visual size,
            // making the generic legend-icon fit (`iconBox / maxDim` in
            // drawLegendCard) shrink the shape more than it should — a real
            // bug, fixed September 2026 (e.g. Parking's blue square legend
            // icon rendering visibly smaller than its own icon box, with a
            // stray red ring at the box's true edge — see drawItem's fix for
            // where that red ring itself came from).
            const sw = data.strokeColor ? (data.strokeWidth || 0) / 2 : 0;
            segs.map(anchorPoint).forEach(p => {
                minX = Math.min(minX, p.x - sw); maxX = Math.max(maxX, p.x + sw);
                minY = Math.min(minY, p.y - sw); maxY = Math.max(maxY, p.y + sw);
            });
        }
    };
    visit(node);
    if (!isFinite(minX)) return null;
    return { left: minX, top: minY, right: maxX, bottom: maxY };
}

// Groups entries by their registry `category` (see topoSymbolTypes.js),
// preserving LEGEND_CATEGORIES' fixed section order — a flat, undifferentiated
// list mixing e.g. 9 near-identical anchor-quality variants with landmark/POI
// pins read as one confusing wall of rows; grouping under a small heading
// lets a reader jump straight to "Anchors" or "Points of interest" instead.
// Returns [{ category: null|{key,labelKey}, rows: [...] }, ...] — headings
// are entirely omitted (single group, category: null) when every present
// entry belongs to the SAME category, since a heading naming the only
// section present is just noise for the common single-family case (e.g. a
// sector with only Rappel/Bolt/Pin marked).
function groupEntriesByCategory(rows) {
    const present = new Set(rows.map(r => r.category));
    if (present.size <= 1) return [{ category: null, rows }];
    const groups = [];
    LEGEND_CATEGORIES.forEach(cat => {
        const catRows = rows.filter(r => r.category === cat.key);
        if (catRows.length) groups.push({ category: cat, rows: catRows });
    });
    // Any entry with no/unrecognized category (shouldn't normally happen)
    // still gets drawn, just without its own heading.
    const uncategorized = rows.filter(r => !LEGEND_CATEGORIES.some(c => c.key === r.category));
    if (uncategorized.length) groups.push({ category: null, rows: uncategorized });
    return groups;
}

// Draws a legend card at (x, y) in ctx's current coordinate space, sized by
// `scale` (1 = base size: ~13px row text, 22px icons). `drawItem` is passed
// in (from paperJsonRenderer.js) so this module has no hard dependency on
// it; `translate(key)` resolves an i18n label ("Legend" title / each row's/
// section heading's text) — pass a plain lookup function, e.g.
// `(k) => this.$t('admin...' + k)`. Returns the card's {width, height} in
// case the caller needs it (e.g. to size a wrapping <canvas> element).
function drawLegendCard(ctx, entries, { x = 0, y = 0, scale = 1, drawItem, translate }) {
    // iconBox was 22, the underlying issue every earlier bump (→30, →38)
    // kept chasing: EVERY symbol sample gets force-fit into this ONE box via
    // `iconScale = iconBox / maxDim` below, regardless of how large its own
    // outer shape was authored — a topo symbol (rappel/bolt/pin) is built
    // from an absolute, non-proportional stroke width, so it stays legible
    // shrunk almost arbitrarily far; a POI marker's fine inner pictogram/
    // letter is sized RELATIVE to its own much-larger pin (`_buildPoiParts`'s
    // R = dotSize*2, vs. a topo symbol's R = dotSize), so the SAME shrink
    // factor that's harmless for a bolt crushes a POI glyph's already-thin
    // detail toward zero — worst for a FILLED (no-stroke) glyph like the
    // water-drop/medical-cross icons, which minStrokePx below can't help at
    // all since there's no stroke to floor. Committed to a decisively
    // bigger box (fixed September 2026, round 3 of tuning this) rather than
    // nudging it again — every symbol should read as an actual small COPY
    // of the real drawn element, not a blurred impression of one.
    const pad = 14 * scale, rowH = 56 * scale, iconBox = 48 * scale, gap = 12 * scale;
    const fontSize = 13 * scale, titleSize = 15 * scale, titleGap = 10 * scale;
    const headingSize = 11 * scale, headingH = 22 * scale;

    const titleText = translate('legend_title') || 'Legend';
    ctx.font = `bold ${titleSize}px Arial`;
    const titleWidth = ctx.measureText(titleText).width;

    const rows = entries.map(e => ({ ...e, labelText: translate(e.labelKey) || e.key }));
    ctx.font = `bold ${fontSize}px Arial`;
    const labelWidths = rows.map(r => ctx.measureText(r.labelText).width);

    const groups = groupEntriesByCategory(rows).map(g => ({
        ...g,
        headingText: g.category ? (translate(g.category.labelKey) || g.category.key).toUpperCase() : null,
    }));

    const contentWidth = Math.max(titleWidth, ...rows.map((r, i) => iconBox + gap + labelWidths[i]));
    const boxW = contentWidth + pad * 2;
    const contentH = groups.reduce((h, g) => h + (g.headingText ? headingH : 0) + g.rows.length * rowH, 0);
    const boxH = pad + titleSize + titleGap + contentH + pad;

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
            // A thin rule above every heading EXCEPT the first (which already
            // sits right under the title, where a second rule would just
            // double up against the title's own visual separation).
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

            // Every symbol — topo/anchor/landmark/POI alike — draws as the
            // REAL, unmodified sample found on canvas, uniformly scaled to
            // fit `iconBox` off its own bounds (see rawNodeBounds): a true
            // small copy of the actual drawn sign, nothing invented or
            // simplified away (see the September 2026 round-7 note above
            // `iconBox` for why an earlier version decomposed POI pins into
            // a plain circle instead — reverted).
            const bounds = rawNodeBounds(r.sample);
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
                // drawItem for the full rationale: a fine authored
                // stroke/font can otherwise shrink below a pixel once
                // fit into this small fixed icon box. dashPx is the same
                // idea for a dashed sample (currently only the Trail row) —
                // without it a long real trail line's dash pattern shrinks
                // to sub-pixel and reads as a plain solid line.
                try { drawItem(ctx, r.sample, null, null, null, 1, 1, { minStrokePx: 1.8, minFontPx: 11, dashPx: [6, 4] }); } catch (_) {}
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
    return { width: boxW, height: boxH };
}

// Bakes the ONE combined legend directly into a full-resolution composite
// canvas, computed fresh from every json actually drawn onto it — the union
// of every distinct topo-symbol type across all of them, positioned via
// whichever json's own saved legendPosition/legendScale is found first. Two
// callers, both needing the exact same "one fresh combined card" logic:
//
//   1. Every admin editor page's renderCompositeAtFullResolution
//      (sectorLocaleImageEditorComponent.vue, spotRockImageEditorComponent.vue,
//      CanvasRouteEditorComponent.vue, sectorRouteDrawingsEditorComponent.vue,
//      CanvasPitchEditorComponent.vue) — bakes it into the JPEG/PNG saved to
//      the server, so a legend is visible even viewing the plain image file
//      directly (a bare <img>, or any context other than the interactive
//      viewer below) — previously NO legend was ever saved into the image at
//      all.
//   2. CanvasJsonDataShowComponent.vue's render() (the public interactive
//      route/MTP-pitch viewer) — draws it live on every redraw. FIXES the bug
//      where each route/pitch only ever showed (or didn't) whichever OWN
//      possibly-stale Paper.js `isLegend` group DrawingTools.vue's
//      rebuildLegend() had baked into THAT one item's JSON at its own last
//      save — inconsistent between siblings sharing the same photo, and
//      capable of showing outright wrong/stale content. Both callers now
//      compute the SAME union, so switching which route/pitch is selected no
//      longer changes what the legend shows.
//
// This function itself NEVER reads any item's own baked-in isLegend group —
// only real symbol instances (collectSymbolSamples explicitly skips isLegend
// subtrees) — and draws exactly once per call, computed fresh: safe to call
// on every redraw/every save without ever accumulating stale/duplicate cards,
// unlike the old per-item-baked-legend approach paperJsonRenderer.js's
// drawItem still (correctly) refuses to draw into a composite at all.
//
// `refWidth` is the width the legend should look "correctly sized" relative
// to — the admin's own editing-time view width (ownMeta.bg_width or
// .canvas_width, when baking a composite: the legend was sized relative to
// THAT width when the admin looked at it live, see EditorComponent's Size
// picker / computeEditorLegend) or the canvas element's own CSS-rendered
// width (when drawing the live public viewer, exactly like
// SectorLocalImageCanvasComponent.vue's drawLegends() `cssScale`). Scaling by
// (targetWidth / refWidth) reproduces the same relative on-photo size
// wherever this is called from, instead of rendering unreadably tiny (a fixed
// pixel size would look fine at ~800px-wide editing resolution but vanish at
// a multi-thousand-pixel native photo) or, on the live viewer, drifting
// smaller as a browser window narrows.
function drawCombinedLegend(ctx, w, h, jsons, refWidth, { drawItem, translate }) {
    const samples = {};
    // `legendPosition`/`legendScale` is a holdover from the old per-item
    // legend (each item baked its own, positioned independently) — "hidden"
    // there meant "I personally don't want to see a legend on THIS item's
    // own small live-preview." Now that one combined legend represents every
    // sibling sharing the photo, that same flag on any ONE sibling must not
    // be able to veto a position another sibling explicitly chose: prefer
    // the first sibling with a real (non-hidden) position, and fall back to
    // an explicit "hidden" only when NO sibling set a real one — i.e. every
    // sibling that set anything at all asked to hide it. This was a real bug
    // (fixed September 2026): a route with no symbols of its own, saved with
    // "hidden" back when a shared legend wasn't a concept, silently blanked
    // the whole combined legend for every OTHER route/pitch sharing that
    // sector image, in both the interactive viewer and the baked composite.
    // Among several real (non-hidden) positions, prefer whichever document's
    // `legendUpdatedAt` is latest (fixed September 2026, round 6 — reported
    // as "legend position and size is not changing": picking a fixed first
    // sibling meant the toolbar picker silently did nothing whenever some
    // OTHER item sharing this photo already had a real position saved) —
    // `>` (not `>=`) so legacy documents with no timestamp (0) still fall
    // back to "first one found", same as before this fix.
    let meta = null;
    let hiddenMeta = null;
    (jsons || []).forEach(raw => {
        if (!raw) return;
        let json = raw;
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
    });

    const entries = TOPO_SYMBOL_TYPES.filter(t => samples[t.key]).map(t => ({ ...t, sample: samples[t.key] }));
    const resolvedMeta = meta || hiddenMeta || { position: 'top-right', scale: 1 };
    if (!entries.length || resolvedMeta.position === 'hidden') return;

    const resScale = refWidth > 0 ? (w / refWidth) : 1;
    let scale = resScale * Math.max(0.3, resolvedMeta.scale || 1);

    // Two-pass on a scratch canvas — drawLegendCard always draws at its own
    // (x, y) origin and needs to know its own size before the real draw (same
    // pattern used by every live-preview caller of this function) — then
    // composited onto the real canvas at the resolved position so it can't
    // clip against the target canvas' own edges mid-measurement.
    const off = document.createElement('canvas');
    const octx = off.getContext('2d');
    off.width = 400; off.height = 400;
    let size = drawLegendCard(octx, entries, { scale, drawItem, translate });
    // Never let the card exceed 90% of the photo in either dimension — a
    // sector/route sharing a photo with many siblings can accumulate enough
    // distinct symbol types that, at scale 1, the card is taller/wider than
    // the photo itself, drawing off-canvas (negative px/py below) and baking
    // an oversized card into every composite image. Matches the same cap
    // already applied by computeEditorLegend() (canvasOverlaysMixin.js) and
    // _legendLayout() (SectorLocalImageCanvasComponent.vue) — this function
    // was missing it (fixed September 2026).
    const overflow = Math.max(size.width / (w * 0.9), size.height / (h * 0.9));
    if (overflow > 1) {
        scale = scale / overflow;
        size = drawLegendCard(octx, entries, { scale, drawItem, translate });
    }
    off.width = Math.ceil(size.width);
    off.height = Math.ceil(size.height);
    drawLegendCard(octx, entries, { scale, drawItem, translate });

    const margin = 12;
    let px, py;
    switch (resolvedMeta.position) {
        case 'top-left':  px = margin;                     py = margin; break;
        case 'top-mid':   px = (w - size.width) / 2;       py = margin; break;
        case 'top-right': px = w - size.width - margin;    py = margin; break;
        case 'left-mid':  px = margin;                     py = (h - size.height) / 2; break;
        case 'right-mid': px = w - size.width - margin;    py = (h - size.height) / 2; break;
        case 'bot-left':  px = margin;                     py = h - size.height - margin; break;
        case 'bot-mid':   px = (w - size.width) / 2;       py = h - size.height - margin; break;
        case 'bot-right': px = w - size.width - margin;    py = h - size.height - margin; break;
        default:          px = w - size.width - margin;    py = margin;
    }
    ctx.drawImage(off, px, py);
}

export { collectSymbolSamples, findLegendMeta, rawNodeBounds, drawLegendCard, drawCombinedLegend };
