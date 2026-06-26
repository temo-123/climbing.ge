<template>
    <div class="sector-local-canvas-wrap" ref="wrap">
        <canvas
            ref="canvas"
            class="sector-local-canvas"
            @mousemove="onMouseMove"
            @mouseleave="onMouseLeave"
            @click="onClick"
        />
        <!-- Tooltip -->
        <div
            v-if="hoveredSector"
            class="canvas-sector-tooltip"
            :style="{ left: tooltipX + 'px', top: tooltipY + 'px' }"
        >
            {{ hoveredSector.sectorName }}
        </div>
    </div>
</template>

<script>
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
            parsedLayouts:  [],  // [{sectorId, sectorName, sectorUrlTitle, shapes:[{type,x,y,w,h,rx,ry}]}]
            hoveredSector:  null,
            hoveredIdx:     -1,
            tooltipX:       0,
            tooltipY:       0,
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
    },
    methods: {
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
                ctx.fillText('Image not available', 400, 200);
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
                    // Paper.js canvas is sized to the admin container width (canvas_width);
                    // the article canvas is sized to the image's natural resolution.
                    const canvasW = l.canvas_width  || 0;
                    const scaleX  = (canvasW > 0 && this.imgW > 0) ? this.imgW / canvasW : 1;
                    const canvasH = l.canvas_height || 0;
                    const scaleY  = (canvasH > 0 && this.imgH > 0) ? this.imgH / canvasH : 1;

                    const rawShapes = json ? this.extractShapes(json) : [];
                    const shapes = rawShapes.map(s => {
                        if (s.type === 'rect') {
                            return { type: 'rect', x: s.x * scaleX, y: s.y * scaleY, w: s.w * scaleX, h: s.h * scaleY };
                        }
                        if (s.type === 'ellipse') {
                            return { type: 'ellipse', x: s.x * scaleX, y: s.y * scaleY, rx: s.rx * scaleX, ry: s.ry * scaleY };
                        }
                        return s;
                    });

                    return {
                        layoutId:       l.id,
                        sectorId:       l.sector_id,
                        sectorName:     l.sector ? l.sector.name     : '',
                        sectorUrlTitle: l.sector ? l.sector.url_title : '',
                        shapes,
                    };
                })
                .filter(l => l.shapes.length > 0);
            this.draw(-1);
        },

        // Walk Paper.js exported JSON and extract bounding shapes for hit detection.
        // DrawingTools creates all shapes as paper.Path (rect, circle, ellipse)
        // with data.isRectangle / data.isCircle / data.isEllipse flags.
        extractShapes(json) {
            const shapes = [];

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
                    if (data.layers)   data.layers.forEach(walk);
                    if (data.children) data.children.forEach(walk);
                } else if (type === 'Group' || type === 'CompoundPath') {
                    if (data.children) data.children.forEach(walk);
                } else if (type === 'Path') {
                    if (!data.closed) return;
                    const segs = data.segments || [];
                    if (segs.length < 3) return;

                    // Compute bounding box from anchor points
                    const pts = segs.map(anchorPoint);
                    const xs = pts.map(p => p.x), ys = pts.map(p => p.y);
                    const x = Math.min(...xs), y = Math.min(...ys);
                    const w = Math.max(...xs) - x, h = Math.max(...ys) - y;

                    const d = data.data || {};
                    if (d.isCircle || d.isEllipse) {
                        shapes.push({ type: 'ellipse', x: x + w / 2, y: y + h / 2, rx: w / 2, ry: h / 2 });
                    } else {
                        shapes.push({ type: 'rect', x, y, w, h });
                    }
                }
            };

            if (Array.isArray(json) && json.length && Array.isArray(json[0])) json.forEach(walk);
            else walk(json);
            return shapes;
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
                const rect = this.$refs.wrap.getBoundingClientRect();
                this.tooltipX = e.clientX - rect.left + 12;
                this.tooltipY = e.clientY - rect.top - 28;
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
            if (idx < 0) return;
            const layout = this.parsedLayouts[idx];
            this.$emit('sector-click', layout);
            // Scroll to sector on the page
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
    position: absolute;
    background: rgba(0,0,0,0.75);
    color: #fff;
    padding: 4px 10px;
    border-radius: 4px;
    font-size: 13px;
    pointer-events: none;
    white-space: nowrap;
    z-index: 10;
}
</style>
