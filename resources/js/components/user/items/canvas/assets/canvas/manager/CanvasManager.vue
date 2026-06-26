<template>
    <canvas :id="canvasId" class="canvas-style" :style="{ height: canvasHeight + 'px' }" v-on:mousedown="handleMouseDown" />
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
        editingSegment: null,
        editingSegmentDot: null,
        isPanning: false,
        panLastScreen: null,
        selectionRect: null,
        currentStrokeColor: '#ff0000',
        currentFillColor: null,
        currentStrokeWidth: 3,
        minZoom: 1,
        rasterBounds: null,
        canvasHeight: 500
    }),
    watch: {
        jsonProp: {
            handler: function(newVal, oldVal) {
                // Only re-import after mount (scope exists) and when value actually changes
                if (!this.scope || newVal === oldVal) return;
                if (newVal) {
                    this.importJsonData(newVal);
                } else {
                    // Drawing was cleared — wipe the main layer so the canvas is blank
                    const mainLayer = this.scope.project.layers.find(l => l.name === 'main');
                    if (mainLayer) mainLayer.removeChildren();
                    this._activateMainLayer();
                    this.scope.view.update();
                }
            },
            immediate: false,
        },
        relatedJsons: {
            handler: function(newVal) {
                if (this.scope) this.importRelatedJsons();
            },
            immediate: false
        },
        action(newVal) {
            if (this.scope && this.scope.view && this.scope.view.element) {
                if (newVal === 9) this.scope.view.element.style.cursor = 'grab';
                else if (newVal === 16) this.scope.view.element.style.cursor = 'cell';
                else this.scope.view.element.style.cursor = 'crosshair';
            }
        },
        image(newVal) {
            this.loadBackgroundRaster(newVal);
        }
    },
    mounted() {
        this.scope = new paper.PaperScope();
        this.scope.setup(this.canvasId);
        this.scope.activate();

        this.scope.project.clear();
        this.updateView(this.zoomLevel, this.panOffset);
        this.importRelatedJsons();

        if (this.jsonProp) {
            this.importJsonData(this.jsonProp);
        } else {
            // No drawing data — create an empty main layer so the canvas is ready to draw
            this._activateMainLayer();
        }

        // Initialise the undo history with the current state but do NOT emit canvas_data —
        // emitting here would overwrite the parent's route_json before the user has drawn anything.
        this._initHistory();

        this.addMouseWheelListener();

        if (this.image) this.loadBackgroundRaster(this.image);

        this.$emit('layers_ready');
    },

    beforeUnmount() {
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
                if (layer.name && (layer.name.startsWith('related-') || layer.name === 'background')) return;
                layer.removeChildren();
            });
            this.selectedItem = null;
            this.saveCanvasData();
        },

        // Loads the background image as a Paper.js Raster.
        // First pre-loads the image to get its natural size, resizes the canvas
        // to match the image aspect ratio, then creates the Raster.
        loadBackgroundRaster(url) {
            if (!this.scope) return;
            this.scope.activate();

            const existing = this.scope.project.layers.find(l => l.name === 'background');
            if (existing) existing.remove();

            if (!url) {
                this.minZoom = 0.1;
                this.rasterBounds = null;
                this.scope.view.update();
                this._activateMainLayer();
                return;
            }

            const tempImg = new Image();
            const doLoad = (naturalW, naturalH) => {
                const canvasEl = this.scope.view.element;
                const cW = Math.max(100, canvasEl.offsetWidth || 800);
                const cH = naturalW > 0
                    ? Math.round(cW * naturalH / naturalW)
                    : 500;

                // Resize canvas height to match image aspect ratio.
                this.canvasHeight = cH;

                this.$nextTick(() => {
                    if (!this.scope) return;
                    // Sync Paper.js view to the new canvas dimensions.
                    this.scope.view.viewSize = new paper.Size(cW, cH);

                    // new Layer() auto-activates itself — create raster on it immediately.
                    const bgLayer = new paper.Layer();
                    bgLayer.name = 'background';
                    const raster = new paper.Raster();
                    this._activateMainLayer();

                    raster.onLoad = () => {
                        if (!this.scope) return;
                        this.scope.activate();
                        const viewSize = this.scope.view.viewSize;
                        const scale = Math.max(
                            viewSize.width  / raster.width,
                            viewSize.height / raster.height
                        );
                        raster.scale(scale);
                        raster.position = new paper.Point(viewSize.width / 2, viewSize.height / 2);
                        raster.locked = true;
                        bgLayer.locked = true;

                        this.rasterBounds = {
                            left:   raster.bounds.left,
                            right:  raster.bounds.right,
                            top:    raster.bounds.top,
                            bottom: raster.bounds.bottom
                        };

                        this.minZoom = 1;
                        if (this.scope.view.zoom < this.minZoom) {
                            this.scope.view.zoom = this.minZoom;
                            this.$emit('zoom-changed', this.minZoom);
                        }

                        const firstOtherLayer = this.scope.project.layers.find(l => l.name !== 'background');
                        if (firstOtherLayer) bgLayer.insertBelow(firstOtherLayer);

                        this._activateMainLayer();
                        this.scope.view.update();
                    };

                    raster.source = url;
                });
            };

            tempImg.onload  = () => doLoad(tempImg.naturalWidth, tempImg.naturalHeight);
            tempImg.onerror = () => doLoad(0, 0); // fallback: keep current canvas size
            tempImg.src = url;
        },

        // Ensures the 'main' drawing layer is the active Paper.js layer.
        // Called after any operation that might leave a non-drawing layer active.
        _activateMainLayer() {
            if (!this.scope) return;
            const mainLayer = this.scope.project.layers.find(l => l.name === 'main');
            if (mainLayer) {
                mainLayer.activate();
                return;
            }
            // Fallback: activate the first non-background, non-related layer
            const drawLayer = this.scope.project.layers.find(
                l => !l.name || (!l.name.startsWith('related-') && l.name !== 'background')
            );
            if (drawLayer) {
                drawLayer.activate();
            } else {
                // No drawing layer exists at all — create one so drawings have a home
                const newMain = new paper.Layer();
                newMain.name = 'main';
            }
        },

        // Clamps view.center so the background raster always covers the visible canvas area.
        // Called after every zoom or pan change to prevent empty space at the edges.
        _clampPan() {
            if (!this.rasterBounds || !this.scope) return;
            const Z = this.scope.view.zoom;
            const viewSize = this.scope.view.viewSize;
            const halfW = viewSize.width  / (2 * Z);
            const halfH = viewSize.height / (2 * Z);

            const minCx = this.rasterBounds.left   + halfW;
            const maxCx = this.rasterBounds.right  - halfW;
            const minCy = this.rasterBounds.top    + halfH;
            const maxCy = this.rasterBounds.bottom - halfH;

            const c  = this.scope.view.center;
            const cx = Math.min(maxCx, Math.max(minCx, c.x));
            const cy = Math.min(maxCy, Math.max(minCy, c.y));
            if (cx !== c.x || cy !== c.y) {
                this.scope.view.center = new paper.Point(cx, cy);
            }
        },

        // Exports only the drawing layers (excludes background and related) to JSON
        // so saved data stays clean and undo/redo history doesn't include background.
        _getDrawingJson() {
            const bgLayer = this.scope.project.layers.find(l => l.name === 'background');
            const relatedLayers = this.scope.project.layers.filter(l => l.name && l.name.startsWith('related-'));

            if (bgLayer) bgLayer.remove();
            relatedLayers.forEach(l => l.remove());

            const json = JSON.stringify(this.scope.project.exportJSON());

            // Restore: background below everything, related on top
            if (bgLayer) {
                const drawingLayers = this.scope.project.layers;
                this.scope.project.addLayer(bgLayer);
                if (drawingLayers.length > 0) bgLayer.insertBelow(drawingLayers[0]);
            }
            relatedLayers.forEach(l => this.scope.project.addLayer(l));

            return json;
        },

        // Seeds the undo history with the current canvas state without emitting canvas_data.
        // Called once from mounted() so undo/redo works from the first user action.
        _initHistory() {
            try {
                const state = JSON.parse(this._getDrawingJson());
                this.history = [state];
                this.redoStack = [];
                this.$emit('history-changed', 0, 0);
            } catch (_) {}
        },

        saveCanvasData() {
            const canvasData = this._getDrawingJson();
            const currentState = JSON.parse(canvasData);
            const lastState = this.history.length > 0 ? this.history[this.history.length - 1] : null;
            if (!lastState || JSON.stringify(currentState) !== JSON.stringify(lastState)) {
                this.history.push(currentState);
                if (this.history.length > 20) this.history.shift();
                this.redoStack = [];
            }
            this.$emit('canvas_data', canvasData);
            this.$emit('history-changed', this.history.length - 1, this.redoStack.length);
        },

        undoLastAction() {
            if (this.history.length <= 1) return;

            // Move the most-recent state to the redo stack, then restore the one before it.
            const lastState = this.history.pop();
            this.redoStack.push(lastState);
            if (this.redoStack.length > 20) this.redoStack = this.redoStack.slice(-20);

            const previousState = this.history[this.history.length - 1];

            const bgLayer = this.scope.project.layers.find(l => l.name === 'background');
            const relatedLayers = this.scope.project.layers.filter(l => l.name && l.name.startsWith('related-'));
            if (bgLayer) bgLayer.remove();
            relatedLayers.forEach(l => l.remove());

            this.scope.project.clear();
            if (previousState) this.scope.project.importJSON(previousState);

            if (bgLayer) {
                const drawingLayers = this.scope.project.layers;
                this.scope.project.addLayer(bgLayer);
                if (drawingLayers.length > 0) bgLayer.insertBelow(drawingLayers[0]);
            }
            relatedLayers.forEach(l => this.scope.project.addLayer(l));

            this._activateMainLayer();
            this._repositionRelatedLayersBelow();
            this.scope.view.update();
            this.$emit('layers_updated');
            this.$emit('canvas_data', this._getDrawingJson());
            this.$emit('history-changed', this.history.length - 1, this.redoStack.length);
        },

        redoLastAction() {
            if (this.redoStack.length === 0) return;

            // Pop the next state from redo and push it onto history (it becomes the new current).
            const nextState = this.redoStack.pop();
            this.history.push(nextState);
            if (this.history.length > 20) this.history = this.history.slice(-20);

            const bgLayer = this.scope.project.layers.find(l => l.name === 'background');
            const relatedLayers = this.scope.project.layers.filter(l => l.name && l.name.startsWith('related-'));
            if (bgLayer) bgLayer.remove();
            relatedLayers.forEach(l => l.remove());

            this.scope.project.clear();
            if (nextState) this.scope.project.importJSON(nextState);

            if (bgLayer) {
                const drawingLayers = this.scope.project.layers;
                this.scope.project.addLayer(bgLayer);
                if (drawingLayers.length > 0) bgLayer.insertBelow(drawingLayers[0]);
            }
            relatedLayers.forEach(l => this.scope.project.addLayer(l));

            this._activateMainLayer();
            this._repositionRelatedLayersBelow();
            this.scope.view.update();
            this.$emit('layers_updated');
            this.$emit('canvas_data', this._getDrawingJson());
            this.$emit('history-changed', this.history.length - 1, this.redoStack.length);
        },

        getCanvasScope() {
            return this.scope;
        },

        getGroupCounter() {
            return this.groupCounter;
        },

        setGroupCounter(value) {
            this.groupCounter = value;
        },

        updateView(zoomLevel, panOffset) {
            if (this.scope && this.scope.view) {
                this.scope.view.zoom = zoomLevel;
                this.scope.view.center = new paper.Point(
                    this.scope.view.viewSize.width  / 2 + panOffset.x,
                    this.scope.view.viewSize.height / 2 + panOffset.y
                );
            }
        },

        addMouseWheelListener() {
            if (this.scope && this.scope.view && this.scope.view.element) {
                this.scope.view.element.addEventListener('wheel', this.handleMouseWheel, { passive: false });
            }
        },

        // Zooms centred on the cursor position so both drawings and the background Raster stay fixed.
        handleMouseWheel(event) {
            event.preventDefault();
            const factor   = event.deltaY > 0 ? 0.9 : 1.1;
            const newZoom  = Math.min(10, Math.max(this.minZoom, this.scope.view.zoom * factor));
            const mousePos = new paper.Point(event.offsetX, event.offsetY);
            const before   = this.scope.view.viewToProject(mousePos);
            this.scope.view.zoom = newZoom;
            const after    = this.scope.view.viewToProject(mousePos);
            this.scope.view.center = this.scope.view.center.add(before.subtract(after));
            this._clampPan();
            this.$emit('zoom-changed', newZoom);
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
            this.currentFillColor   = fillColor;
            this.currentStrokeWidth = strokeWidth;
        },

        zoomIn() {
            if (this.scope && this.scope.view) {
                this.scope.view.zoom = Math.min(10, this.scope.view.zoom * 1.2);
                this._clampPan();
                this.$emit('zoom-changed', this.scope.view.zoom);
            }
        },

        zoomOut() {
            if (this.scope && this.scope.view) {
                this.scope.view.zoom = Math.max(this.minZoom, this.scope.view.zoom / 1.2);
                this._clampPan();
                this.$emit('zoom-changed', this.scope.view.zoom);
            }
        },

        // Reset to the "fit to page" zoom (= minZoom when image loaded, else 1).
        zoomReset() {
            if (this.scope && this.scope.view) {
                const fitZoom = this.minZoom;
                this.scope.view.zoom = fitZoom;
                this.scope.view.center = new paper.Point(
                    this.scope.view.viewSize.width  / 2,
                    this.scope.view.viewSize.height / 2
                );
                this.$emit('zoom-changed', fitZoom);
            }
        },

    }

}
</script>

<style scoped>
.canvas-style {
    cursor: crosshair;
    width: 100%;
    border: 2px solid rgb(139, 139, 139);
    display: block;
    margin: auto;
}
</style>
