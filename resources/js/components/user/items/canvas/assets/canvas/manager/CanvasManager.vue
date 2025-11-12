<template>
    <canvas
        :id="canvasId"
        class="canvas-style"
        v-on:mousedown="handleMouseDown"
    />
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
        // New properties for enhanced features
        isPanning: false,
        panStartPoint: null,
        selectionRect: null
    }),
    watch: {
        jsonProp: {
            handler: function(newVal) {
                this.importJsonData(newVal);
            },
            immediate: true
        },
        relatedJsons: {
            handler: function(newVal) {
                this.importRelatedJsons();
            },
            immediate: true
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

            // Save initial empty state for undo functionality
            this.saveCanvasData();

            // Add mouse wheel event for zoom
            this.addMouseWheelListener();

            // Emit event to notify parent that layers are ready
            this.$emit('layers_ready');
        },

        beforeDestroy() {
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
            this.scope.project.activeLayer.removeChildren();
            this.selectedItem = null;
            this.saveCanvasData();
        },

        saveCanvasData() {
            const canvasData = JSON.stringify(this.scope.project.exportJSON());
            // Store current state in history before saving
            if (this.$parent && this.$parent.history) {
                const currentState = JSON.parse(canvasData);
                const lastState = this.$parent.history.length > 0 ? this.$parent.history[this.$parent.history.length - 1] : null;

                if (!lastState || JSON.stringify(currentState) !== JSON.stringify(lastState)) {
                    this.$parent.history.push(currentState);
                    if (this.$parent.history.length > 10) {
                        this.$parent.history.shift();
                    }
                    if (this.$parent.redoStack) {
                        this.$parent.redoStack = [];
                    }
                }
            }
            this.$emit('canvas_data', canvasData);
        },

        undoLastAction() {
            if (this.$parent && this.$parent.history && this.$parent.history.length > 0) {
                const currentState = JSON.stringify(this.scope.project.exportJSON());
                this.$parent.redoStack.push(JSON.parse(currentState));

                const lastState = this.$parent.history.pop();
                this.scope.project.clear();
                if (lastState) {
                    this.scope.project.importJSON(lastState);
                }
                this.scope.view.update();
                this.$emit('layers_updated');
                this.$emit('canvas_data', JSON.stringify(this.scope.project.exportJSON()));
            }
            if (this.$parent.redoStack && this.$parent.redoStack.length > 10) {
                this.$parent.redoStack = this.$parent.redoStack.slice(-10);
            }
        },

        redoLastAction() {
            if (this.$parent && this.$parent.redoStack && this.$parent.redoStack.length > 0) {
                const currentState = JSON.stringify(this.scope.project.exportJSON());
                this.$parent.history.push(JSON.parse(currentState));

                const nextState = this.$parent.redoStack.pop();
                this.scope.project.clear();
                if (nextState) {
                    this.scope.project.importJSON(nextState);
                }
                this.scope.view.update();
                this.$emit('layers_updated');
                this.$emit('canvas_data', JSON.stringify(this.scope.project.exportJSON()));
            }
            if (this.$parent.history && this.$parent.history.length > 10) {
                this.$parent.history = this.$parent.history.slice(-10);
            }
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
            // This will be used by drawing tools to get current colors
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

        saveImageToServer() {
            if (this.scope && this.scope.project) {
                const canvas = this.scope.view.element;
                const imageData = canvas.toDataURL('image/png');

                // Create a form data to send to server
                const formData = new FormData();
                formData.append('image', this.dataURLToBlob(imageData), 'canvas-image.png');

                // Send to server endpoint (you'll need to create this endpoint)
                fetch('/api/save-canvas-image', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Image saved successfully to public/images folder!');
                    } else {
                        alert('Failed to save image: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error saving image:', error);
                    alert('Error saving image to server');
                });
            }
        },

        dataURLToBlob(dataURL) {
            const arr = dataURL.split(',');
            const mime = arr[0].match(/:(.*?);/)[1];
            const bstr = atob(arr[1]);
            let n = bstr.length;
            const u8arr = new Uint8Array(n);
            while (n--) {
                u8arr[n] = bstr.charCodeAt(n);
            }
            return new Blob([u8arr], { type: mime });
        }
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
