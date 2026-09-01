<template>
    <span>
        <div class="smal_img" @click="open_image()">
            <img :src="img" :alt="img_alt" :class="img_class + ' cursor_pointer'" />
        </div>

        <Teleport to="body">
            <div
                class="open_img"
                v-if="open_img"
                @click="close_image()"
                @touchstart.passive="on_touch_start"
                @touchend.passive="on_touch_end"
            >
                <div class="close_bottom cursor_zoom_out" @click.stop="close_image()">X</div>

                <div v-if="has_gallery" class="img_counter" @click.stop>
                    {{ current_index + 1 }} / {{ gallery.length }}
                </div>

                <button
                    v-if="has_gallery"
                    type="button"
                    class="nav_arrow nav_prev"
                    :aria-label="$t('common.previous')"
                    @click.stop="prev_image()"
                >
                    <i class="fa fa-chevron-left" aria-hidden="true"></i>
                </button>

                <img
                    :src="current_src"
                    :alt="current_alt"
                    class="big_img_position cursor_zoom_out zoom"
                    style="max-width:96%;max-height:80%;position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);"
                />

                <button
                    v-if="has_gallery"
                    type="button"
                    class="nav_arrow nav_next"
                    :aria-label="$t('common.next')"
                    @click.stop="next_image()"
                >
                    <i class="fa fa-chevron-right" aria-hidden="true"></i>
                </button>

                <div v-if="has_gallery" class="thumb_strip" @click.stop>
                    <img
                        v-for="(item, i) in gallery"
                        :key="'thumb-' + i"
                        :src="item.src"
                        :alt="item.alt"
                        class="strip_thumb"
                        :class="{ strip_thumb_active: i === current_index }"
                        @click="go_to_image(i)"
                    />
                </div>
            </div>
        </Teleport>
    </span>
</template>

<script>
    export default {
        props: {
            img: String,
            img_alt: String,
            img_class: String,
            // Optional sibling images so the lightbox can be browsed without
            // closing it. Each entry: { src, alt }. Anything shorter than two
            // items keeps the old single-image behaviour.
            gallery: {
                type: Array,
                default: null,
            },
            // Position of this thumbnail inside `gallery` — where the lightbox opens.
            gallery_index: {
                type: Number,
                default: 0,
            },
        },
        data() {
            return {
                open_img: false,
                current_index: 0,
            };
        },
        computed: {
            has_gallery() {
                return Array.isArray(this.gallery) && this.gallery.length > 1;
            },
            current_item() {
                if (!this.has_gallery) return null;
                return this.gallery[this.current_index] || this.gallery[0];
            },
            current_src() {
                return this.current_item ? this.current_item.src : this.img;
            },
            current_alt() {
                return this.current_item ? this.current_item.alt : this.img_alt;
            },
        },
        mounted() {
            this._closeHandler = () => {
                if (this.open_img) {
                    this.open_img = false;
                    document.body.classList.remove('body_hiden');
                    this._removeKeyHandler();
                }
            };
            window.addEventListener('imageclose', this._closeHandler);
        },
        beforeUnmount() {
            window.removeEventListener('imageclose', this._closeHandler);
            this._removePopstateHandler();
            this._removeKeyHandler();
            if (this.open_img) {
                window.__imageOpen = false;
                document.body.classList.remove('body_hiden');
            }
        },
        methods: {
            open_image() {
                this.current_index = this.has_gallery
                    ? Math.min(Math.max(this.gallery_index, 0), this.gallery.length - 1)
                    : 0;
                this.open_img = true;
                window.__imageOpen = true;
                document.body.classList.add('body_hiden');
                this._savedUrl = location.href;
                history.pushState({ imageOpen: true }, '', location.href);
                this._popstateHandler = () => {
                    // Back button pressed while image is open — close image and stay on same URL
                    history.pushState(null, '', this._savedUrl);
                    this.close_image();
                };
                window.addEventListener('popstate', this._popstateHandler);

                this._keyHandler = (e) => {
                    if (!this.open_img) return;
                    if (e.key === 'Escape') {
                        this.close_image();
                    } else if (this.has_gallery && e.key === 'ArrowRight') {
                        this.next_image();
                    } else if (this.has_gallery && e.key === 'ArrowLeft') {
                        this.prev_image();
                    }
                };
                window.addEventListener('keydown', this._keyHandler);
            },
            close_image() {
                if (!this.open_img) return;
                this.open_img = false;
                window.__imageOpen = false;
                document.body.classList.remove('body_hiden');
                this._removePopstateHandler();
                this._removeKeyHandler();
            },
            next_image() {
                if (!this.has_gallery) return;
                this.current_index = (this.current_index + 1) % this.gallery.length;
            },
            prev_image() {
                if (!this.has_gallery) return;
                this.current_index = (this.current_index - 1 + this.gallery.length) % this.gallery.length;
            },
            go_to_image(idx) {
                if (!this.has_gallery) return;
                this.current_index = idx;
            },
            on_touch_start(e) {
                this._touchStartX = e.changedTouches[0].clientX;
            },
            on_touch_end(e) {
                if (!this.has_gallery || this._touchStartX == null) return;
                const delta = e.changedTouches[0].clientX - this._touchStartX;
                this._touchStartX = null;
                if (Math.abs(delta) < 50) return;
                if (delta < 0) this.next_image();
                else this.prev_image();
            },
            _removePopstateHandler() {
                if (this._popstateHandler) {
                    window.removeEventListener('popstate', this._popstateHandler);
                    this._popstateHandler = null;
                }
            },
            _removeKeyHandler() {
                if (this._keyHandler) {
                    window.removeEventListener('keydown', this._keyHandler);
                    this._keyHandler = null;
                }
            },
        }
    }
