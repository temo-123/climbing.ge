<template>
    <!-- GPS track of one of my summit ascents: upload a GPX file or import a
         Strava activity; it's "verified" when it passes within radius_m of the
         summit. GPX tracks are shown on a map (to me only); for Strava only
         the result is kept. API: summit_track/*, strava/* (SummitTrackController,
         StravaController). -->
    <StackModal
        v-model="show"
        :title="$t('user.track.title', { summit: summit_title })"
        size="xl"
        @close="show = false"
        :saveButton="{ visible: false }"
        :cancelButton="{ title: $t('common.close'), btnClass: { 'btn btn-secondary': true } }"
    >
        <div v-if="loading" class="text-center py-4"><span class="spinner-border text-primary" role="status"></span></div>

        <div v-else class="tv">
            <p class="tv__lead">
                <i class="fa fa-map-signs" aria-hidden="true"></i>
                {{ $t('user.track.lead', { m: radius_m }) }}
            </p>
            <div v-if="!summit_located" class="alert alert-warning py-2 small">
                <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> {{ $t('user.track.no_summit_coords') }}
            </div>

            <!-- the current track -->
            <section v-if="track" class="tv__card" :class="track.verified ? 'is-ok' : 'is-no'">
                <div class="tv__status">
                    <span v-if="track.verified" class="tv__badge is-ok"><i class="fa fa-check-circle" aria-hidden="true"></i> {{ $t('user.track.verified') }}</span>
                    <span v-else class="tv__badge is-no"><i class="fa fa-times-circle" aria-hidden="true"></i> {{ $t('user.track.not_verified') }}</span>
                    <small v-if="track.closest_m != null">{{ $t('user.track.closest', { d: distance_label(track.closest_m) }) }}</small>
                    <small class="ms-auto"><i class="fa" :class="track.source === 'strava' ? 'fa-bolt' : 'fa-file-o'" aria-hidden="true"></i> {{ track.source === 'strava' ? 'Strava' : 'GPX' }}</small>
                </div>
                <dl class="tv__stats">
                    <div><dt>{{ $t('user.track.distance') }}</dt><dd>{{ (track.distance_m / 1000).toFixed(1) }} km</dd></div>
                    <div><dt>{{ $t('user.track.gain') }}</dt><dd>{{ track.elevation_gain_m }} m</dd></div>
                    <div v-if="track.max_elevation_m != null"><dt>{{ $t('user.track.top') }}</dt><dd>{{ track.max_elevation_m }} m</dd></div>
                    <div v-if="track.duration_s"><dt>{{ $t('user.track.time') }}</dt><dd>{{ duration_label(track.duration_s) }}</dd></div>
                    <div v-if="track.started_at"><dt>{{ $t('user.track.date') }}</dt><dd>{{ $formatDate(track.started_at) }}</dd></div>
                </dl>

                <div v-if="track.points && track.points.length" class="tv__map">
                    <l-map
                        ref="map"
                        :zoom="13"
                        :center="map_center"
                        :use-global-leaflet="false"
                        :options="{ scrollWheelZoom: false }"
                        @ready="fit_map"
                    >
                        <l-tile-layer url="https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png" :max-zoom="17" attribution="© OpenStreetMap, SRTM | © OpenTopoMap" />
                        <l-polyline :lat-lngs="line" color="#e0632a" :weight="4" />
                        <template v-if="summit_located">
                            <l-circle :lat-lng="[summit.latitude, summit.longitude]" :radius="radius_m" color="#2fa36b" :weight="2" :fill-opacity="0.15" />
                            <l-marker :lat-lng="[summit.latitude, summit.longitude]" />
                        </template>
                    </l-map>
                </div>
                <p v-else-if="track.source === 'strava'" class="tv__hint"><i class="fa fa-lock" aria-hidden="true"></i> {{ $t('user.track.strava_kept') }}</p>

                <button type="button" class="btn btn-link btn-sm text-danger px-0" :disabled="busy" @click="remove">
                    <i class="fa fa-trash" aria-hidden="true"></i> {{ $t('user.track.remove') }}
                </button>
            </section>

            <!-- add / replace -->
            <h4 class="tv__h">{{ track ? $t('user.track.replace') : $t('user.track.add') }}</h4>
            <div class="tv__sources">
                <section class="tv__src">
                    <h5><i class="fa fa-file-o" aria-hidden="true"></i> {{ $t('user.track.gpx_title') }}</h5>
                    <p>{{ $t('user.track.gpx_text') }}</p>
                    <label class="btn btn-primary btn-sm mb-0" :class="{ disabled: busy }">
                        <span v-if="busy === 'gpx'" class="spinner-border spinner-border-sm me-1" aria-hidden="true"></span>
                        <i v-else class="fa fa-upload" aria-hidden="true"></i> {{ $t('user.track.gpx_choose') }}
                        <input type="file" accept=".gpx,application/gpx+xml" class="d-none" :disabled="!!busy" @change="upload_gpx">
                    </label>
                </section>

                <section class="tv__src tv__src--strava">
                    <h5><i class="fa fa-bolt" aria-hidden="true"></i> {{ $t('user.track.strava_title') }}</h5>
                    <template v-if="!strava.configured">
                        <p class="text-muted">{{ $t('user.track.strava_off') }}</p>
                    </template>
                    <template v-else-if="!strava.connected">
                        <p>{{ $t('user.track.strava_text') }}</p>
                        <button type="button" class="btn btn-strava btn-sm" :disabled="!!busy" @click="connect_strava">
                            {{ $t('user.track.strava_connect') }}
                        </button>
                    </template>
                    <template v-else>
                        <p class="mb-2">
                            {{ $t('user.track.strava_as', { name: strava.athlete_name || 'Strava' }) }}
                            <button type="button" class="btn btn-link btn-sm p-0 ms-1" @click="disconnect_strava">{{ $t('user.track.strava_disconnect') }}</button>
                        </p>
                        <div v-if="activities_loading" class="py-2"><span class="spinner-border spinner-border-sm" role="status"></span></div>
                        <p v-else-if="activities_error" class="text-danger small mb-0">{{ activities_error }}</p>
                        <p v-else-if="!activities.length" class="text-muted small mb-0">{{ $t('user.track.strava_none') }}</p>
                        <ul v-else class="tv__acts">
                            <li v-for="a in activities" :key="a.id">
                                <div>
                                    <strong>{{ a.name || a.type }}</strong>
                                    <small>{{ $formatDate(a.start_date) }} · {{ (a.distance_m / 1000).toFixed(1) }} km · ↑{{ a.elevation_gain_m }} m · {{ a.type }}</small>
                                </div>
                                <button type="button" class="btn btn-outline-primary btn-sm" :disabled="!!busy || !a.has_gps" :title="a.has_gps ? '' : $t('user.track.strava_no_gps')" @click="import_strava(a)">
                                    <span v-if="busy === a.id" class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                                    <template v-else>{{ $t('user.track.strava_use') }}</template>
                                </button>
                            </li>
                        </ul>
                        <p class="tv__hint mt-2 mb-0"><i class="fa fa-lock" aria-hidden="true"></i> {{ $t('user.track.strava_privacy') }}</p>
                    </template>
                </section>
            </div>
        </div>
    </StackModal>
</template>

<script>
    import { LMap, LTileLayer, LPolyline, LCircle, LMarker } from '@vue-leaflet/vue-leaflet'
    import 'leaflet/dist/leaflet.css'

    export default {
        name: 'TrackVerifyModal',
        components: { LMap, LTileLayer, LPolyline, LCircle, LMarker },
        emits: ['saved'],
        data() {
            return {
                show: false,
                loading: false,
                busy: null,              // 'gpx' | activity id | 'remove'
                ascent: null,
                track: null,
                summit: null,
                summit_located: false,
                radius_m: 150,
                strava: { configured: false, connected: false, athlete_name: null },
                activities: [],
                activities_loading: false,
                activities_error: '',
            };
        },
        computed: {
            summit_title() { return (this.summit && this.summit.title) || (this.ascent && this.ascent.summit && this.ascent.summit.title) || ''; },
            line() { return (this.track && this.track.points || []).map(p => [p[0], p[1]]); },
            map_center() {
                if (this.summit_located) return [this.summit.latitude, this.summit.longitude];
                return this.line[0] || [42.3, 43.3];
            },
        },
        methods: {
            open(ascent) {
                this.ascent = ascent;
                this.track = null;
                this.activities = [];
                this.activities_error = '';
                this.show = true;
                this.load();
            },
            load() {
                this.loading = true;
                Promise.all([
                    axios.get('/summit_track/' + this.ascent.id),
                    axios.get('/strava/status').catch(() => ({ data: { configured: false, connected: false } })),
                ]).then(([t, s]) => {
                    this.apply(t.data);
                    this.strava = s.data;
                    if (this.strava.connected) this.load_activities();
                }).catch(() => {
                    this.$bus.$emit('toast', { type: 'danger', message: this.$t('user.track.error') });
                    this.show = false;
                }).finally(() => { this.loading = false; });
            },
            apply(data) {
                this.track = data.track;
                this.summit = data.summit;
                this.summit_located = data.summit_located;
                this.radius_m = data.radius_m || 150;
            },
            fit_map() {
                const map = this.$refs.map && this.$refs.map.leafletObject;
                if (!map) return;
                setTimeout(() => {
                    map.invalidateSize();
                    const pts = this.line.slice();
                    if (this.summit_located) pts.push([this.summit.latitude, this.summit.longitude]);
                    if (pts.length > 1) map.fitBounds(pts, { padding: [20, 20] });
                }, 200);
            },
            message_for(e) {
                const code = e && e.response && e.response.data && (e.response.data.code || e.response.data.message);
                const known = ['not_gpx', 'no_points', 'too_many_points', 'strava_not_connected', 'strava_rate_limit', 'strava_unauthorized', 'strava_error'];
                return this.$t('user.track.errors.' + (known.includes(code) ? code : 'generic'));
            },
            after_save(data) {
                this.apply(data);
                this.$emit('saved');
                this.$bus.$emit('toast', data.track && data.track.verified
                    ? { type: 'success', message: this.$t('user.track.saved_ok') }
                    : { type: 'warning', duration: 7000, message: this.$t('user.track.saved_no', { d: this.distance_label(data.track && data.track.closest_m) }) });
            },
            upload_gpx(event) {
                const file = event.target.files && event.target.files[0];
                event.target.value = '';
                if (!file) return;
                const form = new FormData();
                form.append('file', file);
                this.busy = 'gpx';
                axios.post('/summit_track/gpx/' + this.ascent.id, form)
                    .then(r => this.after_save(r.data))
                    .catch(e => this.$bus.$emit('toast', { type: 'danger', message: this.message_for(e) }))
                    .finally(() => { this.busy = null; });
            },
            load_activities() {
                this.activities_loading = true;
                this.activities_error = '';
                axios.get('/strava/activities', { params: { date: this.ascent.ascent_date ? String(this.ascent.ascent_date).slice(0, 10) : null } })
                    .then(r => { this.activities = r.data || []; })
                    .catch(e => {
                        this.activities_error = this.message_for(e);
                        if (e.response && e.response.data && e.response.data.code === 'strava_unauthorized') this.strava.connected = false;
                    })
                    .finally(() => { this.activities_loading = false; });
            },
            import_strava(activity) {
                this.busy = activity.id;
                axios.post('/summit_track/strava/' + this.ascent.id, { activity_id: activity.id })
                    .then(r => this.after_save(r.data))
                    .catch(e => this.$bus.$emit('toast', { type: 'danger', message: this.message_for(e) }))
                    .finally(() => { this.busy = null; });
            },
            // to Strava's consent page; it comes back to /my_ascents?strava=…
            connect_strava() {
                this.busy = 'connect';
                axios.get('/strava/connect_url')
                    .then(r => { window.location.href = r.data.url; })
                    .catch(e => { this.busy = null; this.$bus.$emit('toast', { type: 'danger', message: this.message_for(e) }); });
            },
            disconnect_strava() {
                if (!confirm(this.$t('user.track.strava_disconnect_confirm'))) return;
                axios.delete('/strava/disconnect').then(() => {
                    this.strava = { ...this.strava, connected: false, athlete_name: null };
                    this.activities = [];
                });
            },
            remove() {
                if (!confirm(this.$t('user.track.remove_confirm'))) return;
                this.busy = 'remove';
                axios.delete('/summit_track/' + this.ascent.id)
                    .then(() => { this.track = null; this.$emit('saved'); })
                    .finally(() => { this.busy = null; });
            },
            distance_label(m) {
                if (m == null) return '—';
                return m >= 1000 ? (m / 1000).toFixed(1) + ' km' : m + ' m';
            },
            duration_label(s) {
                const h = Math.floor(s / 3600), m = Math.round((s % 3600) / 60);
                return h ? h + ' h ' + String(m).padStart(2, '0') + ' min' : m + ' min';
            },
        },
    };
</script>

<style scoped>
    .tv__lead { color: var(--cg-stone); }
    .tv__lead .fa { color: var(--cg-accent); }
    .tv__card { margin-bottom: 1.25rem; padding: 1rem; border: 1px solid var(--cg-line); border-left: 5px solid var(--cg-line); border-radius: var(--cg-r-lg); }
    .tv__card.is-ok { border-left-color: #2fa36b; }
    .tv__card.is-no { border-left-color: #e08a1e; }
    .tv__status { display: flex; flex-wrap: wrap; align-items: center; gap: .5rem 1rem; margin-bottom: .75rem; color: var(--cg-stone); }
    .tv__badge { display: inline-flex; align-items: center; gap: .35rem; padding: .25rem .7rem; border-radius: 2rem; font-weight: 800; }
    .tv__badge.is-ok { background: #e3f4ea; color: #1f7a4d; }
    .tv__badge.is-no { background: #fbeedb; color: #9a5b0c; }
    .tv__stats { display: grid; grid-template-columns: repeat(auto-fill, minmax(8rem, 1fr)); gap: .5rem; margin: 0 0 .75rem; }
    .tv__stats div { padding: .5rem .7rem; border-radius: var(--cg-r); background: var(--cg-chalk); }
    .tv__stats dt { color: var(--cg-muted); font-size: .72rem; font-weight: 800; letter-spacing: .05em; text-transform: uppercase; }
    .tv__stats dd { margin: 0; color: var(--cg-ink); font-weight: 800; font-size: 1.05rem; }
    .tv__map { height: 320px; margin-bottom: .5rem; border-radius: var(--cg-r); overflow: hidden; }
    .tv__hint { color: var(--cg-stone); font-size: .82rem; }
    .tv__h { margin: 0 0 .6rem; font-size: 1rem !important; font-weight: 800; }
    .tv__sources { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    @media (max-width: 767.98px) { .tv__sources { grid-template-columns: 1fr; } }
    .tv__src { padding: 1rem; border: 1px solid var(--cg-line); border-radius: var(--cg-r-lg); background: #fff; }
    .tv__src h5 { margin: 0 0 .4rem; font-size: .95rem !important; font-weight: 800; }
    .tv__src h5 .fa { color: var(--cg-accent); }
    .tv__src p { font-size: .88rem; }
    .tv__src--strava h5 .fa { color: #fc4c02; }
    .btn-strava { background: #fc4c02; border-color: #fc4c02; color: #fff; font-weight: 800; }
    .btn-strava:hover { background: #e34402; border-color: #e34402; color: #fff; }
    .tv__acts { list-style: none; margin: 0; padding: 0; max-height: 260px; overflow-y: auto; }
    .tv__acts li { display: flex; align-items: center; justify-content: space-between; gap: .75rem; padding: .5rem 0; border-top: 1px solid var(--cg-line); }
    .tv__acts li:first-child { border-top: 0; }
    .tv__acts strong { display: block; font-size: .9rem; }
    .tv__acts small { color: var(--cg-stone); }
</style>
