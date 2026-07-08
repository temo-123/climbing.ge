<template>
    <div class="pz-gallery" v-if="items.length">
        <div class="pz-main-row">
            <div
                class="pz-main-image-container"
                @mouseenter="onHoverEnter"
                @mousemove="onHoverMove"
                @mouseleave="onHoverLeave"
                @click="openLightbox()"
            >
                <img
                    ref="mainImg"
                    :src="currentImage.src"
                    :alt="currentImage.caption || altBase"
                    class="pz-main-image"
                />

                <div v-if="lensVisible" class="pz-lens" :style="lensStyle"></div>

                <div v-if="items.length > 1" class="pz-nav prev">
                    <button type="button" class="pz-nav-btn" @click.stop="prevImage" :aria-label="$t('shop.product.prev_image')">‹</button>
                </div>
                <div v-if="items.length > 1" class="pz-nav next">
                    <button type="button" class="pz-nav-btn" @click.stop="nextImage" :aria-label="$t('shop.product.next_image')">›</button>
                </div>

                <div class="pz-zoom-hint" v-if="hoverCapable && !lensVisible">
                    <i class="fa fa-search-plus" aria-hidden="true"></i>
                </div>
            </div>

            <div v-show="lensVisible" class="pz-zoom-panel" :style="zoomPanelStyle"></div>
        </div>

        <div v-if="items.length > 1" class="pz-thumbnails">
            <img
                v-for="(item, index) in items"
                :key="index"
                :src="item.thumbnail"
                :alt="item.caption || altBase"
                class="pz-thumb"
                :class="{ active: index === currentImageIndex }"
                @click="setCurrentImage(index)"
            />
        </div>

        <Teleport to="body">
            <div class="pz-lightbox" v-if="lightboxOpen" @click="closeLightbox">
                <button type="button" class="pz-lightbox-close" @click.stop="closeLightbox" :aria-label="$t('common.close')">✕</button>

                <div class="pz-lightbox-nav prev" v-if="items.length > 1">
                    <button type="button" class="pz-nav-btn" @click.stop="prevImage" :aria-label="$t('shop.product.prev_image')">‹</button>
                </div>
                <div class="pz-lightbox-nav next" v-if="items.length > 1">
                    <button type="button" class="pz-nav-btn" @click.stop="nextImage" :aria-label="$t('shop.product.next_image')">›</button>
                </div>

                <div
                    class="pz-lightbox-viewport"
                    @click.stop
                    @wheel.prevent="onWheelZoom"
                    @mousedown="onDragStart"
                    @mousemove="onDragMove"
                    @mouseup="onDragEnd"
                    @mouseleave="onDragEnd"
                    @touchstart="onTouchStart"
                    @touchmove.prevent="onTouchMove"
                    @touchend="onDragEnd"
                >
                    <img
                        :src="currentImage.src"
                        :alt="currentImage.caption || altBase"
                        class="pz-lightbox-image"
                        :style="lightboxImageStyle"
                        draggable="false"
                    />
                </div>

                <div class="pz-lightbox-controls" @click.stop>
                    <button type="button" @click="zoomOut" :disabled="lightboxScale <= minScale" :aria-label="$t('shop.product.zoom_out')">−</button>
                    <span>{{ Math.round(lightboxScale * 100) }}%</span>
                    <button type="button" @click="zoomIn" :disabled="lightboxScale >= maxScale" :aria-label="$t('shop.product.zoom_in')">+</button>
                    <button type="button" class="pz-reset-btn" v-if="lightboxScale !== 1" @click="resetZoom">{{ $t('shop.product.reset_zoom') }}</button>
                </div>
            </div>
        </Teleport>
    </div>
</template>

