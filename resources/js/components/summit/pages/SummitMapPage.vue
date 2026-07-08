<template>
    <div class="h-recent-work">
        <div class="container">

            <h1 class="index_h2">{{ $t('summit.menu.map') }}</h1>
            <div class="bar"><i class="fa fa-map-marker"></i></div>

            <h3 class="article_list_short_description">
                {{ $t('summit.summit_map_description') }}
            </h3>

            <div v-if="loading" class="text-center py-5">
                <i class="fa fa-spinner fa-spin fa-3x text-muted"></i>
            </div>

            <div v-else>
                <p class="text-muted mb-2 small">
                    {{ geoSummits.length }} {{ $t('summit.map.summits_on_map') }}
                </p>

                <l-map
                    ref="map"
                    style="width: 100%; height: 540px; border-radius: 8px;"
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

            <div class="text-center mt-4">
                <router-link to="/summits/list" class="btn btn-default btn-send main-btn" style="margin: 10px 0;">
                    <i class="fa fa-list"></i> {{ $t('summit.menu.list') }}
                </router-link>
            </div>
        </div>

        <metaData
            :title="$t('summit.menu.map') + ' – Summit Climbing Georgia'"
            :description="$t('summit.summit_map_description')"
            :image="'/public/images/meta_img/mountain.jpg'"
        />
    </div>
</template>

<script>
    import metaData from '../items/MetaDataComponent.vue'
    import SummitCard from '../items/cards/SummitCardComponent.vue'
    import { LMap, LTileLayer, LMarker, LPopup } from '@vue-leaflet/vue-leaflet'
    import 'leaflet/dist/leaflet.css'

    const GEORGIA_CENTER = [42.3154, 43.3569]

    export default {
        components: { metaData, SummitCard, LMap, LTileLayer, LMarker, LPopup },
        data() {
            return {
                summits: [],
                loading: true,
            }
        },
        computed: {
            geoSummits() {
                return this.summits.filter(s => s.latitude != null && s.longitude != null)
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
                return this.geoSummits.length > 0 ? 8 : 7
            }
        },
        mounted() {
            this.load_summits()
        },
        methods: {
            load_summits() {
                axios.get('summit/list')
                    .then(response => { this.summits = response.data })
                    .catch(() => {})
                    .finally(() => { this.loading = false })
            },
        }
    }
</script>

<style scoped>
.map-info-window {
    min-width: 140px;
    line-height: 1.5;
}
</style>
