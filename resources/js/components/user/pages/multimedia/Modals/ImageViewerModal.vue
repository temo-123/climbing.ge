<template>
    <stack-modal
        :show="showModal"
        title="Image Viewer"
        @close="closeModal"
        :modal-class="{ 'image-viewer-modal': true }"
        :saveButton="{ visible: false }"
        :cancelButton="{ visible: false }"
        :close-button="{ visible: false }"
    >

    <!-- <StackModal @close="$emit('close')"> -->
        <!-- Header -->
    <div class="container">
        <div class="viewer-header" v-if="image">
                <div class="image-info">
                    <h3 class="image-title">{{ image.name }}</h3>
                    <div class="image-meta">
                        <span v-if="image.size">{{ formatFileSize(image.size) }}</span>
                        <span v-if="image.dimensions">{{ image.dimensions }}</span>
                        <span>{{ image.type }}</span>
                    </div>
                </div>
                <div class="viewer-controls">
                    <button 
                        @click="$emit('previous')" 
                        class="control-btn"
                        :disabled="currentIndex === 0"
                    >
                        <i class="fa fa-chevron-left"></i>
                    </button>
                    <span class="image-counter">{{ currentIndex + 1 }} / {{ totalImages }}</span>
                    <button 
                        @click="$emit('next')" 
                        class="control-btn"
                        :disabled="currentIndex === totalImages - 1"
                    >
                        <i class="fa fa-chevron-right"></i>
                    </button>
                    <button @click="toggleZoom" class="control-btn">
                        <i :class="isZoomed ? 'fa fa-compress' : 'fa fa-expand'"></i>
                    </button>
                    <button @click="downloadImage" class="control-btn">
                        <i class="fa fa-download"></i>
                    </button>
                    <button @click="$emit('close')" class="control-btn close-btn">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
            </div>
            
            <!-- Image Display Area -->
            <div class="image-display" v-if="image" @mousedown="startPan" @mousemove="handlePan" @mouseup="endPan" @wheel="handleZoom">
                <div class="image-wrapper" :class="{ 'zoomed': isZoomed }">
                    <img
                        :src="image.url"
                        :alt="image.name"
                        class="main-image"
                        :class="{ 'zoomed': isZoomed }"
                        @load="onImageLoad"
                        @error="onImageError"
                    />
                </div>
                
                <!-- Loading overlay -->
                <div v-if="imageLoading" class="loading-overlay">
                    <div class="spinner"></div>
                </div>
                
                <!-- Error overlay -->
                <div v-if="imageError" class="error-overlay">
                    <i class="fa fa-exclamation-triangle"></i>
                    <p>Failed to load image</p>
                </div>
            </div>
            
            <!-- Navigation hint -->
            <div class="navigation-hint">
                <div class="hint-item">
                    <i class="fa fa-mouse-pointer"></i>
                    <span>Click and drag to pan</span>
                </div>
                <div class="hint-item">
                    <i class="fa fa-arrows-v"></i>
                    <span>Scroll to zoom</span>
                </div>
                <div class="hint-item">
                    <i class="fa fa-keyboard-o"></i>
                    <span>Use ← → arrows to navigate</span>
                </div>
                <div class="hint-item">
                    <i class="fa fa-times"></i>
                    <span>Press ESC to close</span>
                </div>
            </div>
        </div>
    </stack-modal>
</template>

<script>
import StackModal from '@innologica/vue-stackable-modal';

