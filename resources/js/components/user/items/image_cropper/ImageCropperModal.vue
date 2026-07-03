<template>
    <div v-if="show" class="image-cropper-backdrop" @mousedown.self="cancel">
        <div class="image-cropper-modal">
            <div class="image-cropper-header">
                <h6 class="mb-0">Crop image to {{ ratio_prop.width }}:{{ ratio_prop.height }}</h6>
                <p class="text-muted small mb-0">Drag to reposition, use the slider to zoom.</p>
            </div>

            <div class="image-cropper-viewport"
                 ref="viewport"
                 :style="{ width: viewportWidth + 'px', height: viewportHeight + 'px' }"
                 @pointerdown="onPointerDown"
                 @pointermove="onPointerMove"
                 @pointerup="onPointerUp"
                 @pointercancel="onPointerUp"
                 @wheel.prevent="onWheel">
                <img v-if="imgSrc"
                     ref="img"
                     :src="imgSrc"
                     :style="imgStyle"
                     draggable="false"
                     @load="onImageLoad">
            </div>

            <div class="image-cropper-controls">
                <label class="small text-muted mb-1">Zoom</label>
                <input type="range" min="1" max="3" step="0.01" class="form-control-range"
                       v-model.number="zoom" @input="onZoomChange">
            </div>

            <div class="image-cropper-actions">
                <button type="button" class="btn btn-secondary btn-sm" @click="cancel">Cancel</button>
                <button type="button" class="btn btn-primary btn-sm" :disabled="!imageLoaded" @click="confirm">
                    Crop & Use
                </button>
            </div>
        </div>
    </div>
</template>


<script>
    export default {
        props: {
            show: { type: Boolean, default: false },
            file: { type: File, default: null },
            ratio_prop: { type: Object, default: () => ({ width: 16, height: 9 }) },
        },
        data() {
            return {
                imgSrc: null,
                imageLoaded: false,
                naturalWidth: 0,
                naturalHeight: 0,
                viewportWidth: 270,
                viewportHeight: 480,
                baseScale: 1,
                zoom: 1,
                offsetX: 0,
                offsetY: 0,
                dragging: false,
                dragStartX: 0,
                dragStartY: 0,
                dragOriginX: 0,
                dragOriginY: 0,
            }
        },
        computed: {
            currentScale() {
                return this.baseScale * this.zoom;
            },
            imgStyle() {
                return {
                    position: 'absolute',
                    left: this.offsetX + 'px',
                    top: this.offsetY + 'px',
                    width: (this.naturalWidth * this.currentScale) + 'px',
                    height: (this.naturalHeight * this.currentScale) + 'px',
                    maxWidth: 'none',
                };
            },
        },
        watch: {
            show(val) {
                if (val && this.file) this.loadFile(this.file);
            },
        },
        beforeUnmount() {
            if (this.imgSrc) URL.revokeObjectURL(this.imgSrc);
        },
        methods: {
            loadFile(file) {
                this.imageLoaded = false;
                if (this.imgSrc) URL.revokeObjectURL(this.imgSrc);
                this.viewportHeight = Math.round(this.viewportWidth * this.ratio_prop.height / this.ratio_prop.width);
                this.imgSrc = URL.createObjectURL(file);
            },

            onImageLoad() {
                this.naturalWidth = this.$refs.img.naturalWidth;
                this.naturalHeight = this.$refs.img.naturalHeight;
                this.baseScale = Math.max(
                    this.viewportWidth / this.naturalWidth,
                    this.viewportHeight / this.naturalHeight
                );
                this.zoom = 1;
                this.offsetX = (this.viewportWidth - this.naturalWidth * this.baseScale) / 2;
                this.offsetY = (this.viewportHeight - this.naturalHeight * this.baseScale) / 2;
                this.imageLoaded = true;
            },

            clampOffsets() {
                const w = this.naturalWidth * this.currentScale;
                const h = this.naturalHeight * this.currentScale;
                const minX = Math.min(0, this.viewportWidth - w);
                const minY = Math.min(0, this.viewportHeight - h);
                this.offsetX = Math.min(0, Math.max(minX, this.offsetX));
                this.offsetY = Math.min(0, Math.max(minY, this.offsetY));
            },

            onZoomChange() {
                this.clampOffsets();
            },

            onWheel(e) {
                const delta = e.deltaY > 0 ? -0.05 : 0.05;
                this.zoom = Math.min(3, Math.max(1, this.zoom + delta));
                this.clampOffsets();
            },

            onPointerDown(e) {
                if (!this.imageLoaded) return;
                this.dragging = true;
                this.dragStartX = e.clientX;
                this.dragStartY = e.clientY;
                this.dragOriginX = this.offsetX;
                this.dragOriginY = this.offsetY;
                this.$refs.viewport.setPointerCapture(e.pointerId);
            },

            onPointerMove(e) {
                if (!this.dragging) return;
                this.offsetX = this.dragOriginX + (e.clientX - this.dragStartX);
                this.offsetY = this.dragOriginY + (e.clientY - this.dragStartY);
                this.clampOffsets();
            },

            onPointerUp() {
                this.dragging = false;
            },

            confirm() {
                const cropX = -this.offsetX / this.currentScale;
                const cropY = -this.offsetY / this.currentScale;
                const cropW = this.viewportWidth / this.currentScale;
                const cropH = this.viewportHeight / this.currentScale;

                const MAX_OUT_W = 1080;
                const outW = Math.round(Math.min(cropW, MAX_OUT_W));
                const outH = Math.round(outW * this.ratio_prop.height / this.ratio_prop.width);

                const canvas = document.createElement('canvas');
                canvas.width = outW;
                canvas.height = outH;
                canvas.getContext('2d').drawImage(this.$refs.img, cropX, cropY, cropW, cropH, 0, 0, outW, outH);

                canvas.toBlob((blob) => {
                    const name = this.file.name.replace(/\.[^.]+$/, '.jpg');
                    const cropped = new File([blob], name, { type: 'image/jpeg', lastModified: Date.now() });
                    this.$emit('confirm', cropped);
                    this.reset();
                }, 'image/jpeg', 0.92);
            },

            cancel() {
                this.$emit('cancel');
                this.reset();
            },

            reset() {
                if (this.imgSrc) URL.revokeObjectURL(this.imgSrc);
                this.imgSrc = null;
                this.imageLoaded = false;
                this.zoom = 1;
            },
        }
    }
</script>


<style scoped>
.image-cropper-backdrop {
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0, 0, 0, 0.6);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 2000;
}

.image-cropper-modal {
    background: #fff;
    border-radius: 8px;
    padding: 1rem;
    max-width: 90vw;
    max-height: 90vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.75rem;
}

.image-cropper-header {
    text-align: center;
}

.image-cropper-viewport {
    position: relative;
    overflow: hidden;
    background: #222;
    border-radius: 6px;
    cursor: grab;
    touch-action: none;
    user-select: none;
}
.image-cropper-viewport:active {
    cursor: grabbing;
}

.image-cropper-controls {
    width: 100%;
    max-width: 270px;
}

.image-cropper-actions {
    display: flex;
    gap: 0.5rem;
    justify-content: flex-end;
    width: 100%;
    max-width: 270px;
}
</style>