<script>
export default {
    props: {
        items: { type: Array, default: () => [] },
        altBase: { type: String, default: '' },
    },
    data() {
        return {
            currentImageIndex: 0,

            hoverCapable: false,
            lensVisible: false,
            lensX: 0,
            lensY: 0,
            lensSize: 150,
            panelWidth: 380,
            panelHeight: 420,
            zoomFactor: 2.5,
            imgRect: null,

            lightboxOpen: false,
            lightboxScale: 2,
            minScale: 1,
            maxScale: 5,
            panX: 0,
            panY: 0,
            dragging: false,
            dragStartX: 0,
            dragStartY: 0,
            panStartX: 0,
            panStartY: 0,
            lastTouchDist: null,
        };
    },
    computed: {
        currentImage() {
            return this.items[this.currentImageIndex] || {};
        },
        lensStyle() {
            const half = this.lensSize / 2;
            // lensX/lensY are relative to the photo's own top-left corner
            // (see getContainedRect) — add the letterbox inset back so the
            // lens <div>, positioned absolute within .pz-main-image-container,
            // lines up with the actual cursor position on screen.
            const offsetLeft = this.imgRect ? this.imgRect.left : 0;
            const offsetTop = this.imgRect ? this.imgRect.top : 0;
            return {
                width: this.lensSize + 'px',
                height: this.lensSize + 'px',
                left: (offsetLeft + this.lensX - half) + 'px',
                top: (offsetTop + this.lensY - half) + 'px',
            };
        },
        zoomPanelStyle() {
            if (!this.imgRect || !this.imgRect.width || !this.imgRect.height) return {};
            const bgW = this.imgRect.width * this.zoomFactor;
            const bgH = this.imgRect.height * this.zoomFactor;
            const ratioX = this.lensX / this.imgRect.width;
            const ratioY = this.lensY / this.imgRect.height;
            const bgPosX = -(ratioX * bgW - this.panelWidth / 2);
            const bgPosY = -(ratioY * bgH - this.panelHeight / 2);
            return {
                width: this.panelWidth + 'px',
                height: this.panelHeight + 'px',
                backgroundImage: `url(${this.currentImage.src})`,
                backgroundSize: `${bgW}px ${bgH}px`,
                backgroundPosition: `${bgPosX}px ${bgPosY}px`,
            };
        },
        lightboxImageStyle() {
            return {
                transform: `translate(${this.panX}px, ${this.panY}px) scale(${this.lightboxScale})`,
                cursor: this.lightboxScale > 1 ? (this.dragging ? 'grabbing' : 'grab') : 'zoom-in',
            };
        },
    },
    watch: {
        items() {
            this.currentImageIndex = 0;
        },
    },
    mounted() {
        this.hoverCapable = !!(window.matchMedia && window.matchMedia('(hover: hover) and (pointer: fine)').matches);

        this._closeHandler = () => {
            if (this.lightboxOpen) {
                this.lightboxOpen = false;
                document.body.classList.remove('body_hiden');
            }
        };
        window.addEventListener('imageclose', this._closeHandler);
    },
    beforeUnmount() {
        window.removeEventListener('imageclose', this._closeHandler);
        this._removePopstateHandler();
        if (this.lightboxOpen) {
            window.__imageOpen = false;
            document.body.classList.remove('body_hiden');
        }
    },
    methods: {
        setCurrentImage(index) {
            this.currentImageIndex = index;
        },
        prevImage() {
            if (this.items.length < 2) return;
            this.currentImageIndex = this.currentImageIndex > 0 ? this.currentImageIndex - 1 : this.items.length - 1;
            this.resetZoom();
        },
        nextImage() {
            if (this.items.length < 2) return;
            this.currentImageIndex = this.currentImageIndex < this.items.length - 1 ? this.currentImageIndex + 1 : 0;
            this.resetZoom();
        },

        // The main image uses object-fit:contain, so on any photo whose aspect
        // ratio doesn't exactly match the box, the visible picture is a
        // letterboxed sub-rect of the element's own box, not the whole box.
        // Mapping the cursor 1:1 against the element's bounding rect (like a
        // plain object-fit:cover image would allow) would drift the lens/zoom
        // off the real picture and, near the edges, "magnify" empty padding
        // instead of photo content. This computes that actual visible rect.
        getContainedRect() {
            const img = this.$refs.mainImg;
            if (!img) return null;
            const containerRect = img.getBoundingClientRect();
            const natW = img.naturalWidth;
            const natH = img.naturalHeight;
            if (!natW || !natH) {
                return { left: 0, top: 0, width: containerRect.width, height: containerRect.height, containerRect };
            }
            const boxRatio = containerRect.width / containerRect.height;
            const imgRatio = natW / natH;
            let width, height, offsetX = 0, offsetY = 0;
            if (imgRatio > boxRatio) {
                width = containerRect.width;
                height = width / imgRatio;
                offsetY = (containerRect.height - height) / 2;
            } else {
                height = containerRect.height;
                width = height * imgRatio;
                offsetX = (containerRect.width - width) / 2;
            }
            return { left: offsetX, top: offsetY, width, height, containerRect };
        },

        onHoverEnter() {
            if (!this.hoverCapable) return;
            this.lensVisible = true;
            this.imgRect = this.getContainedRect();
        },
        onHoverMove(e) {
            if (!this.hoverCapable || !this.lensVisible) return;
            const cr = this.getContainedRect();
            if (!cr) return;
            this.imgRect = cr;
            const half = this.lensSize / 2;
            // Cursor position relative to the container, minus the letterbox
            // inset, gives position within the actual photo (0,0 = photo's
            // own top-left corner) — clamped so the lens never sits over
            // empty padding outside the visible picture.
            let x = (e.clientX - cr.containerRect.left) - cr.left;
            let y = (e.clientY - cr.containerRect.top) - cr.top;
            x = Math.max(half, Math.min(cr.width - half, x));
            y = Math.max(half, Math.min(cr.height - half, y));
            this.lensX = x;
            this.lensY = y;
        },
        onHoverLeave() {
            this.lensVisible = false;
        },

        openLightbox() {
            this.lightboxOpen = true;
            this.lightboxScale = 2;
            this.panX = 0;
            this.panY = 0;
            window.__imageOpen = true;
            document.body.classList.add('body_hiden');
            this._savedUrl = location.href;
            history.pushState({ imageOpen: true }, '', location.href);
            this._popstateHandler = () => {
                history.pushState(null, '', this._savedUrl);
                this.closeLightbox();
            };
            window.addEventListener('popstate', this._popstateHandler);
        },
        closeLightbox() {
            if (!this.lightboxOpen) return;
            this.lightboxOpen = false;
            window.__imageOpen = false;
            document.body.classList.remove('body_hiden');
            this._removePopstateHandler();
        },
        _removePopstateHandler() {
            if (this._popstateHandler) {
                window.removeEventListener('popstate', this._popstateHandler);
                this._popstateHandler = null;
            }
        },

        zoomIn() {
            this.lightboxScale = Math.min(this.maxScale, this.lightboxScale + 0.5);
        },
        zoomOut() {
            this.lightboxScale = Math.max(this.minScale, this.lightboxScale - 0.5);
            if (this.lightboxScale === 1) {
                this.panX = 0;
                this.panY = 0;
            }
        },
        resetZoom() {
            this.lightboxScale = 1;
            this.panX = 0;
            this.panY = 0;
        },

        onWheelZoom(e) {
            const delta = e.deltaY < 0 ? 0.25 : -0.25;
            this.lightboxScale = Math.max(this.minScale, Math.min(this.maxScale, this.lightboxScale + delta));
            if (this.lightboxScale === 1) {
                this.panX = 0;
                this.panY = 0;
            }
        },

        onDragStart(e) {
            if (this.lightboxScale <= 1) return;
            this.dragging = true;
            this.dragStartX = e.clientX;
            this.dragStartY = e.clientY;
            this.panStartX = this.panX;
            this.panStartY = this.panY;
        },
        onDragMove(e) {
            if (!this.dragging) return;
            this.panX = this.panStartX + (e.clientX - this.dragStartX);
            this.panY = this.panStartY + (e.clientY - this.dragStartY);
        },
        onDragEnd() {
            this.dragging = false;
        },

        onTouchStart(e) {
            if (e.touches.length === 1 && this.lightboxScale > 1) {
                this.dragging = true;
                this.dragStartX = e.touches[0].clientX;
                this.dragStartY = e.touches[0].clientY;
                this.panStartX = this.panX;
                this.panStartY = this.panY;
            } else if (e.touches.length === 2) {
                this.lastTouchDist = this.touchDistance(e.touches);
            }
        },
        onTouchMove(e) {
            if (e.touches.length === 1 && this.dragging) {
                this.panX = this.panStartX + (e.touches[0].clientX - this.dragStartX);
                this.panY = this.panStartY + (e.touches[0].clientY - this.dragStartY);
            } else if (e.touches.length === 2 && this.lastTouchDist) {
                const dist = this.touchDistance(e.touches);
                const delta = (dist - this.lastTouchDist) * 0.01;
                this.lightboxScale = Math.max(this.minScale, Math.min(this.maxScale, this.lightboxScale + delta));
                this.lastTouchDist = dist;
            }
        },
        touchDistance(touches) {
            const dx = touches[0].clientX - touches[1].clientX;
            const dy = touches[0].clientY - touches[1].clientY;
            return Math.sqrt(dx * dx + dy * dy);
        },
    },
};
</script>

