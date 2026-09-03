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
function drawItem(ctx, json, strokeStyle, dotFillStyle, textFillStyle, widthMul = 1, fontMul = 1) {
    const walk = (item) => {
        if (!Array.isArray(item) || item.length < 2) return;
        const [type, data] = item;
        if (!data || typeof data !== 'object') return;

        if (type === 'Group' || type === 'CompoundPath') {
            ctx.save();
            const m = data.matrix;
            if (m && Array.isArray(m) && m.length >= 6) ctx.transform(m[0], m[1], m[2], m[3], m[4], m[5]);
            if (data.children) data.children.forEach(walk);
            ctx.restore();

        } else if (type === 'Path') {
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
                const pathStroke = strokeStyle || paperColorToCss(data.strokeColor) || '#cc2222';
                ctx.strokeStyle = pathStroke;
                ctx.lineWidth   = (data.strokeWidth || 3) * widthMul;
                ctx.lineCap     = 'round';
                ctx.lineJoin    = 'round';
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
                    ctx.fillStyle = strokeStyle || paperColorToCss(data.fillColor) || pathStroke;
                    ctx.fill();
                }
                ctx.stroke();
            }
            ctx.restore();

        } else if (type === 'PointText') {
            if (!data.content || !data.matrix || !Array.isArray(data.matrix) || data.matrix.length < 6) return;
            const fs = (data.fontSize || 20) * fontMul;
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
function drawItemScaled(ctx, meta, canvasWidth, canvasHeight, strokeStyle, dotFillStyle, textFillStyle, widthMul = 1, fontMul = 1) {
    const { sx, sy } = itemScale(meta, canvasWidth, canvasHeight);
    const { ox, oy } = itemOffset(meta);
    ctx.save();
    if (sx !== 1 || sy !== 1) ctx.scale(sx, sy);
    if (ox !== 0 || oy !== 0) ctx.translate(-ox, -oy);
    let json = meta.json;
    if (typeof json === 'string') json = JSON.parse(json);
    if (typeof json === 'string') json = JSON.parse(json);
    drawItem(ctx, json, strokeStyle, dotFillStyle, textFillStyle, widthMul, fontMul);
    ctx.restore();
}

export { itemScale, itemOffset, drawItem, drawItemScaled, paperColorToCss };
