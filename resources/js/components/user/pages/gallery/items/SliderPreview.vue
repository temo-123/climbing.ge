<template>
    <div class="slider_preview">
        <div class="preview_bar">
            <h5 class="preview_heading">{{ $t('head_slider.live_preview') }}</h5>

            <div class="device_switch" role="group">
                <button
                    v-for="device in devices"
                    :key="device.key"
                    type="button"
                    class="device_btn"
                    :class="{ active: active_device === device.key }"
                    :aria-pressed="active_device === device.key"
                    @click="active_device = device.key"
                >
                    <i :class="'fa ' + device.icon"></i> {{ $t('head_slider.device_' + device.key) }}
                </button>
            </div>
        </div>

        <p class="preview_hint">{{ $t('head_slider.live_preview_hint') }}</p>

        <div class="preview_stage">
            <div class="preview_frame" :class="'frame_' + active_device" :style="{ maxWidth: current_width }">
                <swiper-component
                    v-if="published_slides.length"
                    :slides_prop="published_slides"
                    :image_path_prop="image_path_prop"
                    :full_width="false"
                />
                <div v-else class="preview_empty">{{ $t('head_slider.no_published_slides') }}</div>
            </div>
        </div>
    </div>
</template>

<script>
    import swiperComponent from '../../../../global_components/SwiperComponent.vue'

    export default {
        components: {
            swiperComponent,
        },
        props: {
            slides_prop: { type: Array, default: () => [] },
            image_path_prop: { type: String, default: '' },
        },
        data() {
            return {
                active_device: 'desktop',
                devices: [
                    { key: 'desktop', icon: 'fa-desktop', width: '100%' },
                    { key: 'tablet', icon: 'fa-tablet', width: '768px' },
                    { key: 'phone', icon: 'fa-mobile', width: '390px' },
                ],
            }
        },
        computed: {
            // Same set the guidebook shows: published slides only
            published_slides() {
                return (this.slides_prop || []).filter(slide => slide.published == 1)
            },
            current_width() {
                return this.devices.find(d => d.key === this.active_device).width
            },
        },
    }
</script>

<style scoped>
.slider_preview {
    margin: 10px 0 25px;
}

.preview_bar {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
}

.preview_heading {
    margin: 0;
}

.device_switch {
    display: inline-flex;
    padding: 3px;
    gap: 3px;
    border-radius: 8px;
    background: #ececf1;
}

.device_btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border: 0;
    border-radius: 6px;
    background: transparent;
    color: #444;
    font-size: 14px;
    font-weight: 600;
    line-height: 1.2;
    cursor: pointer;
    transition: background 0.2s, color 0.2s, box-shadow 0.2s;
}

.device_btn:hover {
    color: #111;
}

.device_btn.active {
    background: #fff;
    color: #7427bb;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.15);
}

.device_btn:focus-visible {
    outline: 2px solid #7427bb;
    outline-offset: 1px;
}

.preview_hint {
    margin: 6px 0 10px;
    color: #777;
    font-size: 0.9rem;
}

.preview_stage {
    background: #f1f1f1;
    border-radius: 8px;
    padding: 16px;
    overflow: hidden;
}

.preview_frame {
    margin: 0 auto;
    transition: max-width 0.35s ease;
    border-radius: 6px;
    overflow: hidden;
    box-shadow: 0 4px 18px rgba(0, 0, 0, 0.18);
}

.preview_frame.frame_phone {
    border: 8px solid #222;
    border-radius: 24px;
}

.preview_frame.frame_tablet {
    border: 8px solid #222;
    border-radius: 16px;
}

.preview_empty {
    padding: 50px 20px;
    text-align: center;
    color: #777;
    background: #fff;
}

@media (max-width: 576px) {
    .preview_stage { padding: 8px; }
}
</style>
