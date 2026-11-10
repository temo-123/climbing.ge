<template>
    <!-- OpenStreetMap view of the massif: one dot per summit with coordinates.
         Loaded only when the card's Map tab shows it (Leaflet is big). -->
    <!-- LMap forces an inline height: 100% on itself, so the fixed height
         lives on this wrapper (a class on l-map is overridden → 0 px map) -->
    <div class="cg-massif-osm">
    <l-map
        :zoom="zoom"
        :center="center"
        :use-global-leaflet="false"
        :options="{ scrollWheelZoom: false }"
        @ready="fit"
    >
        <l-tile-layer
            url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
            attribution="&copy; <a href='https://www.openstreetmap.org/copyright'>OpenStreetMap</a> contributors"
        />
        <l-circle-marker
            v-for="summit in summits"
            :key="summit.id"
            :lat-lng="[summit.latitude, summit.longitude]"
            :radius="8"
            :weight="2"
            color="#fff"
            :fill-color="accent"
            :fill-opacity="1"
        >
            <l-popup>
                <strong>{{ summit.title }}</strong>
                <span v-if="summit.height"> — {{ summit.height }} m</span>
                <br>
                <a :href="url(summit)" target="_blank" rel="noopener">{{ $t('guide.massif.open_summit') }}</a>
            </l-popup>
        </l-circle-marker>
    </l-map>
    </div>
</template>

<script>
    import { LMap, LTileLayer, LCircleMarker, LPopup } from '@vue-leaflet/vue-leaflet'
    import 'leaflet/dist/leaflet.css'

    export default {
        name: 'MountMassifOsmMapComponent',
        components: { LMap, LTileLayer, LCircleMarker, LPopup },
        props: {
            // summits that have latitude + longitude
            summits: { type: Array, default: () => [] },
            // summit page url builder from the card
            url: { type: Function, required: true },
        },
        data() {
            return {
                // Leaflet paints in canvas/SVG attributes, so it needs a real colour, not var()
                accent: (getComputedStyle(document.body).getPropertyValue('--cg-accent') || '').trim() || '#2E86C1',
            };
        },
        computed: {
            center() {
                if (!this.summits.length) return [42.3154, 43.3569];
                const lats = this.summits.map(s => s.latitude);
                const lngs = this.summits.map(s => s.longitude);
                return [(Math.min(...lats) + Math.max(...lats)) / 2, (Math.min(...lngs) + Math.max(...lngs)) / 2];
            },
            zoom() { return this.summits.length > 1 ? 10 : 12; },
        },
        methods: {
            // several summits: zoom so all of them fit
            fit(map) {
                if (this.summits.length > 1) {
                    map.fitBounds(this.summits.map(s => [s.latitude, s.longitude]), { padding: [30, 30], maxZoom: 13 });
                }
            },
        },
    };
</script>

<style scoped>
.cg-massif-osm {
    position: relative;
    width: 100%;
    height: 380px;
    border-radius: var(--cg-r-sm);
    overflow: hidden;
    /* keep Leaflet's panes (z-index 400+) under the sticky navbar */
    z-index: 0;
}
@media (max-width: 575.98px) {
    .cg-massif-osm { height: 280px; }
}
</style>
