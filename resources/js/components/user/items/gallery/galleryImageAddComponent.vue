<template>
    <div class="gallery-manager">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h5 class="mb-0">{{ title_prop || $t('admin.gallery_manager.add_gallery_images_title') }}</h5>
        </div>

        <!-- Toolbar -->
        <div class="d-flex flex-wrap gap-2 mb-3">
            <button class="btn btn-primary btn-sm" @click="triggerFileInput">
                {{ $t('admin.gallery_manager.add_images_btn') }}
            </button>
            <template v-if="hasImageFiles">
                <button class="btn btn-outline-secondary btn-sm" @click="sortImages('name')">
                    {{ $t('admin.gallery_manager.sort_by_name_btn') }}
                </button>
                <button class="btn btn-outline-secondary btn-sm" @click="sortImages('size')">
                    {{ $t('admin.gallery_manager.sort_by_size_btn') }}
                </button>
            </template>
            <button v-if="selectedImages.length > 0" class="btn btn-danger btn-sm" @click="bulkDelete">
                {{ $t('admin.gallery_manager.delete_selected_count_btn', { count: selectedImages.length }) }}
            </button>
            <span v-if="isCompressing" class="badge bg-warning align-self-center">
                {{ $t('admin.gallery_manager.compressing_images') }}
            </span>
        </div>

        <!-- Hidden multi-file input -->
        <input type="file" ref="fileInput" style="display:none" multiple accept="image/*" @change="handleFileInput">

        <!-- Drop zone -->
        <div class="gallery-drop-zone mb-3"
             :class="{ 'drag-over': isExternalDragOver }"
             @drop.prevent="handleExternalDrop"
             @dragover.prevent="isExternalDragOver = true"
             @dragleave.self="isExternalDragOver = false">
            <div class="gallery-drop-icon">📁</div>
            <div class="fw-medium">{{ $t('admin.gallery_manager.drop_images_here') }}</div>
            <div class="text-muted small mt-1">
                {{ $t('admin.gallery_manager.drop_zone_hint', { size: max_size_mb }) }}
            </div>
        </div>

        <!-- Image cards -->
        <div v-if="new_images.length > 0" class="row g-3">
            <div class="col-6 col-md-4 col-xl-3"
                 v-for="(image, index) in new_images"
                 :key="image.id">
                <div class="card gallery-card h-100"
                     :class="{
                         'gallery-card-dragging': draggedIndex === index,
                         'gallery-card-drag-target': dragTargetIndex === index && draggedIndex !== index
                     }"
                     draggable="true"
                     @dragstart="onDragStart($event, index)"
                     @dragend="onDragEnd"
                     @dragover.prevent="onDragOver($event, index)">

                    <!-- Card header: checkbox + drag handle -->
                    <div class="card-header p-2 d-flex justify-content-between align-items-center bg-light">
                        <div class="form-check mb-0">
                            <input class="form-check-input" type="checkbox"
                                   :id="'img-chk-' + image.id"
                                   :checked="selectedImages.includes(image.id)"
                                   @change="toggleImageSelection(image.id)">
                            <label class="form-check-label text-muted small" :for="'img-chk-' + image.id">
                                #{{ index + 1 }}
                            </label>
                        </div>
                        <span class="gallery-drag-handle text-muted" :title="$t('admin.gallery_manager.drag_to_reorder_tooltip')">⠿⠿</span>
                    </div>

                    <!-- Image preview -->
                    <div class="gallery-card-img-wrap bg-light position-relative">
                        <div v-if="image.compressing" class="gallery-card-compressing d-flex flex-column align-items-center justify-content-center">
                            <div class="spinner-border spinner-border-sm text-warning mb-1"></div>
                            <small class="text-muted">{{ $t('admin.gallery_manager.compressing_ellipsis_dots') }}</small>
                        </div>
                        <img v-else-if="image.preview" :src="image.preview" class="gallery-card-img">
                        <div v-else class="gallery-card-placeholder d-flex align-items-center justify-content-center text-muted small text-center p-2">
                            {{ $t('admin.gallery_manager.no_image_selected') }}
                        </div>

                        <!-- Compression badge -->
                        <span v-if="image.compressed && !image.compressing"
                              class="badge bg-success position-absolute top-0 end-0 m-1"
                              :title="$t('admin.gallery_manager.compressed_from_tooltip', { size: formatFileSize(image.originalSize) })">
                            {{ $t('admin.gallery_manager.compressed_badge') }}
                        </span>
                    </div>

                    <!-- File info -->
                    <div class="card-body p-2">
                        <div v-if="image.image" class="small">
                            <div class="text-truncate fw-medium" :title="image.image.name">{{ image.image.name }}</div>
                            <div class="text-muted mt-1">
                                <span v-if="image.compressed">
                                    {{ formatFileSize(image.originalSize) }} → {{ formatFileSize(image.image.size) }}
                                </span>
                                <span v-else>{{ formatFileSize(image.image.size) }}</span>
                            </div>
                        </div>
                        <div v-else class="small text-muted">{{ $t('admin.gallery_manager.no_file_yet') }}</div>
                    </div>

                    <!-- Delete -->
                    <div class="card-footer p-1">
                        <button class="btn btn-danger btn-sm w-100" @click="del_image(image.id)">
                            {{ $t('common.delete') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div v-else class="text-muted small text-center py-3">
            {{ $t('admin.gallery_manager.no_images_added_yet') }}
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
    import image_crop_mixin from '../../../../mixins/image_crop_mixin.js'
    import image_compression_mixin from '../../../../mixins/image_compression_mixin.js'
    import ImageCropperModal from '../image_cropper/ImageCropperModal.vue'

    export default {
        mixins: [image_compression_mixin, image_crop_mixin],
        components: { ImageCropperModal },
        props: {
            max_size_mb: { type: Number, default: 1.5 },
            title_prop: { type: String, default: '' },
        },
        data() {
            return {
                new_images: [],
                selectedImages: [],
                selectAll: false,
                draggedIndex: null,
                dragTargetIndex: null,
                isExternalDragOver: false,
                isCompressing: false,
                _idCounter: 0,
            }
        },
        computed: {
            hasImageFiles() {
                return this.new_images.filter(i => i.image).length > 1;
            }
        },
        beforeUnmount() {
            this.new_images.forEach(img => {
                if (img.preview) URL.revokeObjectURL(img.preview);
            });
        },
        methods: {
            nextId() {
                return ++this._idCounter;
            },

            triggerFileInput() {
                this.$refs.fileInput.value = '';
                this.$refs.fileInput.click();
            },

            async handleFileInput(event) {
                const files = Array.from(event.target.files);
                await this.addFiles(files);
            },

            async handleExternalDrop(event) {
                this.isExternalDragOver = false;
                if (this.draggedIndex !== null) return;
                const files = Array.from(event.dataTransfer.files).filter(f => f.type.startsWith('image/'));
                await this.addFiles(files);
            },

            async addFiles(files) {
                for (const file of files) {
                    if (!this.validateFileType(file)) continue;

                    const cropped = await this.maybeCropImage(file);
                    if (!cropped) continue;

                    const id = this.nextId();
                    const placeholder = { id, image: null, preview: null, compressing: true, compressed: false, originalSize: null };
                    this.new_images.push(placeholder);

                    const idx = this.new_images.findIndex(i => i.id === id);
                    const processed = await this.compressIfNeeded(cropped);
                    this.new_images[idx].image = processed;
                    this.new_images[idx].preview = URL.createObjectURL(processed);
                    this.new_images[idx].compressed = processed !== cropped;
                    this.new_images[idx].originalSize = processed !== cropped ? cropped.size : null;
                    this.new_images[idx].compressing = false;
                }
                this.update_perent_component_data();
            },

            del_image(id) {
                const idx = this.new_images.findIndex(obj => obj.id === id);
                if (idx === -1) return;
                const item = this.new_images[idx];
                if (item.preview) URL.revokeObjectURL(item.preview);
                this.new_images.splice(idx, 1);
                this.selectedImages = this.selectedImages.filter(sid => sid !== id);
                this.update_perent_component_data();
            },

            update_perent_component_data() {
                this.$emit('update_gallery_images', this.new_images);
            },

            toggleImageSelection(imageId) {
                const index = this.selectedImages.indexOf(imageId);
                if (index > -1) {
                    this.selectedImages.splice(index, 1);
                } else {
                    this.selectedImages.push(imageId);
                }
                this.selectAll = this.selectedImages.length === this.new_images.length;
            },

            bulkDelete() {
                if (!confirm(this.$t('admin.gallery_manager.confirm_delete_selected_images', { count: this.selectedImages.length }))) return;
                const toRemove = new Set(this.selectedImages);
                this.new_images = this.new_images.filter(img => {
                    if (toRemove.has(img.id)) {
                        if (img.preview) URL.revokeObjectURL(img.preview);
                        return false;
                    }
                    return true;
                });
                this.selectedImages = [];
                this.selectAll = false;
                this.update_perent_component_data();
            },

            sortImages(criteria) {
                this.new_images.sort((a, b) => {
                    if (!a.image || !b.image) return 0;
                    if (criteria === 'name') return a.image.name.localeCompare(b.image.name);
                    if (criteria === 'size') return a.image.size - b.image.size;
                    return 0;
                });
                this.update_perent_component_data();
            },

            onDragStart(event, index) {
                this.draggedIndex = index;
                event.dataTransfer.effectAllowed = 'move';
                event.dataTransfer.setData('text/plain', String(index));
            },

            onDragEnd() {
                this.draggedIndex = null;
                this.dragTargetIndex = null;
            },

            onDragOver(event, index) {
                if (this.draggedIndex === null || this.draggedIndex === index) return;
                this.dragTargetIndex = index;
                const dragged = this.new_images[this.draggedIndex];
                this.new_images.splice(this.draggedIndex, 1);
                this.new_images.splice(index, 0, dragged);
                this.draggedIndex = index;
                this.update_perent_component_data();
            },
        }
    }
</script>


<style scoped>
.gallery-manager {
    padding: 1rem 0;
}

.gallery-drop-zone {
    border: 2px dashed #ced4da;
    border-radius: 8px;
    padding: 24px 16px;
    text-align: center;
    transition: border-color 0.2s, background-color 0.2s;
    background: #f8f9fa;
    cursor: pointer;
}
.gallery-drop-zone.drag-over {
    border-color: #0d6efd;
    background: #e7f1ff;
}
.gallery-drop-icon {
    font-size: 1.8rem;
    margin-bottom: 6px;
}

.gallery-card {
    border-radius: 8px;
    transition: box-shadow 0.15s, opacity 0.15s;
    overflow: hidden;
}
.gallery-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.12);
}
.gallery-card-dragging {
    opacity: 0.45;
    box-shadow: 0 6px 20px rgba(0,0,0,0.2);
}
.gallery-card-drag-target {
    border-color: #0d6efd;
    box-shadow: 0 0 0 2px #0d6efd40;
}

.gallery-drag-handle {
    cursor: grab;
    font-size: 1.1rem;
    letter-spacing: -2px;
    user-select: none;
}
.gallery-drag-handle:active {
    cursor: grabbing;
}

.gallery-card-img-wrap {
    height: 140px;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
}
.gallery-card-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.gallery-card-compressing {
    height: 100%;
    width: 100%;
}
.gallery-card-placeholder {
    height: 100%;
    width: 100%;
    color: #adb5bd;
}
</style>
