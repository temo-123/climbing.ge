<template>
    <StackModal
        size="xl"
        v-model="is_open"
        :title="$t('admin.live_camera.detail_title')"
        @close="is_open = false"
        :saveButton="{ visible: false }"
        :cancelButton="{ visible: true, title: $t('common.close'), btnClass: { 'btn btn-secondary': true } }"
    >
        <div v-if="loading">{{ $t('admin.live_camera.detail_loading') }}</div>

        <div v-else-if="camera" class="camera-detail">
            <div class="detail-section">
                <div class="detail-row">
                    <span class="detail-label">{{ $t('common.id') }}</span>
                    <span class="detail-value">{{ camera.id }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">{{ $t('common.name') }}</span>
                    <span class="detail-value font-weight-bold">{{ camera.name }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">{{ $t('admin.live_camera.detail_source') }}</span>
                    <span class="detail-value">{{ source_label }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">{{ $t('admin.common.public') }}</span>
                    <span class="detail-value">
                        <span v-if="camera.published" style="color:green;">{{ $t('admin.common.public') }}</span>
                        <span v-else style="color:#868e96;">{{ $t('admin.common.not_public') }}</span>
                    </span>
                </div>

                <div v-if="camera.source === 'link'" class="detail-row">
                    <span class="detail-label">{{ $t('admin.live_camera.detail_link') }}</span>
                    <span class="detail-value"><a :href="camera.link" target="_blank" rel="noopener">{{ camera.link }}</a></span>
                </div>

                <div v-else-if="camera.source === 'ubia'" class="detail-row">
                    <span class="detail-label">{{ $t('admin.live_camera.detail_device_uid') }}</span>
                    <span class="detail-value">{{ camera.ubia_device_uid }}</span>
                </div>
            </div>

            <div v-if="camera.source === 'ubia' || camera.source === 'upload'" class="detail-section">
                <div class="d-flex align-items-center justify-content-between mb-2 gallery-header">
                    <div class="detail-label mb-0">{{ $t('admin.live_camera.detail_photos') }}</div>
                    <select class="form-control form-control-sm gallery-period" v-model.number="period_days" @change="load_gallery">
                        <option :value="7">{{ $t('admin.live_camera.period_7') }}</option>
                        <option :value="30">{{ $t('admin.live_camera.period_30') }}</option>
                        <option :value="90">{{ $t('admin.live_camera.period_90') }}</option>
                        <option :value="null">{{ $t('admin.live_camera.period_all') }}</option>
                    </select>
                </div>

                <div v-if="photos_loading">{{ $t('admin.live_camera.detail_loading') }}</div>
                <p v-else-if="photos && photos.length === 0">{{ $t('admin.live_camera.no_photos_yet') }}</p>
                <div v-else-if="photos" class="photo-gallery">
                    <div v-for="photo in photos" :key="photo.image_url" class="photo-gallery-item">
                        <img :src="photo.image_url" />
                        <div><small>{{ photo.captured_at }}</small></div>
                    </div>
                </div>
            </div>
        </div>
    </StackModal>
</template>

<script>
export default {
    data() {
        return {
            is_open: false,
            camera: null,
            loading: false,
            photos: null,
            photos_loading: false,
            period_days: 7,
        }
    },
    computed: {
        source_label() {
            if (!this.camera) return ''
            if (this.camera.source === 'ubia') return this.$t('admin.live_camera.source_ubia')
            if (this.camera.source === 'upload') return this.$t('admin.live_camera.source_upload')
            return this.$t('admin.live_camera.source_link')
        },
    },
    methods: {
        show_modal(id) {
            this.is_open = true
            this.camera = null
            this.photos = null
            this.period_days = 7
            this.loading = true

            axios
                .get('/set_live_camera/get_editing_live_camera/' + id)
                .then(response => {
                    this.camera = response.data

                    if (this.camera.source === 'ubia' || this.camera.source === 'upload') {
                        this.load_gallery()
                    }
                })
                .catch(error => console.log(error))
                .finally(() => (this.loading = false))
        },
        load_gallery() {
            this.photos_loading = true
            this.photos = null

            axios
                .post('/set_live_camera/get_camera_gallery', { id: this.camera.id, days: this.period_days })
                .then(response => {
                    this.photos = response.data.photos
                })
                .catch(error => console.log(error))
                .finally(() => (this.photos_loading = false))
        },
    },
}
</script>

<style scoped>
.camera-detail {
    font-size: 14px;
}
.detail-section {
    border-bottom: 1px solid #f0f0f0;
    padding: 10px 0;
}
.detail-section:last-child {
    border-bottom: none;
}
.detail-row {
    display: flex;
    align-items: baseline;
    margin-bottom: 5px;
}
.detail-label {
    color: #868e96;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    min-width: 140px;
    flex-shrink: 0;
}
.detail-value {
    color: #212529;
}
.gallery-header {
    flex-wrap: wrap;
    gap: 8px;
}
.gallery-period {
    width: auto;
    display: inline-block;
}
.photo-gallery {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
    gap: 10px;
}
.photo-gallery-item img {
    width: 100%;
    height: 100px;
    object-fit: cover;
    border-radius: 4px;
}
</style>