export default {
    name: 'ImageViewerModal',

    components: {
        StackModal
    },
    
    props: {
        showModal: {
            type: Boolean,
            default: false
        },
        image: {
            type: Object,
            default: null
        },
        images: {
            type: Array,
            default: () => []
        }
    },
    
    data() {
        return {
            isZoomed: false,
            imageLoading: true,
            imageError: false,
            zoomLevel: 1,
            panX: 0,
            panY: 0,
            isDragging: false,
            dragStartX: 0,
            dragStartY: 0,
            imageDimensions: { width: 0, height: 0 }
        }
    },
    
    computed: {
        currentIndex() {
            if (!this.image) return 0;
            return this.images.findIndex(img => img.path === this.image.path);
        },

        totalImages() {
            return this.images.length;
        }
    },
    
    mounted() {
        this.setupKeyboardControls();
        this.preloadImages();
    },
    
    beforeUnmount() {
        this.removeKeyboardControls();
    },
    
    methods: {
        toggleZoom() {
            this.isZoomed = !this.isZoomed;
            if (!this.isZoomed) {
                this.resetZoom();
            }
        },
        
        handleZoom(event) {
            event.preventDefault();
            const delta = event.deltaY > 0 ? -0.1 : 0.1;
            this.zoomLevel = Math.max(0.5, Math.min(3, this.zoomLevel + delta));
        },
        
        startPan(event) {
            this.isDragging = true;
            this.dragStartX = event.clientX - this.panX;
            this.dragStartY = event.clientY - this.panY;
        },
        
        handlePan(event) {
            if (!this.isDragging || !this.isZoomed) return;
            
            this.panX = event.clientX - this.dragStartX;
            this.panY = event.clientY - this.dragStartY;
        },
        
        endPan() {
            this.isDragging = false;
        },
        
        resetZoom() {
            this.zoomLevel = 1;
            this.panX = 0;
            this.panY = 0;
        },
        
        onImageLoad(event) {
            this.imageLoading = false;
            this.imageError = false;
            this.imageDimensions = {
                width: event.target.naturalWidth,
                height: event.target.naturalHeight
            };
        },
        
        onImageError() {
            this.imageLoading = false;
            this.imageError = true;
        },
        
        downloadImage() {
            const link = document.createElement('a');
            link.href = this.image.url;
            link.download = this.image.name;
            link.click();
        },
        
        setupKeyboardControls() {
            document.addEventListener('keydown', this.handleKeydown);
        },
        
        removeKeyboardControls() {
            document.removeEventListener('keydown', this.handleKeydown);
        },
        
        handleKeydown(event) {
            switch (event.key) {
                case 'Escape':
                    this.$emit('close');
                    break;
                case 'ArrowLeft':
                    if (this.currentIndex > 0) {
                        this.$emit('previous');
                    }
                    break;
                case 'ArrowRight':
                    if (this.currentIndex < this.totalImages - 1) {
                        this.$emit('next');
                    }
                    break;
                case '+':
                case '=':
                    this.zoomLevel = Math.min(3, this.zoomLevel + 0.2);
                    break;
                case '-':
                    this.zoomLevel = Math.max(0.5, this.zoomLevel - 0.2);
                    break;
                case '0':
                    this.resetZoom();
                    break;
            }
        },
        
        preloadImages() {
            if (!this.image) return;
            // Preload adjacent images for smoother navigation
            const preloadIndices = [this.currentIndex - 1, this.currentIndex + 1];
            preloadIndices.forEach(index => {
                if (index >= 0 && index < this.totalImages) {
                    const img = new Image();
                    img.src = this.images[index].url;
                }
            });
        },
        
        formatFileSize(bytes) {
            if (!bytes) return '';
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(1024));
            return Math.round(bytes / Math.pow(1024, i) * 100) / 100 + ' ' + sizes[i];
        },

        closeModal() {
            this.$emit('close');
        }
    }
}
</script>

<style>
/* Stack Modal Styles */
.StackModal {
    position: fixed !important;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.9);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
}

.StackModal__content {
    background: white;
    border-radius: 8px;
    overflow: hidden;
    max-width: 90vw;
    max-height: 90vh;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    display: flex;
    flex-direction: column;
}

/* Viewer Header */
.viewer-header {
    background: #f8f9fa;
    padding: 16px 20px;
    border-bottom: 1px solid #e9ecef;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
}

