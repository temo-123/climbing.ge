<template>
    <div class="col-md-12">
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-12 d-flex align-items-center gap-3">
                    <button class="btn btn-secondary btn-sm" @click="goBack">{{ $t('admin.routes_sectors.go_back') }}</button>
                    <h1 class="mb-0 h4">{{ $t('admin.routes_sectors.routes_drawing_editor_title') }} <small v-if="sector" class="text-muted">— {{ sector.name }}</small></h1>
                </div>
            </div>

            <div class="row mb-3">
                <!-- Left: routes list -->
                <div class="col-md-4">
                    <h5>{{ $t('common.routes') }} <small class="text-muted">{{ $t('admin.routes_sectors.one_drawing_per_route') }}</small></h5>
                    <div class="route-list mb-2" style="max-height:320px; overflow-y:auto;">
                        <div
                            v-for="route in routes"
                            :key="route.id"
                            class="route-item d-flex align-items-center justify-content-between mb-1 p-2"
                            :class="{ 'route-active': selectedRouteId === route.id }"
                            style="border:1px solid #dee2e6; border-radius:4px; background:#f8f9fa; cursor:pointer;"
                            @click="selectRoute(route.id)"
                        >
                            <span style="font-size:0.95rem; flex:1;">
                                <template v-if="route.num">{{ route.num }}.</template>
                                {{ route.name }}
                                <small class="text-muted">{{ route.grade }}</small>
                            </span>
                            <span v-if="drawingsByRoute[route.id]" class="badge bg-success ms-1" style="font-size:10px;">{{ $t('admin.routes_sectors.has_drawing') }}</span>
                        </div>
                        <div v-if="routes.length === 0" class="text-muted small p-2">{{ $t('admin.routes_sectors.no_routes_in_sector') }}</div>
                    </div>

                    <!-- MTP pitch picker: an independent entry point alongside routes above —
                         a sector can have MTPs (multi-pitch routes) with NO plain routes at
                         all, so this must not live behind selecting a route first. Styled to
                         match the routes list exactly (click-to-select rows, active highlight,
                         "has drawing" badge) rather than plain <select> dropdowns — clicking a
                         pitch activates pitch-drawing mode automatically (see selectPitch),
                         no separate mode toggle needed; clicking the active MTP/pitch again
                         deselects it. -->
                    <template v-if="available_mtps.length > 0">
                        <h5 class="mt-3">{{ $t('admin.routes_sectors.mtp_section_title') }}</h5>
                        <div class="route-list mb-2" style="max-height:160px; overflow-y:auto;">
                            <div
                                v-for="mtp in available_mtps"
                                :key="mtp.id"
                                class="route-item d-flex align-items-center justify-content-between mb-1 p-2"
                                :class="{ 'route-active': selected_mtp_id === mtp.id }"
                                style="border:1px solid #dee2e6; border-radius:4px; background:#f8f9fa; cursor:pointer;"
                                @click="selectMtp(mtp.id)"
                            >
                                <span style="font-size:0.95rem; flex:1;">
                                    <i class="fa fa-route text-muted me-1"></i>{{ mtp.name }}
                                </span>
                                <span v-if="mtp.pitchs_count" class="badge ms-1" :class="mtp.pitchs_drawn_count >= mtp.pitchs_count ? 'bg-success' : 'bg-secondary'" style="font-size:10px;">
                                    {{ mtp.pitchs_drawn_count }}/{{ mtp.pitchs_count }}
                                </span>
                            </div>
                        </div>

                        <template v-if="selected_mtp_id">
                            <div style="margin-left:8px; border-left:2px solid #dee2e6; padding-left:8px;">
                                <h6 class="text-muted mb-1">{{ $t('admin.routes_sectors.select_pitch_label') }}</h6>
                                <div class="route-list mb-2" style="max-height:160px; overflow-y:auto;">
                                    <div
                                        v-for="pitch in mtp_pitchs"
                                        :key="pitch.id"
                                        class="route-item d-flex align-items-center justify-content-between mb-1 p-2"
                                        :class="{ 'route-active': selected_pitch_id === pitch.id }"
                                        style="border:1px solid #dee2e6; border-radius:4px; background:#f8f9fa; cursor:pointer;"
                                        @click="selectPitch(pitch.id)"
                                    >
                                        <span style="font-size:0.95rem; flex:1;">
                                            <template v-if="pitch.num">{{ pitch.num }}.</template>
                                            {{ pitch.name }}
                                            <small class="text-muted">{{ pitch.grade }}</small>
                                        </span>
                                        <span v-if="pitch.json_exists" class="badge bg-success ms-1" style="font-size:10px;">{{ $t('admin.routes_sectors.has_drawing') }}</span>
                                    </div>
                                    <div v-if="mtp_pitchs.length === 0" class="text-muted small p-2">{{ $t('admin.routes_sectors.no_pitches_in_mtp') }}</div>
                                </div>
                            </div>
                        </template>

                        <span v-if="mtp_pitch_mode" class="badge bg-warning text-dark mt-1">{{ $t('admin.routes_sectors.mtp_pitch_mode_on') }}</span>
                    </template>
                </div>

                <!-- Right: sector image selector -->
                <div class="col-md-8">
                    <h5>{{ $t('admin.routes_sectors.select_background_image') }}</h5>
                    <p v-if="!selectedRouteId && !mtp_pitch_mode" class="text-muted small mb-2">{{ $t('admin.routes_sectors.select_route_or_pitch_first_hint') }}</p>
                    <template v-else>
                        <div style="max-height:220px; overflow-y:auto; border:1px solid #dee2e6; border-radius:4px; padding:8px;">
                            <div v-for="image in sectorImages" :key="image.id" class="form-check mb-1 d-flex align-items-center gap-2">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    :id="'img-' + image.id"
                                    :value="image.id"
                                    v-model="selectedImageId"
                                    :disabled="lockImageChoice"
                                >
                                <label class="form-check-label d-flex align-items-center gap-2" :for="'img-' + image.id">
                                    <img :src="'/public/images/sector_img/' + image.image" class="img-thumbnail" style="max-width:80px; max-height:80px;">
                                    {{ $t('admin.routes_sectors.image_hash_label') }}{{ image.id }}
                                </label>
                            </div>
                            <div v-if="sectorImages.length === 0" class="text-muted small">{{ $t('admin.routes_sectors.no_images_for_sector') }}</div>
                        </div>
                        <p v-if="lockImageChoice" class="text-muted small mt-1">
                            {{ $t('admin.routes_sectors.route_has_drawing_hint') }}
                        </p>
                    </template>
                </div>
            </div>

            <!-- Save / delete + status -->
            <div class="row mb-2" v-if="selectedRouteId || mtp_pitch_mode">
                <div class="col-12 d-flex align-items-center gap-2">
                    <button
                        v-if="selectedImageId && !mtp_pitch_mode"
                        type="button"
                        class="btn"
                        :class="extra_drawing_mode ? 'btn-info' : 'btn-outline-info'"
                        :disabled="extra_drawing_loading"
                        @click="toggleExtraDrawingMode"
                    >
                        <i class="fa fa-map-marker"></i>
                        {{ extra_drawing_loading ? $t('admin.routes_sectors.loading_ellipsis') : (extra_drawing_mode ? $t('admin.routes_sectors.extra_drawing_mode_on') : $t('admin.routes_sectors.add_extra_drawing')) }}
                    </button>
                    <button class="btn btn-success" :disabled="saving || !selectedImageId" @click="saveChanges">
                        <i class="fa fa-save"></i> {{ saving ? $t('admin.routes_sectors.saving_ellipsis') : (mtp_pitch_mode ? $t('admin.routes_sectors.save_pitch_drawing_btn') : (extra_drawing_mode ? $t('admin.routes_sectors.save_extra_drawing') : $t('admin.routes_sectors.save_drawing'))) }}
                    </button>
                    <button class="btn btn-danger" :disabled="deleting || !canDeleteCurrent" @click="deleteDrawing">
                        <i class="fa fa-trash"></i> {{ deleting ? $t('admin.routes_sectors.deleting_ellipsis') : (mtp_pitch_mode ? $t('admin.routes_sectors.delete_pitch_drawing_btn') : (extra_drawing_mode ? $t('admin.routes_sectors.delete_extra_drawing') : $t('admin.routes_sectors.delete_drawing'))) }}
                    </button>
                    <span v-if="saveStatus" :class="saveStatus === 'error' ? 'text-danger' : 'text-success'">
                        {{ saveStatus === 'ok' ? '✓ ' + $t('admin.routes_sectors.status_saved') : saveStatus === 'deleted' ? '✓ ' + $t('admin.routes_sectors.status_deleted') : '✗ ' + $t('admin.routes_sectors.error') }}
                    </span>
                </div>
                <div class="col-12" v-if="extra_drawing_mode">
                    <p class="text-muted mt-1 mb-0" style="font-size:12px;">
                        {{ $t('admin.routes_sectors.extra_drawing_mode_hint') }}
                    </p>
                </div>
                <div class="col-12" v-if="mtp_pitch_mode">
                    <p class="text-muted mt-1 mb-0" style="font-size:12px;">
                        {{ $t('admin.routes_sectors.mtp_pitch_mode_hint') }}
                    </p>
                </div>
            </div>

            <!-- Canvas editor -->
            <div class="row" v-if="selectedRouteId || mtp_pitch_mode">
                <div class="col-12">
                    <Editor
                        v-if="imageUrl"
                        ref="editorComponent"
                        :image_prop="imageUrl"
                        :json_prop="activeJsonProp"
                        :json_meta="activeJsonMeta"
                        :related_jsons="relatedJsons"
                        :related_jsons_meta="relatedJsonsMeta"
                        :related_first_label="relatedFirstLabel"
                        :route_name="mtp_pitch_mode ? ('Pitch ' + selectedPitchName) : (extra_drawing_mode ? 'extra info' : selectedRouteName)"
                        canvas_col_class="col-lg-8 col-md-8"
                        layers_col_class="col-lg-4 col-md-4"
                        @canvas_data="handleCanvasData"
                    />
                    <div v-else class="text-muted p-4 text-center border rounded">{{ $t('admin.routes_sectors.select_background_to_draw') }}</div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Editor from '../../../items/canvas/EditorComponent.vue'