<style scoped>
.pz-gallery { position: relative; }

.pz-main-row {
    /* .pz-zoom-panel is position:absolute (see below), so it never competes
       for width with the main image — it floats over whatever is to the
       right (this sits in a half-width column next to the product info
       panel) instead of squeezing both into a too-narrow flex row. */
    position: relative;
}

.pz-main-image-container {
    position: relative;
    width: 100%;
    aspect-ratio: 16 / 9;
    overflow: hidden;
    border-radius: 10px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.12);
    background: #f5f5f5;
    cursor: zoom-in;
}

.pz-main-image {
    width: 100%;
    height: 100%;
    object-fit: contain;
    display: block;
    user-select: none;
}

.pz-lens {
    position: absolute;
    border: 2px solid #27bb7d;
    background: rgba(39, 187, 125, 0.18);
    pointer-events: none;
    border-radius: 4px;
}

.pz-zoom-panel {
    position: absolute;
    top: 0;
    left: 100%;
    margin-left: 16px;
    z-index: 20;
    border-radius: 10px;
    border: 1px solid #e0e0e0;
    box-shadow: 0 8px 28px rgba(0,0,0,0.22);
    background-repeat: no-repeat;
    background-color: #fff;
}

.pz-zoom-hint {
    position: absolute;
    top: 12px;
    right: 12px;
    width: 34px;
    height: 34px;
    border-radius: 50%;
    background: rgba(0,0,0,0.45);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    pointer-events: none;
}