.image-info {
    flex: 1;
    min-width: 200px;
}

.image-title {
    margin: 0 0 8px 0;
    font-size: 18px;
    font-weight: 600;
    color: #495057;
    line-height: 1.2;
}

.image-meta {
    display: flex;
    gap: 12px;
    font-size: 14px;
    color: #6c757d;
}

.image-meta span {
    display: flex;
    align-items: center;
    gap: 4px;
}

.image-meta span:not(:last-child):after {
    content: "•";
    margin-left: 12px;
    color: #dee2e6;
}

.viewer-controls {
    display: flex;
    align-items: center;
    gap: 8px;
}

.control-btn {
    background: #007bff;
    border: none;
    color: white;
    padding: 8px 12px;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 36px;
    height: 36px;
}

.control-btn:hover:not(:disabled) {
    background: #0056b3;
    transform: translateY(-1px);
}

.control-btn:disabled {
    background: #6c757d;
    cursor: not-allowed;
    opacity: 0.6;
}

.control-btn.close-btn {
    background: #dc3545;
}

.control-btn.close-btn:hover {
    background: #c82333;
}

.image-counter {
    font-size: 14px;
    color: #495057;
    font-weight: 500;
    padding: 0 8px;
    white-space: nowrap;
}

/* Image Display Area */
.image-display {
    position: relative;
    flex: 1;
    min-height: 400px;
    max-height: 70vh;
    overflow: hidden;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: grab;
}

.image-display:active {
    cursor: grabbing;
}

.image-wrapper {
    position: relative;
    max-width: 100%;
    max-height: 100%;
    transition: transform 0.3s ease;
}

.image-wrapper.zoomed {
    cursor: grab;
}

.main-image {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    transition: transform 0.3s ease;
    user-select: none;
    -webkit-user-drag: none;
}

.main-image.zoomed {
    transform-origin: center center;
    cursor: grab;
}

/* Loading and Error States */
.loading-overlay,
.error-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: rgba(248, 249, 250, 0.9);
    color: #6c757d;
}

.error-overlay {
    color: #dc3545;
}

.loading-overlay i,
.error-overlay i {
    font-size: 48px;
    margin-bottom: 16px;
}

.loading-overlay p,
.error-overlay p {
    margin: 0;
    font-size: 16px;
}

.spinner {
    width: 40px;
    height: 40px;
    border: 4px solid #f3f3f3;
    border-top: 4px solid #007bff;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin-bottom: 16px;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Navigation Hint */
.navigation-hint {
    background: #f8f9fa;
    padding: 12px 20px;
    border-top: 1px solid #e9ecef;
    display: flex;
    justify-content: center;
    gap: 24px;
    flex-wrap: wrap;
}

.hint-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    color: #6c757d;
}

.hint-item i {
    font-size: 14px;
    color: #007bff;
}

/* Responsive Design */
@media (max-width: 768px) {
    .StackModal__content {
        max-width: 95vw;
        max-height: 95vh;
    }

    .viewer-header {
        flex-direction: column;
        align-items: stretch;
        gap: 12px;
    }

    .viewer-controls {
        justify-content: center;
    }

    .image-title {
        font-size: 16px;
        text-align: center;
    }

    .image-meta {
        justify-content: center;
        flex-wrap: wrap;
    }

    .navigation-hint {
        flex-direction: column;
        align-items: center;
        gap: 8px;
    }

    .image-display {
        min-height: 300px;
    }
}

@media (max-width: 480px) {
    .viewer-controls {
        gap: 4px;
    }

    .control-btn {
        padding: 6px 8px;
        min-width: 32px;
        height: 32px;
        font-size: 12px;
    }

    .image-title {
        font-size: 14px;
    }

    .image-meta {
        font-size: 12px;
        gap: 8px;
    }

    .hint-item {
        font-size: 12px;
    }
}
</style>
