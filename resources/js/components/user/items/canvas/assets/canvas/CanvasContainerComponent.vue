<template>
    <div>
        <CanvasManager
            :canvas-id="'canvas-one'"
            :action="action"
            :json-prop="json_prop"
            :json-meta="json_meta"
            :related-jsons="related_jsons"
            :related-jsons-meta="related_jsons_meta"
            :related-first-label="related_first_label"
            :disable-auto-legend="disable_auto_legend"
            :image="image"
            :stroke-color="strokeColor"
            :fill-color="fillColor"
            :stroke-width="strokeWidth"
            :zoom-level="zoomLevel"
            :pan-offset="panOffset"
            ref="canvasManager"
            @canvas_data="handleCanvasData"
            @layers_updated="handleLayersUpdated"
            @layers_ready="handleLayersReady"
            @history-changed="onHistoryChanged"
            @zoom-changed="onZoomChanged"
            @crop-save="$emit('crop-save')"
        />
    </div>
</template>

<script>
import CanvasManager from "./manager/CanvasManager.vue";

export default {
    name: "CanvasContainerComponent",
    components: {
        CanvasManager
    },
    props: {
        action: {
            type: Number,
            default: 1
        },
        json_prop: {
            type: String,
            default: null
        },
        json_meta: {
            type: Object,
            default: () => null
        },
        related_jsons: {
            type: Array,
            default: () => []
        },
        related_jsons_meta: {
            type: Array,
            default: () => []
        },
        related_first_label: {
            type: String,
            default: null
        },
        disable_auto_legend: {
            type: Boolean,
            default: false
        },
        image: {
            type: String,
            default: null
        },
        strokeColor: {
            type: String,
            default: '#ff0000'
        },
        fillColor: {
            type: String,
            default: null
        },
        strokeWidth: {
            type: Number,
            default: 3
        },
        zoomLevel: {
            type: Number,
            default: 1
        },
        panOffset: {
            type: Object,
            default: () => ({ x: 0, y: 0 })
        }
    },
    data: () => ({
        // groupCounter is now accessed via getter/setter
        isCanvasManagerReady: false
    }),
    mounted() {
        // Ensure canvas manager is ready
        this.$nextTick(() => {
            this.isCanvasManagerReady = true;
        });
    },
    methods: {
        handleCanvasData(event) {
            this.$emit('canvas_data', event);
        },

        handleLayersUpdated() {
            this.$emit('layers_updated');
        },

        handleLayersReady() {
            this.$emit('layers_ready');
        },

        onHistoryChanged(histLen, redoLen) {
            this.$emit('history-changed', histLen, redoLen);
        },

        onZoomChanged(zoom) {
            this.$emit('zoom-changed', zoom);
        },

        // Delegate methods to canvas manager
        reset() {
            if (this.isCanvasManagerReady && this.$refs.canvasManager && typeof this.$refs.canvasManager.reset === 'function') {
                this.$refs.canvasManager.reset();
            }
        },

        undoLastAction() {
            if (this.isCanvasManagerReady && this.$refs.canvasManager && typeof this.$refs.canvasManager.undoLastAction === 'function') {
                this.$refs.canvasManager.undoLastAction();
            }
        },

        redoLastAction() {
            if (this.isCanvasManagerReady && this.$refs.canvasManager && typeof this.$refs.canvasManager.redoLastAction === 'function') {
                this.$refs.canvasManager.redoLastAction();
            }
        },

        saveCanvasData() {
            if (this.isCanvasManagerReady && this.$refs.canvasManager && typeof this.$refs.canvasManager.saveCanvasData === 'function') {
                this.$refs.canvasManager.saveCanvasData();
            }
        },

        // Access to canvas scope for layers management
        getCanvasScope() {
            return this.isCanvasManagerReady && this.$refs.canvasManager ? this.$refs.canvasManager.getCanvasScope() : null;
        },

        getBackgroundBounds() {
            return this.isCanvasManagerReady && this.$refs.canvasManager ? this.$refs.canvasManager.getBackgroundBounds() : null;
        },

        // Rescales/translates freshly-imported sibling-route layers (e.g. temporary
        // layers imported just to bake a composite screenshot) from the coordinate
        // space they were originally saved in (meta.bg_*) to fit the CURRENT
        // background raster — same transform importRelatedJsons() applies to the
        // on-screen reference layers. Without this, a route drawn in a
        // differently-sized container renders shifted/mis-scaled (sometimes off
        // canvas entirely) when captured, silently dropping it from the saved image.
        rescaleLayersToCurrentBackground(layers, meta) {
            if (this.isCanvasManagerReady && this.$refs.canvasManager
                && typeof this.$refs.canvasManager._rescaleToCurrentBackground === 'function') {
                this.$refs.canvasManager._rescaleToCurrentBackground(layers, meta);
            }
        },

        // Rescales an arrow Group's shaft width + arrowhead geometry together to a
        // specific width (see DrawingTools.vue's resizeArrow) — used by the layers
        // panel's size input, which otherwise only has direct access to raw Paper.js
        // item references, not the DrawingTools mixin methods that live on canvasManager.
        resizeArrow(arrowGroup, width) {
            if (this.isCanvasManagerReady && this.$refs.canvasManager
                && typeof this.$refs.canvasManager.resizeArrow === 'function') {
                this.$refs.canvasManager.resizeArrow(arrowGroup, width);
            }
        },

        // Same reasoning as resizeArrow above, for the rappel-marker Group
        // (see DrawingTools.vue's resizeRappel).
        resizeRappel(rappelGroup, width) {
            if (this.isCanvasManagerReady && this.$refs.canvasManager
                && typeof this.$refs.canvasManager.resizeRappel === 'function') {
                this.$refs.canvasManager.resizeRappel(rappelGroup, width);
            }
        },

        // Same reasoning as resizeArrow/resizeRappel above, for the bolt/pin/
        // pendulum/crux marker Groups (see DrawingTools.vue).
        resizeBolt(boltGroup, width) {
            if (this.isCanvasManagerReady && this.$refs.canvasManager
                && typeof this.$refs.canvasManager.resizeBolt === 'function') {
                this.$refs.canvasManager.resizeBolt(boltGroup, width);
            }
        },

        resizePin(pinGroup, width) {
            if (this.isCanvasManagerReady && this.$refs.canvasManager
                && typeof this.$refs.canvasManager.resizePin === 'function') {
                this.$refs.canvasManager.resizePin(pinGroup, width);
            }
        },

        resizePendulum(pendulumGroup, width) {
            if (this.isCanvasManagerReady && this.$refs.canvasManager
                && typeof this.$refs.canvasManager.resizePendulum === 'function') {
                this.$refs.canvasManager.resizePendulum(pendulumGroup, width);
            }
        },

        resizeCrux(cruxGroup, width) {
            if (this.isCanvasManagerReady && this.$refs.canvasManager
                && typeof this.$refs.canvasManager.resizeCrux === 'function') {
                this.$refs.canvasManager.resizeCrux(cruxGroup, width);
            }
        },

        // Same reasoning as resizeArrow/resizeRappel/resizeCrux above, for the
        // anchor-family marker Group (see DrawingTools.vue's resizeAnchor).
        resizeAnchor(anchorGroup, width) {
            if (this.isCanvasManagerReady && this.$refs.canvasManager
                && typeof this.$refs.canvasManager.resizeAnchor === 'function') {
                this.$refs.canvasManager.resizeAnchor(anchorGroup, width);
            }
        },

        // Same reasoning as resizeAnchor above, for the summit/tent landmark
        // markers (see DrawingTools.vue's resizeSummit/resizeTent).
        resizeSummit(summitGroup, width) {
            if (this.isCanvasManagerReady && this.$refs.canvasManager
                && typeof this.$refs.canvasManager.resizeSummit === 'function') {
                this.$refs.canvasManager.resizeSummit(summitGroup, width);
            }
        },

        resizeTent(tentGroup, width) {
            if (this.isCanvasManagerReady && this.$refs.canvasManager
                && typeof this.$refs.canvasManager.resizeTent === 'function') {
                this.$refs.canvasManager.resizeTent(tentGroup, width);
            }
        },

        resizeParking(parkingGroup, width) {
            if (this.isCanvasManagerReady && this.$refs.canvasManager
                && typeof this.$refs.canvasManager.resizeParking === 'function') {
                this.$refs.canvasManager.resizeParking(parkingGroup, width);
            }
        },

        // Same reasoning as resizeSummit/resizeTent/resizeParking above, for
        // the points-of-interest pins (see DrawingTools.vue's resizePoi).
        resizePoi(poiGroup, width) {
            if (this.isCanvasManagerReady && this.$refs.canvasManager
                && typeof this.$refs.canvasManager.resizePoi === 'function') {
                this.$refs.canvasManager.resizePoi(poiGroup, width);
            }
        },

        // Same reasoning as resizeSummit/resizeTent above, for the sector
        // name-label's font size (see DrawingTools.vue's resizeSectorLabel).
        resizeSectorLabel(labelGroup, width) {
            if (this.isCanvasManagerReady && this.$refs.canvasManager
                && typeof this.$refs.canvasManager.resizeSectorLabel === 'function') {
                this.$refs.canvasManager.resizeSectorLabel(labelGroup, width);
            }
        },

        // Rebuilds the auto-generated topo-symbol legend (see DrawingTools.vue's
        // rebuildLegend) — pass a position and/or scale to change/persist them
        // (from the toolbar's pickers), or omit either to just refresh content
        // against whatever is already stored.
        rebuildLegend(position, scale) {
            if (this.isCanvasManagerReady && this.$refs.canvasManager
                && typeof this.$refs.canvasManager.rebuildLegend === 'function') {
                this.$refs.canvasManager.rebuildLegend(position, scale);
            }
        },

        getLegendPosition() {
            return this.isCanvasManagerReady && this.$refs.canvasManager
                && typeof this.$refs.canvasManager.getLegendPosition === 'function'
                ? this.$refs.canvasManager.getLegendPosition()
                : 'top-right';
        },

        getLegendScale() {
            return this.isCanvasManagerReady && this.$refs.canvasManager
                && typeof this.$refs.canvasManager.getLegendScale === 'function'
                ? this.$refs.canvasManager.getLegendScale()
                : 1;
        },

        // Access to group counter for layer management
        getGroupCounter() {
            return this.isCanvasManagerReady && this.$refs.canvasManager ? this.$refs.canvasManager.getGroupCounter() : 0;
        },

        setGroupCounter(value) {
            if (this.isCanvasManagerReady && this.$refs.canvasManager) {
                this.$refs.canvasManager.setGroupCounter(value);
            }
        },

        // New methods for enhanced features
        updateView(zoomLevel, panOffset) {
            if (this.isCanvasManagerReady && this.$refs.canvasManager && typeof this.$refs.canvasManager.updateView === 'function') {
                this.$refs.canvasManager.updateView(zoomLevel, panOffset);
            }
        },

        exportCanvas(format) {
            if (this.isCanvasManagerReady && this.$refs.canvasManager && typeof this.$refs.canvasManager.exportCanvas === 'function') {
                this.$refs.canvasManager.exportCanvas(format);
            }
        },

        updateColors(strokeColor, fillColor, strokeWidth, textSize, dotSize) {
            if (this.isCanvasManagerReady && this.$refs.canvasManager && typeof this.$refs.canvasManager.updateColors === 'function') {
                this.$refs.canvasManager.updateColors(strokeColor, fillColor, strokeWidth, textSize, dotSize);
            }
        },

        setSmoothLines(value) {
            if (this.isCanvasManagerReady && this.$refs.canvasManager && typeof this.$refs.canvasManager.setSmoothLines === 'function') {
                this.$refs.canvasManager.setSmoothLines(value);
            }
        },

        // Enhanced zoom methods
        zoomIn() {
            if (this.isCanvasManagerReady && this.$refs.canvasManager && typeof this.$refs.canvasManager.zoomIn === 'function') {
                this.$refs.canvasManager.zoomIn();
            }
        },

        zoomOut() {
            if (this.isCanvasManagerReady && this.$refs.canvasManager && typeof this.$refs.canvasManager.zoomOut === 'function') {
                this.$refs.canvasManager.zoomOut();
            }
        },

        zoomReset() {
            if (this.isCanvasManagerReady && this.$refs.canvasManager && typeof this.$refs.canvasManager.zoomReset === 'function') {
                this.$refs.canvasManager.zoomReset();
            }
        },

        // Returns clean JSON (drawing layer only, no background/related layers).
        getCleanJson() {
            if (this.isCanvasManagerReady && this.$refs.canvasManager && typeof this.$refs.canvasManager._getDrawingJson === 'function') {
                return this.$refs.canvasManager._getDrawingJson();
            }
            return null;
        }
    }
}
</script>