.pz-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    z-index: 5;
}
.pz-nav.prev { left: 12px; }
.pz-nav.next { right: 12px; }

.pz-nav-btn {
    background: rgba(0,0,0,0.45);
    color: #fff;
    border: none;
    border-radius: 50%;
    width: 42px;
    height: 42px;
    font-size: 20px;
    cursor: pointer;
    transition: background 0.25s;
    display: flex;
    align-items: center;
    justify-content: center;
}
.pz-nav-btn:hover { background: rgba(0,0,0,0.75); }

.pz-thumbnails { margin-top: 12px; }
.pz-thumb {
    width: 72px;
    height: 72px;
    object-fit: cover;
    cursor: pointer;
    border: 2px solid transparent;
    border-radius: 6px;
    margin-right: 8px;
    transition: border-color 0.2s, opacity 0.2s;
    opacity: 0.75;
}
.pz-thumb.active { border-color: #27bb7d; opacity: 1; }
.pz-thumb:hover { border-color: #1a9460; opacity: 1; }

@media (max-width: 900px) {
    .pz-zoom-panel { display: none !important; }
}

/* ── Fullscreen lightbox ── */
.pz-lightbox {
    position: fixed;
    top: 0; right: 0; bottom: 0; left: 0;
    z-index: 1050;
    background: #000000d9;
}
.pz-lightbox-viewport {
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 70px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}
.pz-lightbox-image {
    max-width: 90%;
    max-height: 90%;
    transition: transform 0.05s linear;
    touch-action: none;
}
.pz-lightbox-close {
    position: absolute;
    top: 14px;
    right: 18px;
    z-index: 2;
    background: none;
    border: none;
    color: #fff;
    font-size: 1.6em;
    cursor: pointer;
}
.pz-lightbox-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    z-index: 2;
}
.pz-lightbox-nav.prev { left: 18px; }
.pz-lightbox-nav.next { right: 18px; }
.pz-lightbox-controls {
    position: absolute;
    bottom: 14px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    align-items: center;
    gap: 10px;
    background: rgba(255,255,255,0.1);
    padding: 8px 14px;
    border-radius: 30px;
    color: #fff;
}
.pz-lightbox-controls button {
    background: rgba(255,255,255,0.15);
    border: none;
    color: #fff;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    font-size: 1.1em;
    cursor: pointer;
}
.pz-lightbox-controls button:disabled { opacity: 0.4; cursor: default; }
.pz-lightbox-controls .pz-reset-btn {
    width: auto;
    border-radius: 16px;
    padding: 0 12px;
    font-size: 0.85em;
}
.pz-lightbox-controls span { min-width: 3em; text-align: center; }
</style>