export default {
    components: { Editor },
    data() {
        return {
            sectorId: this.$route.params.id,
            sector: null,
            routes: [],
            sectorImages: [],
            drawingsByRoute: {}, // route_id -> sector_image_id

            selectedRouteId: null,
            selectedImageId: null,
            canvasData: null,
            canvasJsonMeta: null,
            // Raw "other routes drawn on this image" fetch — always excludes the
            // current route server-side, regardless of extra-drawing mode. The
            // relatedJsons/relatedJsonsMeta computed below layer the current
            // route's own drawing / the extra drawing on top of this as needed.
            otherRoutesJson: [],
            otherRoutesJsonMeta: [],

            // "Extra drawing" mode: a general annotation layer tied only to the
            // sector image (not this route) — mirrors CanvasRouteEditorComponent.
            extra_drawing_mode: false,
            extra_drawing_json: null,
            extra_drawing_meta: null,
            extra_drawing_loading: false,

            // "MTP pitch drawing" mode: draws a pitch of a multi-pitch route
            // belonging to this sector, on the currently selected sector image —
            // saves into that pitch's own MtpPitchJson record (the SAME
            // sector_image-based endpoints CanvasPitchEditorComponent.vue uses),
            // not into this route's ClimbingRoutesJson. Unlike extra-drawing mode
            // this has no manual toggle button — a sector can have MTPs with NO
            // plain routes at all, so picking a pitch (see selectPitch) is
            // what activates it, independent of any route selection.
            mtp_pitch_mode: false,
            available_mtps: [],
            selected_mtp_id: null,
            mtp_pitchs: [],
            selected_pitch_id: null,
            pitch_json: null,
            pitch_json_meta: null,
            pitch_related_jsons: [],
            pitch_related_jsons_meta: [],

            saving: false,
            deleting: false,
            saveStatus: null,
        }
    },
    computed: {
        selectedRouteName() {
            const r = this.routes.find(r => r.id === this.selectedRouteId);
            return r ? r.name : '';
        },
        selectedPitchName() {
            const p = this.mtp_pitchs.find(p => p.id === this.selected_pitch_id);
            return p ? p.name : '';
        },
        // What the Editor actually shows/edits — the route's own drawing normally,
        // the image's general extra-info layer while that mode is toggled on, or
        // the selected MTP pitch's own drawing in pitch mode.
        activeJsonProp() {
            if (this.mtp_pitch_mode) return this.pitch_json;
            return this.extra_drawing_mode ? this.extra_drawing_json : this.canvasData;
        },
        activeJsonMeta() {
            if (this.mtp_pitch_mode) return this.pitch_json_meta;
            return this.extra_drawing_mode ? this.extra_drawing_meta : this.canvasJsonMeta;
        },
        // Reference-only overlay shown alongside whatever's actively being edited.
        // - In extra-drawing mode, this route isn't "the active drawing" anymore
        //   (the extra drawing is), so this route's own strokes join the other
        //   routes as reference — otherwise it just vanishes off the canvas.
        // - In normal route mode, the extra drawing isn't being edited, so it
        //   shows as reference too, same as any other route on this image.
        // Non-null when relatedJsons[0] is this route's own drawing or the extra
        // drawing rather than another route — always placed FIRST (not appended)
        // so other routes keep a stable position/number regardless of whether
        // this extra entry has finished loading yet. Tells the Editor to label/
        // color that one entry with this name instead of numbering it as just
        // another route.
        relatedFirstLabel() {
            if (this.mtp_pitch_mode) return null;
            if (this.extra_drawing_mode) return this.canvasData ? (this.selectedRouteName || 'this route') : null;
            return this.extra_drawing_json ? 'extra info' : null;
        },
        relatedJsons() {
            // Pitch mode's reference overlay is sibling pitches of the same MTP
            // already drawn on the currently selected sector image (fetched
            // pre-filtered by the backend), not the sibling routes shown in the
            // other two modes.
            if (this.mtp_pitch_mode) return this.pitch_related_jsons;
            const jsons = [...this.otherRoutesJson];
            if (this.extra_drawing_mode) {
                if (this.canvasData) jsons.unshift(this.canvasData);
            } else if (this.extra_drawing_json) {
                jsons.unshift(this.extra_drawing_json);
            }
            return jsons;
        },
        relatedJsonsMeta() {
            if (this.mtp_pitch_mode) return this.pitch_related_jsons_meta;
            const metas = [...this.otherRoutesJsonMeta];
            if (this.extra_drawing_mode) {
                if (this.canvasData) metas.unshift(this.canvasJsonMeta);
            } else if (this.extra_drawing_json) {
                metas.unshift(this.extra_drawing_meta);
            }
            return metas;
        },
        canDeleteCurrent() {
            if (this.mtp_pitch_mode) return !!this.pitch_json;
            return this.extra_drawing_mode ? !!this.extra_drawing_json : !!this.drawingsByRoute[this.selectedRouteId];
        },
        selectedImage() {
            return this.sectorImages.find(i => i.id === this.selectedImageId) || null;
        },
        imageUrl() {
            if (!this.selectedImage) return null;
            return this.selectedImage.has_original
                ? '/public/images/sector_img/origin_img/' + this.selectedImage.image
                : '/public/images/sector_img/' + this.selectedImage.image;
        },
        // Once a route has a saved drawing its background image is fixed — changing it
        // here would silently orphan the drawing already baked into the old image.
        // Doesn't apply in pitch mode: a pitch isn't tied to the route's own image,
        // so the admin must be free to pick whichever sector image the pitch is on.
        lockImageChoice() {
            if (this.mtp_pitch_mode) return false;
            return !!(this.selectedRouteId && this.drawingsByRoute[this.selectedRouteId]);
        },
    },
    watch: {
        // Switching routes must fully reset canvas state before loading the new route's
        // own drawing — otherwise the previous route's strokes stay live on the canvas
        // and get saved (duplicated) into the newly selected route.
        selectedRouteId(routeId) {
            this.canvasData = null;
            this.canvasJsonMeta = null;
            this.otherRoutesJson = [];
            this.otherRoutesJsonMeta = [];
            this.selectedImageId = null;
            this.saveStatus = null;

            if (!routeId) return;

            const existingImageId = this.drawingsByRoute[routeId] || null;
            if (existingImageId) this.loadRouteJson(routeId);
        },
        // Manual image pick (route has no drawing yet) — loads the other routes already
        // drawn on that image as a locked reference layer. Also reloads extra-drawing
        // state since it's tied to this specific image, not the route.
        selectedImageId(imageId) {
            if (this.mtp_pitch_mode) {
                // Pitch mode reference overlay is sibling pitches on THIS image,
                // not sibling routes — refresh it here instead of the route/extra
                // drawing state below, which pitch mode doesn't use.
                if (imageId && this.selected_pitch_id) {
                    this.fetchPitchRelatedJsons(imageId, this.selected_pitch_id);
                } else {
                    this.pitch_related_jsons = [];
                    this.pitch_related_jsons_meta = [];
                }
                return;
            }
            this.extra_drawing_mode = false;
            this.extra_drawing_json = null;
            this.extra_drawing_meta = null;
            if (imageId && !this.drawingsByRoute[this.selectedRouteId]) {
                this.fetchRelatedJsons(imageId, this.selectedRouteId);
            }
            // Eager-load (not just on first toggle) so the extra drawing is already
            // available to show as a reference overlay in normal route-editing mode
            // from the start, instead of only after the admin has opened extra-drawing
            // mode at least once this session.
            if (imageId) this.loadExtraDrawing(imageId);
        },
    },
    mounted() {
        document.querySelector('body').style.marginLeft = '0';
        const nav = document.querySelector('.admin_page_header_navbar');
        if (nav) nav.style.marginLeft = '0';
        // This page needs the full viewport width for the canvas — HomeComponent.vue
        // wraps every user-subdomain page in a Bootstrap `.container`, which caps
        // max-width per breakpoint. Drop just that class (page-local DOM tweak, not
        // an edit to HomeComponent.vue itself) and restore it on leaving so no other
        // page under this same layout is affected.
        const container = document.querySelector('.container.top_menu_margin');
        if (container) container.classList.remove('container');
        this.loadSectorData();
        this.loadMtps();
    },
    beforeUnmount() {
        const container = document.querySelector('.top_menu_margin');
        if (container) container.classList.add('container');
    },
    methods: {
        loadSectorData() {
            axios.get('/set_route/get_sector_routes_drawing_data/' + this.sectorId)
                .then(response => {
                    const d = response.data || {};
                    this.sector = d.sector || null;
                    this.routes = d.routes || [];
                    this.sectorImages = d.images || [];
                    const map = {};
                    (d.drawings || []).forEach(row => { map[row.route_id] = row.sector_image_id; });
                    this.drawingsByRoute = map;
                })
                .catch(error => console.log(error));
        },

        // Loaded eagerly (not behind a toggle) so the MTP picker shows up
        // immediately for a sector that has MTPs — including one with NO plain
        // routes at all, which otherwise had no way to reach pitch drawing.
        loadMtps() {
            axios.get('/set_mtp/get_mtps_for_sector/' + this.sectorId)
                .then(response => { this.available_mtps = response.data || []; })
                .catch(() => { this.available_mtps = []; });
        },

        selectRoute(routeId) {
            if (this.selectedRouteId === routeId) return;
            // Selecting a route always means "go back to editing this route's
            // own drawing" — exit pitch mode. Clears selected_pitch_id (not just
            // mtp_pitch_mode) so re-picking the SAME pitch afterwards still
            // fires the select's @change event; the MTP dropdown keeps its
            // value so the pitch list doesn't need refetching.
            this.mtp_pitch_mode = false;
            this.selected_pitch_id = null;
            this.pitch_json = null;
            this.pitch_json_meta = null;
            this.selectedRouteId = routeId;
        },

        loadRouteJson(routeId) {
            axios.get('/set_route/get_route_editing_data/' + routeId)
                .then(response => {
                    if (this.selectedRouteId !== routeId) return; // user already switched again
                    const d = response.data || {};
                    this.canvasData = d.json || null;
                    // The background photo's own position/size within the Paper.js view at
                    // save time — needed to rescale saved strokes onto the current fit.
                    this.canvasJsonMeta = {
                        canvas_width: d.canvas_width, canvas_height: d.canvas_height,
                        bg_left: d.bg_left, bg_top: d.bg_top,
                        bg_width: d.bg_width, bg_height: d.bg_height,
                    };
                    const imageId = d.sector_image_id || null;
                    this.selectedImageId = imageId;
                    if (imageId) this.fetchRelatedJsons(imageId, routeId);
                })
                .catch(error => console.log(error));
        },

        fetchRelatedJsons(sectorImageId, excludeRouteId) {
            axios.get('/get_route/get_related_routes_jsons', {
                params: { sector_image_id: sectorImageId, exclude_route_id: excludeRouteId }
            })
                .then(response => {
                    const items = response.data || [];
                    this.otherRoutesJson = items.map(i => i.json);
                    this.otherRoutesJsonMeta = items;
                })
                .catch(() => {});
        },

        // Loaded eagerly on image select (not just on first toggle) so it's already
        // available to show as a reference overlay in normal route-editing mode.
        async loadExtraDrawing(sectorImageId) {
            try {
                const response = await axios.get('/set_sector/set_sector_image_extra_drawing/get_for_editor/' + sectorImageId);
                const drawing = response.data && response.data.extra_drawing;
                this.extra_drawing_json = drawing ? drawing.json : null;
                this.extra_drawing_meta = drawing ? {
                    canvas_width: drawing.canvas_width, canvas_height: drawing.canvas_height,
                    bg_left: drawing.bg_left, bg_top: drawing.bg_top,
                    bg_width: drawing.bg_width, bg_height: drawing.bg_height,
                } : null;
            } catch (e) {
                this.extra_drawing_json = null;
                this.extra_drawing_meta = null;
            }
        },

        // Switches the editor between "this route's drawing" and the image's general
        // extra-info layer. Re-fetches on entry to make sure it's the freshest copy
        // (e.g. in case it changed since the eager load), same as before.
        async toggleExtraDrawingMode() {
            if (!this.extra_drawing_mode && !this.selectedImageId) {
                alert(this.$t('admin.routes_sectors.select_sector_image_first'));
                return;
            }
            if (!this.extra_drawing_mode) {
                this.mtp_pitch_mode = false; // modes are mutually exclusive
                this.extra_drawing_loading = true;
                await this.loadExtraDrawing(this.selectedImageId);
                this.extra_drawing_loading = false;
            }
            this.extra_drawing_mode = !this.extra_drawing_mode;
        },

        // Click-to-select, styled and behaving exactly like selectRoute() above —
        // clicking the already-active MTP again deselects it (collapses the
        // pitch list), same click-to-toggle affordance as the pitch row below.
        async selectMtp(mtpId) {
            this.selected_mtp_id = (this.selected_mtp_id === mtpId) ? null : mtpId;

            this.selected_pitch_id       = null;
            this.mtp_pitchs              = [];
            this.pitch_json               = null;
            this.pitch_json_meta          = null;
            this.pitch_related_jsons      = [];
            this.pitch_related_jsons_meta = [];
            this.mtp_pitch_mode           = false;
            if (!this.selected_mtp_id) return;

            try {
                const response = await axios.get('/set_mtp/set_mtp_pitch/get_mtp_pitchs_for_model/' + this.selected_mtp_id);
                this.mtp_pitchs = response.data || [];
            } catch (e) {
                this.mtp_pitchs = [];
            }
        },

        // Picking a pitch is what ACTIVATES pitch-drawing mode (no separate
        // toggle button — see the data() comment on mtp_pitch_mode) and
        // deactivates route/extra-drawing editing, so only one drawing target
        // is ever active. Clicking the already-active pitch again deselects it,
        // which is the way back out of pitch mode.
        async selectPitch(pitchId) {
            this.selected_pitch_id = (this.selected_pitch_id === pitchId) ? null : pitchId;

            this.pitch_json               = null;
            this.pitch_json_meta          = null;
            this.pitch_related_jsons      = [];
            this.pitch_related_jsons_meta = [];

            if (!this.selected_pitch_id) {
                this.mtp_pitch_mode = false;
                return;
            }

            this.mtp_pitch_mode = true;
            this.extra_drawing_mode = false;
            this.selectedRouteId = null; // also resets canvasData/selectedImageId via its own watcher

            try {
                const response = await axios.get('/set_mtp/set_mtp_pitch/get_editin_pitch/' + this.selected_pitch_id);
                const pitch = response.data;
                this.pitch_json      = (pitch && pitch.json) ? pitch.json.json : null;
                this.pitch_json_meta = (pitch && pitch.json) ? {
                    canvas_width: pitch.json.canvas_width, canvas_height: pitch.json.canvas_height,
                    bg_left: pitch.json.bg_left, bg_top: pitch.json.bg_top,
                    bg_width: pitch.json.bg_width, bg_height: pitch.json.bg_height,
                } : null;
                // A pitch drawing already carries the sector image it was drawn
                // on — switch the picker to match so reopening a pitch shows its
                // own photo, not whatever was last selected for the route.
                if (pitch && pitch.json && pitch.json.sector_image_id) {
                    this.selectedImageId = pitch.json.sector_image_id;
                }
            } catch (e) {
                this.pitch_json = null;
                this.pitch_json_meta = null;
            }

            if (this.selectedImageId) {
                this.fetchPitchRelatedJsons(this.selectedImageId, this.selected_pitch_id);
            }
        },

        fetchPitchRelatedJsons(sectorImageId, excludePitchId) {
            axios.get('/set_mtp/set_mtp_pitch/get_pitch_jsons_for_sector_image', {
                params: { sector_image_id: sectorImageId, exclude_pitch_id: excludePitchId }
            })
                .then(response => {
                    const items = response.data || [];
                    this.pitch_related_jsons      = items.map(i => i.json);
                    this.pitch_related_jsons_meta = items;
                })
                .catch(() => {});
        },

        handleCanvasData(data) {
            if (this.mtp_pitch_mode) {
                this.pitch_json = data;
            } else if (this.extra_drawing_mode) {
                this.extra_drawing_json = data;
            } else {
                this.canvasData = data;
            }
        },

        // The background photo's own actual position + size within the Paper.js
        // view — the editor fits it with a uniform cover-scale, centered, so it
        // doesn't necessarily start at (0,0) or fill the view exactly. Without
        // this, every viewer had to assume zero offset, which is exactly what
        // let saved strokes land in the wrong place once redrawn elsewhere.
        bgBoundsPayload(canvasContainer) {
            const bounds = canvasContainer && typeof canvasContainer.getBackgroundBounds === 'function'
                ? canvasContainer.getBackgroundBounds()
                : null;
            return {
                bg_left:   bounds ? bounds.left   : null,
                bg_top:    bounds ? bounds.top    : null,
                bg_width:  bounds ? bounds.width  : null,
                bg_height: bounds ? bounds.height : null,
            };
        },

        async saveChanges() {
            if (this.mtp_pitch_mode) { return this.savePitchDrawing(); }
            if (this.extra_drawing_mode) { return this.saveExtraDrawing(); }

            if (!this.selectedRouteId) { alert(this.$t('admin.routes_sectors.select_route_first_alert')); return; }
            if (!this.selectedImageId) { alert(this.$t('admin.routes_sectors.select_background_image_first')); return; }
            if (!this.$refs.editorComponent) { alert(this.$t('admin.routes_sectors.editor_not_ready')); return; }

            this.saving = true;
            this.saveStatus = null;

            try {
                const canvasContainer = this.$refs.editorComponent.$refs.canvasContainer;

                let json = this.canvasData;
                if (canvasContainer && typeof canvasContainer.getCleanJson === 'function') {
                    const cleanJson = canvasContainer.getCleanJson();
                    if (cleanJson) { json = cleanJson; this.canvasData = json; }
                }
                if (!json) { alert(this.$t('admin.routes_sectors.draw_something_first')); this.saving = false; return; }

                const selectedImage = this.selectedImage;
                const bgPath = selectedImage && selectedImage.has_original
                    ? '/public/images/sector_img/origin_img/' + selectedImage.image
                    : '/public/images/sector_img/' + (selectedImage ? selectedImage.image : '');

                const drawingDataUrl = this.captureAllDrawingStrokes(canvasContainer);
                const editedImageData = await this.compositeImages(
                    bgPath, drawingDataUrl, canvasContainer.$refs.canvasManager.$el,
                    canvasContainer.getBackgroundBounds && canvasContainer.getBackgroundBounds()
                );

                // Paper.js view size at save time — the canvas is sized responsively to the
                // browser container width, not to the photo's pixel dimensions, so any other
                // renderer (e.g. the public guidebook page) needs this to rescale correctly.
                const scope = canvasContainer.getCanvasScope();
                const canvasWidth  = scope && scope.view ? Math.round(scope.view.viewSize.width)  : null;
                const canvasHeight = scope && scope.view ? Math.round(scope.view.viewSize.height) : null;

                const response = await axios.post('/set_route/save_route_drawing', {
                    route_id: this.selectedRouteId,
                    sector_image_id: this.selectedImageId,
                    json,
                    edited_image: editedImageData,
                    canvas_width: canvasWidth,
                    canvas_height: canvasHeight,
                    ...this.bgBoundsPayload(canvasContainer),
                });

                if (response.data.success) {
                    this.saveStatus = 'ok';
                    this.drawingsByRoute = { ...this.drawingsByRoute, [this.selectedRouteId]: this.selectedImageId };
                    if (selectedImage) selectedImage.has_original = true;
                    this.$bus.$emit('route-drawing-updated', { sector_image_id: this.selectedImageId });
                    setTimeout(() => { this.saveStatus = null; }, 3000);
                } else {
                    this.saveStatus = 'error';
                }
            } catch (e) {
                console.error(e);
                this.saveStatus = 'error';
            } finally {
                this.saving = false;
            }
        },

        // Extra-drawing-mode counterparts of saveChanges/deleteDrawing above — same
        // composite-image approach, but targets SectorImageExtraDrawing (keyed only
        // by sector_image_id) instead of ClimbingRoutesJson (keyed by route_id).
        async saveExtraDrawing() {
            if (!this.selectedImageId) { alert(this.$t('admin.routes_sectors.select_sector_image_first')); return; }
            if (!this.$refs.editorComponent) { alert(this.$t('admin.routes_sectors.editor_not_ready')); return; }

            this.saving = true;
            this.saveStatus = null;

            try {
                const canvasContainer = this.$refs.editorComponent.$refs.canvasContainer;

                let json = this.extra_drawing_json;
                if (canvasContainer && typeof canvasContainer.getCleanJson === 'function') {
                    const cleanJson = canvasContainer.getCleanJson();
                    if (cleanJson) { json = cleanJson; this.extra_drawing_json = json; }
                }
                if (!json) { alert(this.$t('admin.routes_sectors.draw_something_first')); this.saving = false; return; }

                const selectedImage = this.selectedImage;
                const bgPath = selectedImage && selectedImage.has_original
                    ? '/public/images/sector_img/origin_img/' + selectedImage.image
                    : '/public/images/sector_img/' + (selectedImage ? selectedImage.image : '');

                const drawingDataUrl = this.captureAllDrawingStrokes(canvasContainer);
                const editedImageData = await this.compositeImages(
                    bgPath, drawingDataUrl, canvasContainer.$refs.canvasManager.$el,
                    canvasContainer.getBackgroundBounds && canvasContainer.getBackgroundBounds()
                );

                const scope = canvasContainer.getCanvasScope();
                const canvasWidth  = scope && scope.view ? Math.round(scope.view.viewSize.width)  : null;
                const canvasHeight = scope && scope.view ? Math.round(scope.view.viewSize.height) : null;

                const response = await axios.post('/set_sector/set_sector_image_extra_drawing/save/' + this.selectedImageId, {
                    json,
                    edited_image: editedImageData,
                    canvas_width: canvasWidth,
                    canvas_height: canvasHeight,
                    ...this.bgBoundsPayload(canvasContainer),
                });

                if (response.data.success) {
                    this.saveStatus = 'ok';
                    if (selectedImage) selectedImage.has_original = true;
                    this.$bus.$emit('route-drawing-updated', { sector_image_id: this.selectedImageId });
                    setTimeout(() => { this.saveStatus = null; }, 3000);
                } else {
                    this.saveStatus = 'error';
                }
            } catch (e) {
                console.error(e);
                this.saveStatus = 'error';
            } finally {
                this.saving = false;
            }
        },

        // Pitch-mode counterpart of saveChanges/deleteDrawing above — same
        // composite-image approach, but targets MtpPitchJson (keyed by
        // mtp_pitch_id via the SAME endpoint CanvasPitchEditorComponent.vue
        // uses) instead of ClimbingRoutesJson (keyed by route_id).
        async savePitchDrawing() {
            if (!this.selected_pitch_id) { alert(this.$t('admin.routes_sectors.select_pitch_first_alert')); return; }
            if (!this.selectedImageId) { alert(this.$t('admin.routes_sectors.select_background_image_first')); return; }
            if (!this.$refs.editorComponent) { alert(this.$t('admin.routes_sectors.editor_not_ready')); return; }

            this.saving = true;
            this.saveStatus = null;

            try {
                const canvasContainer = this.$refs.editorComponent.$refs.canvasContainer;

                let json = this.pitch_json;
                if (canvasContainer && typeof canvasContainer.getCleanJson === 'function') {
                    const cleanJson = canvasContainer.getCleanJson();
                    if (cleanJson) { json = cleanJson; this.pitch_json = json; }
                }
                if (!json) { alert(this.$t('admin.routes_sectors.draw_something_first')); this.saving = false; return; }

                const selectedImage = this.selectedImage;
                const bgPath = selectedImage && selectedImage.has_original
                    ? '/public/images/sector_img/origin_img/' + selectedImage.image
                    : '/public/images/sector_img/' + (selectedImage ? selectedImage.image : '');

                const drawingDataUrl = this.captureAllDrawingStrokes(canvasContainer);
                const editedImageData = await this.compositeImages(
                    bgPath, drawingDataUrl, canvasContainer.$refs.canvasManager.$el,
                    canvasContainer.getBackgroundBounds && canvasContainer.getBackgroundBounds()
                );

                const scope = canvasContainer.getCanvasScope();
                const canvasWidth  = scope && scope.view ? Math.round(scope.view.viewSize.width)  : null;
                const canvasHeight = scope && scope.view ? Math.round(scope.view.viewSize.height) : null;

                const response = await axios.post('/set_mtp/set_mtp_pitch/save_pitch_drawing', {
                    pitch_id: this.selected_pitch_id,
                    sector_image_id: this.selectedImageId,
                    json,
                    edited_image: editedImageData,
                    canvas_width: canvasWidth,
                    canvas_height: canvasHeight,
                    ...this.bgBoundsPayload(canvasContainer),
                });

                if (response.data.success) {
                    this.saveStatus = 'ok';
                    if (selectedImage) selectedImage.has_original = true;
                    this.$bus.$emit('route-drawing-updated', { sector_image_id: this.selectedImageId });
                    setTimeout(() => { this.saveStatus = null; }, 3000);
                } else {
                    this.saveStatus = 'error';
                }
            } catch (e) {
                console.error(e);
                this.saveStatus = 'error';
            } finally {
                this.saving = false;
            }
        },

        async deletePitchDrawing() {
            if (!this.selected_pitch_id) return;
            if (!confirm(this.$t('admin.routes_sectors.confirm_delete_pitch_drawing'))) return;

            this.deleting = true;
            this.saveStatus = null;
            try {
                await axios.delete('/set_mtp/set_mtp_pitch/del_pitch_drawing/' + this.selected_pitch_id);
                this.pitch_json = null;
                this.pitch_json_meta = null;
                this.$bus.$emit('route-drawing-updated', { sector_image_id: this.selectedImageId });
                this.saveStatus = 'deleted';
                setTimeout(() => { this.saveStatus = null; }, 3000);
            } catch (e) {
                console.error(e);
                this.saveStatus = 'error';
            } finally {
                this.deleting = false;
            }
        },

        async deleteExtraDrawing() {
            if (!this.selectedImageId) { alert(this.$t('admin.routes_sectors.no_sector_image_selected')); return; }
            if (!confirm(this.$t('admin.routes_sectors.confirm_delete_extra_drawing'))) return;

            this.deleting = true;
            this.saveStatus = null;
            try {
                await axios.delete('/set_sector/set_sector_image_extra_drawing/delete/' + this.selectedImageId);
                this.extra_drawing_json = null;
                this.extra_drawing_meta = null;
                this.$bus.$emit('route-drawing-updated', { sector_image_id: this.selectedImageId });
                this.saveStatus = 'deleted';
                setTimeout(() => { this.saveStatus = null; }, 3000);
            } catch (e) {
                console.error(e);
                this.saveStatus = 'error';
            } finally {
                this.deleting = false;
            }
        },

        async deleteDrawing() {
            if (this.mtp_pitch_mode) { return this.deletePitchDrawing(); }
            if (this.extra_drawing_mode) { return this.deleteExtraDrawing(); }
            if (!this.selectedRouteId) return;
            if (!confirm(this.$t('admin.routes_sectors.confirm_delete_route_drawing'))) return;

            this.deleting = true;
            this.saveStatus = null;
            try {
                const imageId = this.selectedImageId;
                await axios.delete('/set_route/set_route_json/del_route_json/' + this.selectedRouteId);

                const map = { ...this.drawingsByRoute };
                delete map[this.selectedRouteId];
                this.drawingsByRoute = map;

                this.canvasData = null;
                // Keep the image selected (now unlocked) so the admin can redraw on the same photo.
                if (imageId) this.fetchRelatedJsons(imageId, this.selectedRouteId);

                this.$bus.$emit('route-drawing-updated', { sector_image_id: imageId });
                this.saveStatus = 'deleted';
                setTimeout(() => { this.saveStatus = null; }, 3000);
            } catch (e) {
                console.error(e);
                this.saveStatus = 'error';
            } finally {
                this.deleting = false;
            }
        },

        // Capture ALL drawing strokes (current route + related, original colors, no bg raster).
        captureAllDrawingStrokes(canvasContainer) {
            const scope = canvasContainer.getCanvasScope();
            if (!scope) return null;

            const savedZoom    = scope.view.zoom;
            const savedCenterX = scope.view.center.x;
            const savedCenterY = scope.view.center.y;
            canvasContainer.updateView(1, { x: 0, y: 0 });

            const bgLayer       = scope.project.layers.find(l => l.name === 'background');
            const relatedLayers = scope.project.layers.filter(l => l.name && l.name.startsWith('related-'));

            // Paper.js draws a selected item's bounding box + resize handles directly
            // onto the canvas, not as a separate DOM overlay — so if the user's last
            // click before hitting Save left an item selected, toDataURL() below bakes
            // that blue selection UI straight into the saved PNG. Clear it first and
            // restore it after so the on-screen editor experience is unaffected.
            const previouslySelected = scope.project.selectedItems.slice();
            scope.project.deselectAll();

            const wasBgVisible = bgLayer ? bgLayer.visible : false;
            if (bgLayer) bgLayer.visible = false;

            const relatedWasVisible = relatedLayers.map(l => l.visible);
            relatedLayers.forEach(l => { l.visible = false; });

            const tempLayers = [];
            if (this.relatedJsons && this.relatedJsons.length > 0) {
                scope.activate();
                this.relatedJsons.forEach((jsonData, index) => {
                    if (!jsonData) return;
                    try {
                        let parsed = typeof jsonData === 'string' ? JSON.parse(jsonData) : jsonData;
                        if (typeof parsed === 'string') parsed = JSON.parse(parsed);
                        const before = scope.project.layers.length;
                        scope.project.importJSON(parsed);
                        const newLayers = scope.project.layers.slice(before);
                        // Without this, a sibling route drawn in a differently-sized
                        // container than the current session bakes in the wrong
                        // place (or off-canvas), so it silently vanishes from the
                        // saved composite JPEG even though its DB row is untouched.
                        const meta = (this.relatedJsonsMeta && this.relatedJsonsMeta[index]) || null;
                        canvasContainer.rescaleLayersToCurrentBackground(newLayers, meta);
                        newLayers.forEach(l => {
                            if (l.name === 'background') { l.remove(); return; }
                            // Older saves baked "selected":true into the stored JSON
                            // (fixed in CanvasManager._getDrawingJson); importJSON()
                            // restores it faithfully, and Paper.js renders that as a
                            // handle square on every segment of the line plus a
                            // crosshair marker — which would otherwise get burned
                            // into this composite too. Never trust it here.
                            try { l.selected = false; } catch (_) {}
                            l.name = 'temp-capture';
                            tempLayers.push(l);
                        });
                    } catch (_) {}
                });
            }

            // Keep sibling routes' reference geometry BELOW this route's own strokes,
            // matching the live editing view — otherwise an overlapping sibling shape
            // paints over this route's own strokes in the snapshot.
            const mainLayer = scope.project.layers.find(l => l.name === 'main');
            if (mainLayer) tempLayers.forEach(l => { try { l.insertBelow(mainLayer); } catch (_) {} });

            scope.view.update();
            const canvas = canvasContainer.$refs.canvasManager.$el;
            const dataUrl = canvas.toDataURL('image/png');

            tempLayers.forEach(l => { try { l.remove(); } catch (_) {} });
            if (bgLayer) bgLayer.visible = wasBgVisible;
            relatedLayers.forEach((l, i) => { l.visible = relatedWasVisible[i]; });
            previouslySelected.forEach(item => { try { item.selected = true; } catch (_) {} });
            const vs = scope.view.viewSize;
            canvasContainer.updateView(savedZoom, {
                x: savedCenterX - vs.width  / 2,
                y: savedCenterY - vs.height / 2,
            });
            scope.view.update();

            return dataUrl;
        },

        // Renders at the PHOTO's own native resolution, not the browser's current
        // on-screen canvas size — otherwise every save silently downscales the sector
        // image to whatever width the editor happened to be rendered at.
        compositeImages(bgPath, drawingDataUrl, paperCanvas, bgBounds) {
            return new Promise((resolve) => {
                const drawStrokesThenResolve = (ctx, w, h) => {
                    if (!drawingDataUrl) { resolve(ctx.canvas.toDataURL('image/jpeg', 0.9)); return; }
                    const si = new Image();
                    si.onload  = () => {
                        // The strokes were captured at the live editor's own viewport size
                        // (paperCanvas.width/height), where the background photo itself only
                        // occupies the (bgBounds.left, bgBounds.top, bgBounds.width, bgBounds.height)
                        // sub-rect — a uniform cover-fit crop, not necessarily starting at
                        // (0,0) or filling the captured canvas exactly (see getBackgroundBounds).
                        // Blindly stretching the FULL captured canvas onto the full-resolution
                        // background ignores that crop and drags every stroke out of alignment
                        // by the crop's proportion — draw only the photo-covering sub-rect.
                        if (bgBounds && bgBounds.width && bgBounds.height) {
                            ctx.drawImage(si, bgBounds.left, bgBounds.top, bgBounds.width, bgBounds.height, 0, 0, w, h);
                        } else {
                            ctx.drawImage(si, 0, 0, w, h);
                        }
                        resolve(ctx.canvas.toDataURL('image/jpeg', 0.9));
                    };
                    si.onerror = () => resolve(ctx.canvas.toDataURL('image/jpeg', 0.9));
                    si.src = drawingDataUrl;
                };
                const fallback = () => {
                    const w = paperCanvas.width, h = paperCanvas.height;
                    const offscreen = document.createElement('canvas');
                    offscreen.width = w; offscreen.height = h;
                    drawStrokesThenResolve(offscreen.getContext('2d'), w, h);
                };

                if (!bgPath) { fallback(); return; }

                const bg = new Image();
                bg.onload = () => {
                    const w = bg.naturalWidth  || paperCanvas.width;
                    const h = bg.naturalHeight || paperCanvas.height;
                    const offscreen = document.createElement('canvas');
                    offscreen.width = w; offscreen.height = h;
                    const ctx = offscreen.getContext('2d');
                    ctx.drawImage(bg, 0, 0, w, h);
                    drawStrokesThenResolve(ctx, w, h);
                };
                bg.onerror = fallback;
                bg.src = bgPath;
            });
        },

        goBack() { this.$router.go(-1); },
    }
}
</script>

<style scoped>
.route-active {
    border-color: #0d6efd !important;
    background: #e7f1ff !important;
}
</style>
