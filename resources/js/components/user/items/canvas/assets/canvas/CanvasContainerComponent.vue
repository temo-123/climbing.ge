<template>
    <div>
        <CanvasManager
            :canvas-id="'canvas-one'"
            :action="action"
            :json-prop="json_prop"
            :related-jsons="related_jsons"
            ref="canvasManager"
            :style="{
                backgroundImage: image ? 'url(' + image + ')' : 'none',
                backgroundSize: '100%'
            }"
            @canvas_data="handleCanvasData"
            @layers_updated="handleLayersUpdated"
            @layers_ready="handleLayersReady"
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

        // Access to group counter for layer management
        get groupCounter() {
            return this.isCanvasManagerReady && this.$refs.canvasManager ? this.$refs.canvasManager.getGroupCounter() : 0;
        },

        set groupCounter(value) {
            if (this.isCanvasManagerReady && this.$refs.canvasManager) {
                this.$refs.canvasManager.setGroupCounter(value);
            }
        }
    }
}
</script>
