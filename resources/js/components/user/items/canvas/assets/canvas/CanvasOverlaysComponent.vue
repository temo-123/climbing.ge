<template>
    <!-- Item-name labels + leader lines, drawn OVER the live Paper.js canvas
         so the editor matches the annotated-overview look (see the public
         guidebook viewer's own version of the same thing). This is a plain
         SVG overlay tracked from the Paper.js view transform every animation
         frame by the host's canvasOverlaysMixin rather than real Paper.js
         items — the editor's own canvas coordinates pan/zoom independently
         of the page, and anything added as an actual Paper.js item would get
         baked into the saved drawing JSON and the composite photo,
         permanently duplicating a label that's only ever meant to be an
         editing aid.

         Positioned absolute (not fixed) — the host must render this inside
         its own position-relative root wrapper (ref="canvasOverlayAnchor")
         — since both this overlay and the Paper.js canvas then scroll
         together as part of the SAME normal document flow, the browser
         itself keeps them pixel-aligned during a page scroll with zero JS
         involvement (a `position:fixed` overlay resynced only once per
         animation frame visibly lagged/blinked a frame behind native scroll
         compositing). Also clipped (overflow:hidden) to exactly the canvas
         element's own bounding box — a saved label dragged near the top of
         an item's region floated OVER the toolbar above the canvas when
         zoomed in enough, since a real drawing item would have been clipped
         by the canvas's own edges but this plain overlay wasn't. -->
    <div class="canvas-overlays-labels-clip" :style="labelsClipStyle">
        <svg v-if="editorLabels.length" class="canvas-overlays-labels-svg">
            <g v-for="(label, i) in editorLabels" :key="i">
                <line :x1="label.anchorX" :y1="label.anchorY" :x2="label.boxCenterX" :y2="label.boxBottomY" stroke="#1a1a1a" stroke-width="1.5" />
                <rect :x="label.boxX" :y="label.boxY" :width="label.boxW" :height="label.boxH" rx="3" fill="#ffe100" stroke="#1a1a1a" stroke-width="1.5" />
                <text :x="label.boxCenterX" :y="label.boxCenterY" text-anchor="middle" dominant-baseline="middle" font-weight="bold" :font-size="label.fontSize" fill="#1a1a1a">{{ label.text }}</text>
            </g>
        </svg>
    </div>

    <!-- Live PREVIEW of the combined topo-symbol legend (see the host's
         canvasOverlaysMixin computeEditorLegend) — the union of every
         sibling item's own symbols, same content the public page will show.
         This is a plain <canvas> overlay, never a real Paper.js item, so it
         can never get saved into any one item's own document (that was the
         earlier "combined legend" design's actual bug: baking a combined
         snapshot into one item's JSON went stale/tinted the moment you
         switched items). Purely a "here's what it'll look like" aid while
         you work on just one item at a time.

         Same clip-wrapper/positioning treatment as the labels overlay above
         — see its comment. -->
    <div class="canvas-overlays-legend-clip" :style="legendClipStyle">
        <canvas ref="legendPreviewCanvas" class="canvas-overlays-legend-preview" :style="legendPreviewStyle"></canvas>
    </div>
</template>

<script>
export default {
    name: 'CanvasOverlaysComponent',
    props: {
        editorLabels: { type: Array, default: () => [] },
        labelsClipStyle: { type: Object, default: () => ({ display: 'none' }) },
        legendPreviewStyle: { type: Object, default: () => ({ display: 'none' }) },
        legendClipStyle: { type: Object, default: () => ({ display: 'none' }) },
    },
    methods: {
        // The host's canvasOverlaysMixin draws directly into this canvas's 2D
        // context (measuring/sizing it itself) — exposed as a method rather
        // than relying on cross-component `$refs` reaching-in, so the actual
        // DOM element stays this component's own implementation detail.
        getLegendCanvasEl() {
            return this.$refs.legendPreviewCanvas;
        },
    },
};
</script>

<style scoped>
/* Low z-index on purpose — this only ever needs to sit above the plain
   canvas/toolbar content right around it, NOT above real app-level overlays.
   The app's own PreloaderComponent (z-index 1035, shown for ~2s after every
   full page load/refresh) and RouteloaderComponent (z-index 9999) both sit
   below a higher value used here previously, which let this editing aid
   visibly float on top of the "Loading…" splash instead of being hidden
   behind it like the rest of the page underneath. */
.canvas-overlays-labels-clip {
    position: absolute;
    overflow: hidden;
    pointer-events: none;
    z-index: 20;
}
.canvas-overlays-labels-svg {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
}
.canvas-overlays-legend-clip {
    position: absolute;
    overflow: hidden;
    pointer-events: none;
    z-index: 21;
}
/* Sized to its actual content by computeEditorLegend() (canvas.width/height
   set in JS before drawing) — position (left/top) computed there too,
   RELATIVE to the clip wrapper's own top-left (not the viewport). */
.canvas-overlays-legend-preview {
    position: absolute;
    pointer-events: none;
}
</style>
