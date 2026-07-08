<template>
    <div class="single-image-manager">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h5 class="mb-0">{{ title_prop || $t('admin.single_image.image_default_title') }}</h5>
        </div>

        <!-- Existing image from DB, shown until a new file is picked -->
        <div v-if="existing_image_url_prop && !image" class="mb-3">
            <p class="text-muted small mb-2">{{ $t('admin.single_image.current_image_label') }}</p>
            <div class="row g-3">
                <div class="col-6 col-md-4 col-xl-3">
                    <div class="card single-image-card-existing h-100">
                        <div class="single-image-card-img-wrap bg-light">
                            <img :src="existing_image_url_prop" class="single-image-card-img">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex flex-wrap gap-2 mb-3">
            <button type="button" class="btn btn-primary btn-sm" @click="triggerFileInput">
                {{ existing_image_url_prop ? $t('admin.single_image.replace_image_btn') : $t('admin.single_image.upload_image_btn') }}
            </button>
            <span v-if="isCompressing" class="badge bg-warning align-self-center">
                {{ $t('admin.single_image.compressing_image') }}
            </span>
        </div>

        <input type="file" ref="fileInput" style="display:none" :accept="accept_prop" @change="handleFileInput">

        <div v-if="!image"
             class="single-image-drop-zone mb-3"
             :class="{ 'drag-over': isExternalDragOver }"
             @click="triggerFileInput"
             @drop.prevent="handleExternalDrop"
             @dragover.prevent="isExternalDragOver = true"
             @dragleave.self="isExternalDragOver = false">
            <div class="single-image-drop-icon">📁</div>
            <div class="fw-medium">{{ $t('admin.single_image.drop_image_here') }}</div>
            <div class="text-muted small mt-1">
                {{ $t('admin.single_image.drop_zone_hint_generic', { size: max_size_mb }) }}
            </div>
        </div>

        <div v-if="image || compressing" class="row g-3">
            <div class="col-6 col-md-4 col-xl-3">
                <div class="card single-image-card h-100">
                    <div class="single-image-card-img-wrap bg-light position-relative">
                        <div v-if="compressing" class="single-image-card-compressing d-flex flex-column align-items-center justify-content-center">
                            <div class="spinner-border spinner-border-sm text-warning mb-1"></div>
                            <small class="text-muted">{{ $t('admin.gallery_manager.compressing_ellipsis_dots') }}</small>
                        </div>
                        <img v-else-if="preview" :src="preview" class="single-image-card-img">

                        <span v-if="compressed && !compressing"
                              class="badge bg-success position-absolute top-0 end-0 m-1"
                              :title="$t('admin.gallery_manager.compressed_from_tooltip', { size: formatFileSize(originalSize) })">
                            {{ $t('admin.gallery_manager.compressed_badge') }}
                        </span>
                    </div>

                    <div class="card-body p-2">
                        <div v-if="image" class="small">
                            <div class="text-muted small mb-1" v-if="existing_image_url_prop">{{ $t('admin.single_image.new_image_replaces_existing') }}</div>
                            <div class="text-truncate fw-medium" :title="image.name">{{ image.name }}</div>
                            <div class="text-muted mt-1">
                                <span v-if="compressed">
                                    {{ formatFileSize(originalSize) }} → {{ formatFileSize(image.size) }}
                                </span>
                                <span v-else>{{ formatFileSize(image.size) }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer p-1">
                        <button type="button" class="btn btn-danger btn-sm w-100" @click="removeImage">
                            {{ existing_image_url_prop ? $t('admin.single_image.cancel_replace_btn') : $t('admin.single_image.remove_btn') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <ImageCropperModal
            :show="crop_modal_visible"
            :file="crop_modal_file"
            :ratio_prop="crop_ratio_prop || { width: 16, height: 9 }"
            @confirm="onCropConfirm"
            @cancel="onCropCancel"
        />
    </div>
</template>


<script>
    import image_compression_mixin from '../../../../mixins/image_compression_mixin.js'
    import image_crop_mixin from '../../../../mixins/image_crop_mixin.js'
    import ImageCropperModal from '../image_cropper/ImageCropperModal.vue'

    export default {
        mixins: [image_compression_mixin, image_crop_mixin],
        components: { ImageCropperModal },
        props: {
            max_size_mb: { type: Number, default: 1.5 },
            title_prop: { type: String, default: '' },
            accept_prop: { type: String, default: 'image/*' },
            existing_image_url_prop: { type: String, default: '' },
        },
        data() {
            return {
                image: null,
                preview: null,
                compressing: false,
                compressed: false,
                originalSize: null,
                isExternalDragOver: false,
                isCompressing: false,
            }
        },
        beforeUnmount() {
            if (this.preview) URL.revokeObjectURL(this.preview);
        },
        methods: {
            triggerFileInput() {
                this.$refs.fileInput.value = '';
                this.$refs.fileInput.click();
            },

            async handleFileInput(event) {
                const file = event.target.files[0];
                if (file) await this.setFile(file);
            },

            async handleExternalDrop(event) {
                this.isExternalDragOver = false;
                const file = Array.from(event.dataTransfer.files).find(f => f.type.startsWith('image/'));
                if (file) await this.setFile(file);
            },

            async setFile(file) {
                if (!this.validateFileType(file)) return;

                const cropped = await this.maybeCropImage(file);
                if (!cropped) return;
                file = cropped;

                if (this.preview) URL.revokeObjectURL(this.preview);

                this.image = null;
                this.preview = null;
                this.compressed = false;
                this.originalSize = null;
                this.compressing = true;

                const processed = await this.compressIfNeeded(file);
                this.image = processed;
                this.preview = URL.createObjectURL(processed);
                this.compressed = processed !== file;
                this.originalSize = processed !== file ? file.size : null;
                this.compressing = false;

                this.emitUpdate();
            },

            removeImage() {
                if (this.preview) URL.revokeObjectURL(this.preview);
                this.image = null;
                this.preview = null;
                this.compressed = false;
                this.originalSize = null;
                this.emitUpdate();
            },

            emitUpdate() {
                this.$emit('update_single_image', this.image);
            },
        }
    }
</script>


<style scoped>
.single-image-manager {
    padding: 1rem 0;
}

.single-image-drop-zone {
    border: 2px dashed #ced4da;
    border-radius: 8px;
    padding: 24px 16px;
    text-align: center;
    transition: border-color 0.2s, background-color 0.2s;
    background: #f8f9fa;
    cursor: pointer;
}
.single-image-drop-zone.drag-over {
    border-color: #0d6efd;
    background: #e7f1ff;
}
.single-image-drop-icon {
    font-size: 1.8rem;
    margin-bottom: 6px;
}

.single-image-card {
    border-radius: 8px;
    overflow: hidden;
}

.single-image-card-existing {
    border-radius: 8px;
    overflow: hidden;
    border: 1px solid #dee2e6;
}

.single-image-card-img-wrap {
    height: 140px;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
}
.single-image-card-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.single-image-card-compressing {
    height: 100%;
    width: 100%;
}
</style>
