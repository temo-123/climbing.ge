<template>
    <div class="route-lines-wrapper">
        <img
            v-if="image_src"
            ref="bgImg"
            :src="image_src"
            class="route-bg-img"
            @load="onImageLoad"
        />
        <canvas ref="canvas" class="route-canvas" />
    </div>
</template>

<script>
import axios from 'axios';

export default {
    props: {
        sector_image_id:   { default: null },
        image_src:         { default: null },
        // Composite image (routes baked in) whose pixel dimensions match the Paper.js
        // coordinate space used by the editor. Passed only when has_original=true so we
        // can scale route coordinates from composite space to origin-image space.
        composite_src:     { default: null },
        selected_route_id: { default: null },
        // true  → background is the original clean photo → draw all routes in dim color + selected green
        // false → background is composite (routes already baked in) → only draw selected in green
        show_all:          { type: Boolean, default: false },
        // preview_all → draw every route in solid red, ignore selection (for admin previews)
        preview_all:       { type: Boolean, default: false },
    },
    data: () => ({
        routeJsons:    {},   // { route_id: parsedJson }
        canvasReady:   false,
        compositeWidth:  null,
        compositeHeight: null,
    }),
    watch: {
        sector_image_id(v) { if (v) { this.routeJsons = {}; this.canvasReady = false; this.fetchRoutes(); } },
        image_src()        { this.canvasReady = false; },
        selected_route_id(){ this.render(); },
        routeJsons()       { this.render(); },
        show_all()         { this.render(); },
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
                // Handle already-cached images
                if (img.complete && img.naturalWidth > 0) {
                    this.compositeWidth  = img.naturalWidth;
                    this.compositeHeight = img.naturalHeight;
                }
            },
        },
    },
    mounted() {
        if (this.sector_image_id) this.fetchRoutes();
        // Cached images may not fire @load — trigger manually if already complete.
        this.$nextTick(() => {
            const img = this.$refs.bgImg;
            if (img && img.complete && img.naturalWidth > 0) this.onImageLoad();
        });
        // Re-fetch when another component saves or deletes a drawing for the same sector image.
        this._drawingUnsub = this.$bus.$on('route-drawing-updated', ({ sector_image_id }) => {
            if (String(sector_image_id) === String(this.sector_image_id)) {
                this.fetchRoutes();
            }
        });
    },
    beforeUnmount() {
        if (this._drawingUnsub) this._drawingUnsub();
    },
    methods: {
        onImageLoad() {
            const img    = this.$refs.bgImg;
            const canvas = this.$refs.canvas;
            if (!img || !canvas) return;
            const nw = img.naturalWidth  || 800;
            const nh = img.naturalHeight || 600;
            canvas.width  = nw;
            canvas.height = nh;
            this.canvasReady = true;
            this.render();
        },

        async fetchRoutes() {
            if (!this.sector_image_id) return;
            try {
                const res = await axios.get('get_route/get_route_jsons_for_sector_image', {
                    params: { sector_image_id: this.sector_image_id },
                });
                const jsons = {};
                (res.data || []).forEach(item => {
                    if (!item.route_id || !item.json) return;
                    try {
                        // Paper.js exportJSON() returns a string, which the editor wraps in
                        // JSON.stringify again → double-encoded. Parse once then check again.
                        let parsed = typeof item.json === 'string' ? JSON.parse(item.json) : item.json;
                        if (typeof parsed === 'string') parsed = JSON.parse(parsed);
                        jsons[item.route_id] = parsed;
                    } catch (_) {}
                });
                this.routeJsons = jsons;
            } catch (_) {}
        },

        // Shared tree walker: calls callbacks for Path, PointText while skipping
        // background / related-* layers and Raster items.
        _walkJson(json, onPath, onText) {
            const walk = (item) => {
                if (!Array.isArray(item) || item.length < 2) return;
                const [type, data] = item;
                if (!data || typeof data !== 'object') return;
                if (type === 'Path') {
                    if (Array.isArray(data.segments) && data.segments.length) onPath(data);
                } else if (type === 'PointText') {
                    if (data.content) onText(data);
                } else if (type === 'CompoundPath' || type === 'Group') {
                    if (data.children) data.children.forEach(walk);
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

        // Returns non-dot path data objects (lines, rectangles, circles, etc.).
        extractPaths(json) {
            const result = [];
            this._walkJson(json,
                (pd) => { if (!(pd.name || '').startsWith('point ')) result.push(pd); },
                () => {}
            );
            return result;
        },

        // Returns dot path data objects (name starts with "point ").
        extractDots(json) {
            const result = [];
            this._walkJson(json,
                (pd) => { if ((pd.name || '').startsWith('point ')) result.push(pd); },
                () => {}
            );
            return result;
        },

        // Returns PointText data objects (route numbers).
        extractTexts(json) {
            const result = [];
            this._walkJson(json, () => {}, (td) => result.push(td));
            return result;
        },

        // Draw path data objects (from extractPaths) onto ctx in the given color/width.
        drawPaths(ctx, json, strokeStyle, lineWidth) {
            const paths = this.extractPaths(json);
            if (!paths.length) return;

            ctx.save();
            ctx.strokeStyle = strokeStyle;
            ctx.lineWidth   = lineWidth;
            ctx.lineCap     = 'round';
            ctx.lineJoin    = 'round';

            paths.forEach(pd => {
                const segs = pd.segments;
                if (!segs || !segs.length) return;

                // Parse one Paper.js segment entry.
                // Format A: [x, y]              → point only, no handles
                // Format B: [[x,y],[hIn],[hOut]] → point + relative handles
                const parseSeg = (s) => {
                    if (!Array.isArray(s)) return null;
                    if (Array.isArray(s[0])) {
                        // Format B
                        const pt   = s[0];
                        const hIn  = s[1] || [0, 0];
                        const hOut = s[2] || [0, 0];
                        return { x: pt[0], y: pt[1], hIn, hOut };
                    }
                    // Format A
                    return { x: s[0], y: s[1], hIn: [0, 0], hOut: [0, 0] };
                };

                const pts = segs.map(parseSeg).filter(Boolean);
                if (!pts.length) return;

                ctx.beginPath();
                ctx.moveTo(pts[0].x, pts[0].y);

                for (let i = 1; i < pts.length; i++) {
                    const prev = pts[i - 1];
                    const curr = pts[i];
                    const cp1x = prev.x + prev.hOut[0];
                    const cp1y = prev.y + prev.hOut[1];
                    const cp2x = curr.x + curr.hIn[0];
                    const cp2y = curr.y + curr.hIn[1];

                    const isStraight = (cp1x === prev.x && cp1y === prev.y)
                                    && (cp2x === curr.x && cp2y === curr.y);
                    if (isStraight) {
                        ctx.lineTo(curr.x, curr.y);
                    } else {
                        ctx.bezierCurveTo(cp1x, cp1y, cp2x, cp2y, curr.x, curr.y);
                    }
                }

                if (pd.closed && pts.length > 1) {
                    const last  = pts[pts.length - 1];
                    const first = pts[0];
                    ctx.bezierCurveTo(
                        last.x  + last.hOut[0],  last.y  + last.hOut[1],
                        first.x + first.hIn[0],  first.y + first.hIn[1],
                        first.x, first.y,
                    );
                    ctx.closePath();
                }

                ctx.stroke();
            });

            ctx.restore();
        },

        // Draw endpoint dots (Paper.js Path.Circle) as filled circles in the given color.
        drawDots(ctx, json, fillStyle) {
            const dots = this.extractDots(json);
            dots.forEach(pd => {
                const segs = pd.segments;
                if (!segs || !segs.length) return;
                // Anchor points of a Paper.js bezier circle sit at top/right/bottom/left.
                const pts = segs.map(s => {
                    if (!Array.isArray(s)) return null;
                    return Array.isArray(s[0]) ? { x: s[0][0], y: s[0][1] } : { x: s[0], y: s[1] };
                }).filter(Boolean);
                if (!pts.length) return;
                const minX = Math.min(...pts.map(p => p.x));
                const maxX = Math.max(...pts.map(p => p.x));
                const minY = Math.min(...pts.map(p => p.y));
                const maxY = Math.max(...pts.map(p => p.y));
                const cx = (minX + maxX) / 2;
                const cy = (minY + maxY) / 2;
                const r  = Math.max(4, (maxX - minX) / 2);
                ctx.save();
                ctx.fillStyle = fillStyle;
                ctx.beginPath();
                ctx.arc(cx, cy, r, 0, Math.PI * 2);
                ctx.fill();
                ctx.restore();
            });
        },

        // Draw route-number labels (Paper.js PointText) in the given color.
        // Paper.js stores position in the affine matrix as [1,0,0,1,x,y].
        drawTexts(ctx, json, fillStyle, fontSize) {
            const texts = this.extractTexts(json);
            texts.forEach(td => {
                if (!td.matrix || !Array.isArray(td.matrix)) return;
                const x  = td.matrix[4];
                const y  = td.matrix[5];
                const fs = fontSize || td.fontSize || 20;
                ctx.save();
                ctx.fillStyle    = fillStyle;
                ctx.font         = `bold ${fs}px Arial`;
                ctx.textAlign    = td.justification === 'center' ? 'center' : 'left';
                ctx.textBaseline = 'alphabetic';
                ctx.fillText(td.content, x, y);
                ctx.restore();
            });
        },

        // Single-pass renderer that applies Group/CompoundPath matrices so items moved
        // with action 8 render at their correct global positions.
        drawRoute(ctx, json, strokeStyle, dotFillStyle, textFillStyle, lineWidth, fontSize) {
            const parseSeg = (s) => {
                if (!Array.isArray(s)) return null;
                if (Array.isArray(s[0])) {
                    const pt = s[0], hIn = s[1] || [0, 0], hOut = s[2] || [0, 0];
                    return { x: pt[0], y: pt[1], hIn, hOut };
                }
                return { x: s[0], y: s[1], hIn: [0, 0], hOut: [0, 0] };
            };

            const walk = (item) => {
                if (!Array.isArray(item) || item.length < 2) return;
                const [type, data] = item;
                if (!data || typeof data !== 'object') return;

                if (type === 'Group' || type === 'CompoundPath') {
                    ctx.save();
                    const m = data.matrix;
                    if (m && Array.isArray(m) && m.length >= 6)
                        ctx.transform(m[0], m[1], m[2], m[3], m[4], m[5]);
                    if (data.children) data.children.forEach(walk);
                    ctx.restore();

                } else if (type === 'Path') {
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
                        ctx.fillStyle = dotFillStyle;
                        ctx.beginPath();
                        ctx.arc((minX + maxX) / 2, (minY + maxY) / 2,
                                Math.max(4, (maxX - minX) / 2), 0, Math.PI * 2);
                        ctx.fill();
                    } else {
                        ctx.strokeStyle = strokeStyle;
                        ctx.lineWidth   = lineWidth;
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
                        ctx.stroke();
                    }
                    ctx.restore();

                } else if (type === 'PointText') {
                    if (!data.content || !data.matrix || !Array.isArray(data.matrix) || data.matrix.length < 6) return;
                    const x  = data.matrix[4], y = data.matrix[5];
                    const fs = fontSize || data.fontSize || 20;
                    ctx.save();
                    ctx.fillStyle    = textFillStyle;
                    ctx.font         = `bold ${fs}px Arial`;
                    ctx.textAlign    = data.justification === 'center' ? 'center' : 'left';
                    ctx.textBaseline = 'alphabetic';
                    ctx.fillText(data.content, x, y);
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

        render() {
            if (!this.canvasReady) return;
            const canvas = this.$refs.canvas;
            if (!canvas) return;
            const ctx = canvas.getContext('2d');
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            // Route coordinates are in Paper.js space = composite image pixel dimensions.
            // When the background is the high-res origin photo (has_original=true), scale
            // the context so coordinates map correctly onto the larger canvas buffer.
            const sx = this.compositeWidth  ? (canvas.width  / this.compositeWidth)  : 1;
            const sy = this.compositeHeight ? (canvas.height / this.compositeHeight) : 1;
            if (sx !== 1 || sy !== 1) ctx.scale(sx, sy);

            // Line widths and font sizes are in drawing-coordinate (composite) space;
            // ctx.scale above will proportionally expand them into canvas buffer space.
            const refW     = this.compositeWidth  || canvas.width;
            const base     = Math.max(3, refW / 280);
            const textSize = Math.max(12, base * 6);

            // Admin preview: draw every route in solid red, no selection logic
            if (this.preview_all) {
                Object.keys(this.routeJsons).forEach(id => {
                    const j = this.routeJsons[id];
                    this.drawRoute(ctx, j, '#cc2222', '#ffd700', '#cc2222', base * 1.4, textSize);
                });
                ctx.setTransform(1, 0, 0, 1, 0, 0);
                return;
            }

            if (this.show_all) {
                // Draw all other routes in solid red (not semi-transparent pink)
                Object.keys(this.routeJsons).forEach(id => {
                    if (String(id) === String(this.selected_route_id)) return;
                    const j = this.routeJsons[id];
                    this.drawRoute(ctx, j, '#cc2222', '#ffd700', '#cc2222', base, textSize);
                });
            }

            // Highlight the selected route in bright green
            if (this.selected_route_id && this.routeJsons[this.selected_route_id]) {
                const j = this.routeJsons[this.selected_route_id];
                ctx.save();
                ctx.shadowColor = 'rgba(0,220,60,0.55)';
                ctx.shadowBlur  = 10;
                this.drawRoute(ctx, j, '#00e64d', '#ffd700', '#cc2222', base * 2.5, textSize * 1.2);
                ctx.restore();
            }

            ctx.setTransform(1, 0, 0, 1, 0, 0);
        },
    },
}
</script>

<style scoped>
.route-lines-wrapper {
    position: relative;
    line-height: 0;
    background: #111;
}
.route-bg-img {
    display: block;
    width: 100%;
    height: auto;
}
.route-canvas {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
}
</style>
