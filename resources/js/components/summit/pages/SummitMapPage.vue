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

            <div v-else-if="!mapsApiKey" class="text-center py-5 text-muted">
                <i class="fa fa-map fa-3x mb-3"></i>
                <p>Map is not configured. Set <code>MIX_GOOGLE_MAPS_API_KEY</code> in <code>.env</code> to enable it.</p>
            </div>

            <div v-else>
                <p class="text-muted mb-2 small">
                    {{ summits.length }} {{ $t('summit.map.summits_on_map') }}
                </p>

                <GoogleMap
                    :api-key="mapsApiKey"
                    style="width: 100%; height: 540px; border-radius: 8px;"
                    :center="mapCenter"
                    :zoom="mapZoom"
                >
                    <Marker
                        v-for="summit in summits"
                        :key="summit.id"
                        :options="{
                            position: { lat: summit.latitude, lng: summit.longitude },
                            title: summit.title,
                        }"
                        @click="selectSummit(summit)"
                    />

                    <InfoWindow
                        v-if="selected"
                        :options="{ position: { lat: selected.latitude, lng: selected.longitude } }"
                        @closeclick="selected = null"
                    >
                        <div class="map-info-window">
                            <strong>{{ selected.title }}</strong>
                            <div v-if="selected.height" class="text-muted small">
                                <i class="fa fa-arrow-up"></i> {{ selected.height }} m
                            </div>
                            <div v-if="selected.region" class="text-muted small">
                                <i class="fa fa-map-o"></i> {{ selected.region.us_name }}
                            </div>
                            <div class="mt-1">
                                <a :href="summitUrl(selected)" target="_blank" class="btn btn-xs btn-primary" style="font-size:0.8rem;padding:2px 8px;">
                                    {{ $t('summit.map.view') }}
                                </a>
                            </div>
                        </div>
                    </InfoWindow>
                </GoogleMap>
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
    import { GoogleMap, Marker, InfoWindow } from 'vue3-google-map'

    const GEORGIA_CENTER = { lat: 42.3154, lng: 43.3569 }

    export default {
        components: { metaData, GoogleMap, Marker, InfoWindow },
        data() {
            return {
                summits: [],
                loading: true,
                selected: null,
                mapsApiKey: process.env.MIX_GOOGLE_MAPS_API_KEY || '',
            }
        },
        computed: {
            mapCenter() {
                if (this.summits.length === 0) return GEORGIA_CENTER
                const lats = this.summits.map(s => s.latitude)
                const lngs = this.summits.map(s => s.longitude)
                return {
                    lat: (Math.min(...lats) + Math.max(...lats)) / 2,
                    lng: (Math.min(...lngs) + Math.max(...lngs)) / 2,
                }
            },
            mapZoom() {
                return this.summits.length > 0 ? 8 : 7
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
            selectSummit(summit) {
                this.selected = summit
            },
            summitUrl(summit) {
                const base = (process.env.MIX_APP_SSH || '').replace(/\/$/, '')
                const summit_sub = (process.env.MIX_SUMMIT_URL || '').replace(/^\/|\/$/g, '')
                return `${base}/${summit_sub}/summit/${summit.url_title}`
            }
        }
    }
</script>

<style scoped>
.map-info-window {
    min-width: 140px;
    line-height: 1.5;
}
</style>
