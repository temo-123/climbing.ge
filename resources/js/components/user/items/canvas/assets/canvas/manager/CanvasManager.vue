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
            group: 0
        }
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

        // Import related JSONs first (background)
        this.importRelatedJsons();

        // Import JSON if it was passed during initialization (foreground, editable)
        if (this.jsonProp) {
            this.importJsonData(this.jsonProp);
        }

        // Save initial empty state for undo functionality
        this.saveCanvasData();

        // Emit event to notify parent that layers are ready
        this.$emit('layers_ready');
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
