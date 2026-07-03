<template>
    <div>
        <CanvasManager
            :canvas-id="'canvas-one'"
            :action="action"
            :json-prop="json_prop"
            :related-jsons="related_jsons"
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
        related_jsons: {
            type: Array,
            default: () => []
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

        updateColors(strokeColor, fillColor, strokeWidth) {
            if (this.isCanvasManagerReady && this.$refs.canvasManager && typeof this.$refs.canvasManager.updateColors === 'function') {
                this.$refs.canvasManager.updateColors(strokeColor, fillColor, strokeWidth);
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
