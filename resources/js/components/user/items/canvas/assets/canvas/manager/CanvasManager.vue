<template>
    <canvas :id="canvasId" class="canvas-style" v-on:mousedown="handleMouseDown" />
</template>

<script>
import paper from 'paper';
import DrawingTools from '../tools/DrawingTools.vue';
import CanvasHandlers from '../handlers/CanvasHandlers.vue';

export default {
    name: "CanvasManager",
    mixins: [DrawingTools, CanvasHandlers],
    props: {
        canvasId: {
            type: String,
            default: 'canvas-one'
        },
        action: {
            type: Number,
            default: 1
        },
        jsonProp: {
            type: String,
            default: null
        },
        relatedJsons: {
            type: Array,
            default: () => []
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
        scope: null,
        canvasData: null,
        oldJson: null,
        selectedItem: null,
        group: null,
        groupCounter: 0,
        layerCounters: {
            line: 0,
            point: 0,
            rectangle: 0,
            circle: 0,
            ellipse: 0,
            polygon: 0,
            text: 0,
            group: 0
        },
        history: [],
        redoStack: [],
        jsonLoaded: false,
        isPanning: false,
        panStartPoint: null,
        selectionRect: null,
        currentStrokeColor: '#ff0000',
        currentFillColor: null,
        currentStrokeWidth: 3
    }),
    watch: {
        jsonProp: {
            handler: function(newVal) {
                // Only import once on first non-null value to avoid re-importing on every canvas_data emit
                if (!this.jsonLoaded && newVal) {
                    this.importJsonData(newVal);
                    this.jsonLoaded = true;
                }
            },
            immediate: true
        },
        relatedJsons: {
            handler: function(newVal) {
                if (this.scope) this.importRelatedJsons();
            },
            immediate: false
        }
    },
        mounted() {
            this.scope = new paper.PaperScope();
            this.scope.setup(this.canvasId);
            this.scope.activate();

            // Initialize with empty project
            this.scope.project.clear();

            // Set initial zoom and pan
            this.updateView(this.zoomLevel, this.panOffset);

            // Import related JSONs first (background)
            this.importRelatedJsons();

            // Import JSON if it was passed during initialization (foreground, editable)
            if (this.jsonProp) {
                this.importJsonData(this.jsonProp);
            }
            // Mark JSON as loaded so the jsonProp watcher doesn't re-import on canvas_data updates
            this.jsonLoaded = true;

            // Save initial empty state for undo functionality
            this.saveCanvasData();

            // Add mouse wheel event for zoom
            this.addMouseWheelListener();

            // Emit event to notify parent that layers are ready
            this.$emit('layers_ready');
        },

        beforeUnmount() {
            // Remove mouse wheel listener
            if (this.scope && this.scope.view && this.scope.view.element) {
                this.scope.view.element.removeEventListener('wheel', this.handleMouseWheel);
            }
        },
    methods: {
        handleMouseDown(event) {
            this.mouseDown(event);
        },

        reset() {
            this.scope.project.layers.forEach(layer => {
                if (!layer.name || !layer.name.startsWith('related-')) {
                    layer.removeChildren();
                }
            });
            this.selectedItem = null;
            this.saveCanvasData();
        },

        saveCanvasData() {
            const canvasData = JSON.stringify(this.scope.project.exportJSON());
            const currentState = JSON.parse(canvasData);
            const lastState = this.history.length > 0 ? this.history[this.history.length - 1] : null;
            if (!lastState || JSON.stringify(currentState) !== JSON.stringify(lastState)) {
                this.history.push(currentState);
                if (this.history.length > 20) this.history.shift();
                this.redoStack = [];
            }
            this.$emit('canvas_data', canvasData);
            this.$emit('history-changed', this.history.length, this.redoStack.length);
        },

        undoLastAction() {
            if (this.history.length === 0) return;
            const currentState = JSON.stringify(this.scope.project.exportJSON());
            this.redoStack.push(JSON.parse(currentState));
            if (this.redoStack.length > 20) this.redoStack = this.redoStack.slice(-20);

            const lastState = this.history.pop();
            this.scope.project.clear();
            if (lastState) this.scope.project.importJSON(lastState);
            this.scope.view.update();
            this.$emit('layers_updated');
            this.$emit('canvas_data', JSON.stringify(this.scope.project.exportJSON()));
            this.$emit('history-changed', this.history.length, this.redoStack.length);
        },

        redoLastAction() {
            if (this.redoStack.length === 0) return;
            const currentState = JSON.stringify(this.scope.project.exportJSON());
            this.history.push(JSON.parse(currentState));
            if (this.history.length > 20) this.history = this.history.slice(-20);

            const nextState = this.redoStack.pop();
            this.scope.project.clear();
            if (nextState) this.scope.project.importJSON(nextState);
            this.scope.view.update();
            this.$emit('layers_updated');
            this.$emit('canvas_data', JSON.stringify(this.scope.project.exportJSON()));
            this.$emit('history-changed', this.history.length, this.redoStack.length);
        },

        getCanvasScope() {
            return this.scope;
        },

        // Expose groupCounter for parent access
        getGroupCounter() {
            return this.groupCounter;
        },

        setGroupCounter(value) {
            this.groupCounter = value;
        },

        // New methods for enhanced features
        updateView(zoomLevel, panOffset) {
            if (this.scope && this.scope.view) {
                this.scope.view.zoom = zoomLevel;
                this.scope.view.center = new paper.Point(
                    this.scope.view.viewSize.width / 2 + panOffset.x,
                    this.scope.view.viewSize.height / 2 + panOffset.y
                );
            }
        },

        addMouseWheelListener() {
            if (this.scope && this.scope.view && this.scope.view.element) {
                this.scope.view.element.addEventListener('wheel', this.handleMouseWheel, { passive: false });
            }
        },

        handleMouseWheel(event) {
            event.preventDefault();
            const delta = event.deltaY > 0 ? 0.9 : 1.1;
            const newZoom = this.scope.view.zoom * delta;
            // Limit zoom between 0.1 and 10
            if (newZoom >= 0.1 && newZoom <= 10) {
                this.scope.view.zoom = newZoom;
                this.$emit('zoom-changed', newZoom);
            }
        },

        exportCanvas(format) {
            if (this.scope && this.scope.project) {
                if (format === 'png') {
                    const canvas = this.scope.view.element;
                    const link = document.createElement('a');
                    link.download = 'canvas-drawing.png';
                    link.href = canvas.toDataURL();
                    link.click();
                } else if (format === 'svg') {
                    const svg = this.scope.project.exportSVG({ asString: true });
                    const blob = new Blob([svg], { type: 'image/svg+xml' });
                    const link = document.createElement('a');
                    link.download = 'canvas-drawing.svg';
                    link.href = URL.createObjectURL(blob);
                    link.click();
                }
            }
        },

        updateColors(strokeColor, fillColor, strokeWidth) {
            this.currentStrokeColor = strokeColor;
            this.currentFillColor = fillColor;
            this.currentStrokeWidth = strokeWidth;
        },

        // Enhanced zoom methods
        zoomIn() {
            if (this.scope && this.scope.view) {
                const newZoom = this.scope.view.zoom * 1.2;
                if (newZoom <= 10) {
                    this.scope.view.zoom = newZoom;
                    this.$emit('zoom-changed', newZoom);
                }
            }
        },

        zoomOut() {
            if (this.scope && this.scope.view) {
                const newZoom = this.scope.view.zoom / 1.2;
                if (newZoom >= 0.1) {
                    this.scope.view.zoom = newZoom;
                    this.$emit('zoom-changed', newZoom);
                }
            }
        },

    }

}
</script>

<style scoped>
.canvas-style {
    cursor: crosshair;
    width: 100% !important;
    height: 500px !important;
    border: 2px solid rgb(139, 139, 139);
    display: block;
    margin: auto;
}
</style>