</script>

<style scoped>

    .close_bottom{
        float: right;
        cursor: pointer;
        color: #b3b2b2d9;
        font-size: 2em;
        margin-right: 0.4em;
        margin-top: 0.4em;
    }

    @media (max-width: 756px) {
        .close_bottom{
            position: absolute;
            left: 50%;
            right: 0;
            cursor: pointer;
            color: #b3b2b2d9;
            font-size: 2em;
            margin-right: 0.4em;
            margin-top: 0.4em;
        }
    }
    .smal_img img{
        height: 100%;
        cursor: zoom-in;
    }
    .open_img{
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        z-index: 1050;
        background: #000000d9;

        transition: opacity .15s linear;
    }
    .big_img_position{
        max-width: 96%;
        max-height: 80%;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    /* ── Gallery navigation ── */
    .nav_arrow{
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        z-index: 2;
        border: none;
        background: #00000059;
        color: #ffffffd9;
        font-size: 1.6em;
        line-height: 1;
        width: 2em;
        height: 2.4em;
        cursor: pointer;
        border-radius: 4px;
        transition: background .15s linear;
    }
    .nav_arrow:hover{
        background: #000000a6;
        color: #fff;
    }
    .nav_prev{
        left: 0.4em;
    }
    .nav_next{
        right: 0.4em;
    }
    .img_counter{
        position: absolute;
        top: 0.7em;
        left: 50%;
        transform: translateX(-50%);
        z-index: 2;
        color: #b3b2b2d9;
        font-size: 1em;
        letter-spacing: .05em;
    }
    .thumb_strip{
        position: absolute;
        bottom: 0.6em;
        left: 50%;
        transform: translateX(-50%);
        z-index: 2;
        display: flex;
        gap: 6px;
        max-width: 96%;
        overflow-x: auto;
        padding: 4px;
    }
    .strip_thumb{
        width: 62px;
        height: 44px;
        object-fit: cover;
        border-radius: 3px;
        cursor: pointer;
        opacity: .5;
        border: 2px solid transparent;
        flex: 0 0 auto;
        transition: opacity .15s linear;
    }
    .strip_thumb:hover{
        opacity: .85;
    }
    .strip_thumb_active{
        opacity: 1;
        border-color: #00e64d;
    }

    @media (max-width: 756px) {
        .nav_arrow{
            font-size: 1.2em;
            background: #00000080;
        }
        .strip_thumb{
            width: 48px;
            height: 34px;
        }
    }
</style>
