<template>
    <div class="mount-map-modal-wrapper">
        <button class="btn btn-sm btn-map-open" @click="openModal">
            <i class="fa fa-map-marker"></i> {{ $t('guide.region.show_map') || 'Show Map' }}
        </button>

        <StackModal
            v-model="show"
            :title="title + ' — Map'"
            :size="'xxxl'"
            :save-button="false"
            @close="show = false"
        >
            <div class="mount-map-tabs" v-if="map && hasOsmData">
                <div class="btn-group" role="group" aria-label="Map Source">
                    <button
                        type="button"
                        class="btn btn-sm"
                        :class="{'btn-success': activeTab === 'google', 'btn-secondary': activeTab !== 'google'}"
                        @click="activeTab = 'google'"
                    >
                        <i class="fa fa-map"></i> Google Map
                    </button>
                    <button
                        type="button"
                        class="btn btn-sm"
                        :class="{'btn-success': activeTab === 'osm', 'btn-secondary': activeTab !== 'osm'}"
                        @click="activeTab = 'osm'"
                    >
                        <i class="fa fa-map-o"></i> OpenStreetMap
                    </button>
                </div>
            </div>

            <div class="mount-map-content" v-if="map && (!hasOsmData || activeTab === 'google')">
                <span v-html="map"></span>
            </div>

            <div class="mount-map-osm" v-if="hasOsmData && (!map || activeTab === 'osm')">
                <l-map
                    style="width: 100%; height: 500px; border-radius: 8px;"
                    :zoom="mapZoom"
                    :center="mapCenter"
                    :use-global-leaflet="false"
                >
                    <l-tile-layer
                        url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
                        attribution="&copy; <a href='https://www.openstreetmap.org/copyright'>OpenStreetMap</a> contributors"
                    />
                    <l-marker
                        v-for="summit in geoSummits"
                        :key="summit.id"
                        :lat-lng="[summit.latitude, summit.longitude]"
                    >
                        <l-popup :options="{ maxWidth: 260, minWidth: 240 }">
                            <summit-card :summit="summit" />
                        </l-popup>
                    </l-marker>
                </l-map>
            </div>

            <div class="mount-map-summits">
                <h4>{{ $t('summit.title.summit_list') }}</h4>

                <div v-if="loading" class="text-center py-3">
                    <i class="fa fa-spinner fa-spin"></i>
                </div>

                <ul v-else-if="summits.length > 0" class="mount-map-summits-list">
                    <li v-for="summit in summits" :key="summit.id">
                        <router-link :to="'/summit/' + summit.url_title">{{ summit.title }}</router-link>
                        <span v-if="summit.height" class="mount-map-summit-height"> — {{ summit.height }}m</span>
                    </li>
                </ul>

                <p v-else class="text-muted">{{ $t('summit.no_summits') }}</p>
            </div>
        </StackModal>
    </div>
</template>

<script>
    import { LMap, LTileLayer, LMarker, LPopup } from '@vue-leaflet/vue-leaflet'
    import 'leaflet/dist/leaflet.css'
    import SummitCard from '../../summit/items/cards/SummitCardComponent.vue'

    const GEORGIA_CENTER = [42.3154, 43.3569]

    export default {
        name: 'MountSummitsMapModalComponent',
        components: { LMap, LTileLayer, LMarker, LPopup, SummitCard },
        props: {
            mountId: {
                type: [String, Number],
                required: true
            },
            title: {
                type: String,
                default: ''
            },
            map: {
                type: String,
                default: ''
            }
        },
        data() {
            return {
                show: false,
                loading: false,
                loaded: false,
                summits: [],
                activeTab: this.map ? 'google' : 'osm'
            }
        },
        computed: {
            geoSummits() {
                return this.summits.filter(s => s.latitude != null && s.longitude != null)
            },
            hasOsmData() {
                return this.geoSummits.length > 0
            },
            mapCenter() {
                if (this.geoSummits.length === 0) return GEORGIA_CENTER
                const lats = this.geoSummits.map(s => s.latitude)
                const lngs = this.geoSummits.map(s => s.longitude)
                return [
                    (Math.min(...lats) + Math.max(...lats)) / 2,
                    (Math.min(...lngs) + Math.max(...lngs)) / 2,
                ]
            },
            mapZoom() {
                return this.geoSummits.length > 0 ? 10 : 7
            }
        },
        methods: {
            openModal() {
                this.show = true
                if (!this.loaded) {
                    this.get_summits()
                }
            },
            get_summits() {
                this.loading = true
                axios.get('summit/list_filtered/' + this.mountId)
                    .then(response => { this.summits = response.data })
                    .catch(() => {})
                    .finally(() => {
                        this.loading = false
                        this.loaded = true
                    })
            }
        }
    }
</script>

<style scoped>
    .mount-map-modal-wrapper {
        display: inline-block;
    }

    .btn-map-open {
        background-color: #00b9e7;
        color: #fff;
        border: none;
        border-radius: 4px;
        padding: 4px 10px;
        font-size: 0.8rem;
        cursor: pointer;
        white-space: nowrap;
        transition: background 0.2s ease;
    }

    .btn-map-open:hover {
        background-color: #009dc5;
        color: #fff;
    }

    .btn-map-open .fa {
        margin-right: 4px;
    }

    .mount-map-tabs {
        margin-bottom: 1rem;
    }

    .mount-map-tabs .btn-group {
        display: flex;
    }

    .mount-map-tabs .btn-success {
        background-color: #28a745;
        border-color: #28a745;
        color: #fff;
    }

    .mount-map-tabs .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
        color: #fff;
    }

    .mount-map-content {
        width: 100%;
        min-height: 400px;
    }

    .mount-map-content :deep(iframe),
    .mount-map-content :deep(img) {
        width: 100%;
        height: 600px;
        border: none;
    }

    .mount-map-summits {
        margin-top: 1.5rem;
    }

    .mount-map-summits-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .mount-map-summits-list li {
        padding: 8px 0;
        border-bottom: 1px solid #eee;
    }

    .mount-map-summit-height {
        color: #888;
        font-size: 0.85rem;
    }
</style>
