<template>
    <div class="live-camera-timeline" v-if="camera">
        <h2>{{ camera.name || $t('guide.live_camera.title') }}</h2>

        <div v-if="loading">{{ $t('guide.live_camera.loading') }}</div>

        <template v-else-if="timeline_days.length">
            <div class="timeline-images">
                <img
                    v-for="img in selected_images"
                    :key="img.image_url"
                    :src="img.image_url"
                    class="timeline-image"
                />
                <img
                    v-if="selected_images.length === 0"
                    src="/images/site_img/image.png"
                    class="timeline-image timeline-image--placeholder"
                />
            </div>

            <div class="timeline-track-wrap">
                <input
                    type="range"
                    class="timeline-range"
                    min="0"
                    :max="timeline_days.length - 1"
                    v-model.number="selected_index"
                />
                <div class="timeline-ticks">
                    <span
                        v-for="(day, index) in timeline_days"
                        :key="day.date"
                        class="timeline-tick"
                        :class="{ 'has-images': day.images.length > 0, active: index === selected_index }"
                        @click="selected_index = index"
                    >
                        {{ day_label(day, index) }}
                    </span>
                </div>
            </div>
        </template>
    </div>
</template>

<script>
    export default {
        props: [
            'article_id',
        ],
        data() {
            return {
                camera: null,
                timeline_days: [],
                selected_index: 0,
                loading: false,
            }
        },
        computed: {
            selected_images() {
                return this.timeline_days[this.selected_index]?.images || []
            },
        },
        watch: {
            article_id() {
                this.load()
            },
        },
        mounted() {
            this.load()
        },
        methods: {
            load() {
                this.camera = null
                this.timeline_days = []

                if (!this.article_id) return

                axios
                .get('/get_live_camera/get_camera_for_article/' + this.article_id)
                .then(response => {
                    // Guard on `.id` specifically, not just truthiness — a "no
                    // camera" response can still be a truthy empty object.
                    if (!response.data || !response.data.id) return

                    this.camera = response.data
                    this.loading = true

                    return axios.get('/get_live_camera/get_camera_timeline/' + this.camera.id + '?days=7')
                })
                .then(response => {
                    if (!response) return

                    this.timeline_days = response.data.days
                    this.selected_index = this.timeline_days.length - 1
                })
                .catch(error => console.log(error))
                .finally(() => (this.loading = false))
            },
            day_label(day, index) {
                if (index === this.timeline_days.length - 1) return this.$t('guide.live_camera.today')
                return this.$formatDate ? this.$formatDate(day.date) : day.date
            },
        },
    }
</script>

<style scoped>
    .live-camera-timeline {
        margin: 30px 0;
    }
    .timeline-images {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 10px;
        margin-bottom: 15px;
        width: 100%;
    }
    .timeline-image {
        width: 100%;
        height: 420px;
        border-radius: 6px;
        object-fit: cover;
    }
    .timeline-image--placeholder {
        opacity: 0.5;
    }
    .timeline-track-wrap {
        width: 100%;
    }
    .timeline-range {
        width: 100%;
    }
    .timeline-ticks {
        display: flex;
        justify-content: space-between;
        font-size: 11px;
        color: #868e96;
    }
    .timeline-tick {
        cursor: pointer;
        padding: 2px 4px;
        white-space: nowrap;
    }
    .timeline-tick.has-images {
        color: #212529;
        font-weight: 600;
    }
    .timeline-tick.active {
        color: #7427bb;
        text-decoration: underline;
    }
</style>
