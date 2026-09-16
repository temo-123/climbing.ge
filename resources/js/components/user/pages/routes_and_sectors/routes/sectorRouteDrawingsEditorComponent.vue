<template>
    <div class="col-md-12 position-relative" ref="canvasOverlayAnchor">
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
                                    :checked="selectedImageId === image.id"
                                    :disabled="lockImageChoice"
                                    @change="selectImage(image.id)"
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
                <div class="col-12 d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <button
                            v-if="selectedImageId"
                            type="button"
                            class="btn"
                            :class="extra_drawing_mode ? 'btn-info' : 'btn-success'"
                            :disabled="extra_drawing_loading"
                            @click="toggleExtraDrawingMode"
                        >
                            <i class="fa fa-map-marker"></i>
                            {{ extra_drawing_loading ? $t('admin.routes_sectors.loading_ellipsis') : (extra_drawing_mode ? $t('admin.routes_sectors.extra_drawing_mode_on') : $t('admin.routes_sectors.add_extra_drawing')) }}
                        </button>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <button class="btn btn-success" :disabled="saving || !selectedImageId" @click="saveChanges">
                            <i class="fa fa-save"></i> {{ saving ? $t('admin.routes_sectors.saving_ellipsis') : (extra_drawing_mode ? $t('admin.routes_sectors.save_extra_drawing') : (mtp_pitch_mode ? $t('admin.routes_sectors.save_pitch_drawing_btn') : $t('admin.routes_sectors.save_drawing'))) }}
                        </button>
                        <button class="btn btn-danger" :disabled="(extra_drawing_mode ? deletingExtraDrawing : deleting) || !canDeleteCurrent" @click="deleteDrawing">
                            <i class="fa fa-trash"></i> {{ (extra_drawing_mode ? deletingExtraDrawing : deleting) ? $t('admin.routes_sectors.deleting_ellipsis') : (extra_drawing_mode ? $t('admin.routes_sectors.delete_extra_drawing') : (mtp_pitch_mode ? $t('admin.routes_sectors.delete_pitch_drawing_btn') : $t('admin.routes_sectors.delete_drawing'))) }}
                        </button>
                        <span v-if="saveStatus" :class="saveStatus === 'error' ? 'text-danger' : 'text-success'">
                            {{ saveStatus === 'ok' ? '✓ ' + $t('admin.routes_sectors.status_saved') : saveStatus === 'deleted' ? '✓ ' + $t('admin.routes_sectors.status_deleted') : '✗ ' + $t('admin.routes_sectors.error') }}
                        </span>
                    </div>
                </div>
                <div class="col-12" v-if="extra_drawing_mode">
                    <p class="text-muted mt-1 mb-0" style="font-size:12px;">
                        {{ $t('admin.routes_sectors.extra_drawing_mode_hint') }}
                    </p>
                </div>
                <div class="col-12" v-if="mtp_pitch_mode && !extra_drawing_mode">
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
                        :image_prop="lockedImageUrl"
                        :json_prop="activeJsonProp"
                        :json_meta="activeJsonMeta"
                        :related_jsons="relatedJsons"
                        :related_jsons_meta="relatedJsonsMeta"
                        :related_first_label="relatedFirstLabel"
                        :route_name="extra_drawing_mode ? 'extra info' : (mtp_pitch_mode ? ('Pitch ' + selectedPitchName) : selectedRouteName)"
                        :disable_auto_legend="true"
                        :has_legend_symbols="hasLegendSymbols"
                        canvas_col_class="col-lg-8 col-md-8"
                        layers_col_class="col-lg-4 col-md-4"
                        @canvas_data="handleCanvasData"
                    />
                    <div v-else class="text-muted p-4 text-center border rounded">{{ $t('admin.routes_sectors.select_background_to_draw') }}</div>
                </div>
            </div>
        </div>

        <!-- Item-name labels + leader lines + combined-legend preview — shared
             across every canvas-editor page/modal that lets several items
             share one background image; see canvasOverlaysMixin.js and
             CanvasOverlaysComponent.vue for the full rationale. -->
        <CanvasOverlaysComponent
            ref="canvasOverlays"
            :editor-labels="editorLabels"
            :labels-clip-style="labelsClipStyle"
            :legend-preview-style="legendPreviewStyle"
            :legend-clip-style="legendClipStyle"
        />
    </div>
</template>

<script>
import Editor from '../../../items/canvas/EditorComponent.vue'
import CanvasOverlaysComponent from '../../../items/canvas/assets/canvas/CanvasOverlaysComponent.vue'
import canvasOverlaysMixin from '../../../items/canvas/mixins/canvasOverlaysMixin.js'
import canvasExtraDrawingMixin from '../../../items/canvas/mixins/canvasExtraDrawingMixin.js'
import { drawItem, drawItemScaled } from '../../../../../services/canvas/paperJsonRenderer.js'
import { drawCombinedLegend } from '../../../../../services/canvas/legendRenderer.js'
import { canvasToJpegSized, COMPOSITE_JPEG_MIN_BYTES, COMPOSITE_JPEG_MAX_BYTES } from '../../../../../services/canvas/imageSizing.js'

export default {
    components: { Editor, CanvasOverlaysComponent },
    mixins: [canvasOverlaysMixin, canvasExtraDrawingMixin],
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
            // The background URL actually fed to the live canvas — frozen at the
            // moment a route/pitch/image is (re)selected, deliberately NOT
            // recomputed when has_original later flips true after a same-session
            // save. See the selectedImageId watcher below for why.
            lockedImageUrl: null,
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

            // canvasExtraDrawingMixin's endpoint/alert config — see its own
            // header comment for the full contract.
            extraDrawingEndpoints: {
                getForEditor: (id) => '/set_sector/set_sector_image_extra_drawing/get_for_editor/' + id,
                save:         (id) => '/set_sector/set_sector_image_extra_drawing/save/' + id,
                delete:       (id) => '/set_sector/set_sector_image_extra_drawing/delete/' + id,
            },
            extraDrawingAlertKeys: {
                noResourceSelected: 'admin.routes_sectors.select_sector_image_first',
                noDrawingData:      'admin.routes_sectors.draw_something_first',
                confirmDelete:      'admin.routes_sectors.confirm_delete_extra_drawing',
                confirmSaveBeforeSwitch: 'admin.routes_sectors.confirm_save_before_switch',
            },
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
        // the selected MTP pitch's own drawing in pitch mode, or the image's
        // general extra-info layer while that mode is toggled on. extra_drawing_mode
        // is checked FIRST everywhere in this block — it's an orthogonal overlay
        // on top of route/pitch mode (mtp_pitch_mode is left untouched while
        // toggling it, unlike an earlier version of this file that cleared it),
        // not a third exclusive mode, so it must always win regardless of
        // whether the underlying context is a route or a pitch. Getting this
        // order backwards was a real bug (fixed September 2026): with
        // mtp_pitch_mode checked first, entering extra-drawing mode from a
        // selected pitch showed/edited/saved into the PITCH's own drawing
        // instead of the extra layer — reported as "extra drawing just not
        // showing" for MTP pitches.
        activeJsonProp() {
            if (this.extra_drawing_mode) return this.extra_drawing_json;
            return this.mtp_pitch_mode ? this.pitch_json : this.canvasData;
        },
        activeJsonMeta() {
            if (this.extra_drawing_mode) return this.extra_drawing_meta;
            return this.mtp_pitch_mode ? this.pitch_json_meta : this.canvasJsonMeta;
        },
        // Reference-only overlay shown alongside whatever's actively being edited.
        // - In extra-drawing mode, the route/pitch that was active isn't "the
        //   active drawing" anymore (the extra drawing is), so its own strokes
        //   join its siblings as reference — otherwise it just vanishes off
        //   the canvas.
        // - In normal route/pitch mode, the extra drawing isn't being edited,
        //   so it shows as reference too, same as any other sibling.
        // Non-null when relatedJsons[0] is the left-behind route/pitch or the
        // extra drawing rather than another sibling — always placed FIRST (not
        // appended) so siblings keep a stable position/number regardless of
        // whether this extra entry has finished loading yet. Tells the Editor
        // to label/color that one entry with this name instead of numbering
        // it as just another sibling.
        // NOT in extra-drawing mode, the extra drawing shows as a reference
        // overlay REGARDLESS of whether the active item is a route or a
        // pitch — it's the same shared SectorImageExtraDrawing either way
        // (see the "Extra Drawing" doc section). Pitch mode's own version of
        // this used to skip it entirely (`if (mtp_pitch_mode) return null` /
        // `return pitch_related_jsons` with no extra_drawing_json check at
        // all) — a real bug (fixed September 2026, reported as "extra
        // drawing doesn't show in other drawing layouts"): editing a pitch
        // never showed the sector's extra annotations as reference, even
        // though editing a ROUTE on the very same image already did.
        relatedFirstLabel() {
            if (this.extra_drawing_mode) {
                if (this.mtp_pitch_mode) return this.pitch_json ? (this.selectedPitchName || 'this pitch') : null;
                return this.canvasData ? (this.selectedRouteName || 'this route') : null;
            }
            return this.extra_drawing_json ? 'extra info' : null;
        },
        relatedJsons() {
            // Pitch mode's OWN siblings are other pitches of the same MTP
            // already drawn on the currently selected sector image (fetched
            // pre-filtered by the backend), not the sibling routes shown in
            // route mode — but the shared extra-info layer is added on top
            // either way, same as route mode.
            if (this.extra_drawing_mode) {
                if (this.mtp_pitch_mode) {
                    const jsons = [...this.pitch_related_jsons];
                    if (this.pitch_json) jsons.unshift(this.pitch_json);
                    return jsons;
                }
                const jsons = [...this.otherRoutesJson];
                if (this.canvasData) jsons.unshift(this.canvasData);
                return jsons;
            }
            const jsons = this.mtp_pitch_mode ? [...this.pitch_related_jsons] : [...this.otherRoutesJson];
            if (this.extra_drawing_json) jsons.unshift(this.extra_drawing_json);
            return jsons;
        },
        relatedJsonsMeta() {
            if (this.extra_drawing_mode) {
                if (this.mtp_pitch_mode) {
                    const metas = [...this.pitch_related_jsons_meta];
                    if (this.pitch_json) metas.unshift(this.pitch_json_meta);
                    return metas;
                }
                const metas = [...this.otherRoutesJsonMeta];
                if (this.canvasData) metas.unshift(this.canvasJsonMeta);
                return metas;
            }
            const metas = this.mtp_pitch_mode ? [...this.pitch_related_jsons_meta] : [...this.otherRoutesJsonMeta];
            if (this.extra_drawing_json) metas.unshift(this.extra_drawing_meta);
            return metas;
        },
        canDeleteCurrent() {
            if (this.extra_drawing_mode) return !!this.extra_drawing_json;
            return this.mtp_pitch_mode ? !!this.pitch_json : !!this.drawingsByRoute[this.selectedRouteId];
        },
        selectedImage() {
            return this.sectorImages.find(i => i.id === this.selectedImageId) || null;
        },
        // Live/reactive — tracks has_original as it flips, safe for a plain
        // truthy check (v-if) but NOT for feeding the canvas component; see
        // lockedImageUrl.
        imageUrl() {
            return this.selectedImage ? this._resolveImageUrl(this.selectedImage) : null;
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
            this._mainDrawingDirty = false;

            if (!routeId) return;

            const existingImageId = this.drawingsByRoute[routeId] || null;
            if (existingImageId) this.loadRouteJson(routeId);
        },
        // Manual image pick (route has no drawing yet) — loads the other routes already
        // drawn on that image as a locked reference layer. Also reloads extra-drawing
        // state since it's tied to this specific image (not the route or pitch), in
        // EITHER mode — a pitch's sector image can be picked manually too (see
        // selectPitch/lockImageChoice), so extra-drawing must refresh there as well.
        selectedImageId(imageId) {
            this.extra_drawing_mode = false;
            this.extra_drawing_json = null;
            this.extra_drawing_meta = null;
            // Freeze the canvas's background URL for this image selection.
            // has_original can flip true mid-session once this route/pitch's
            // first save completes and backs up the origin photo — but the
            // canvas already has that exact same clean photo loaded, so
            // reactively pointing it at the new origin_img/ URL only forces
            // CanvasManager to silently reload the background in the
            // background (loadBackgroundRaster/bgLoadInFlight). If the admin
            // toggles into extra-drawing mode while that reload is still in
            // flight, the jsonProp watcher it temporarily blocks means the
            // reload's OWN completion re-imports whatever jsonProp has
            // become BY THEN (the extra drawing) onto the live canvas,
            // wiping the route/pitch's just-saved strokes from view. Only
            // re-resolve this URL when the image itself actually changes.
            // Reported September 2026 as "extra drawing save deletes the
            // route I was just editing."
            this.lockedImageUrl = this.selectedImage ? this._resolveImageUrl(this.selectedImage) : null;
            if (imageId) this.loadExtraDrawing();

            if (this.mtp_pitch_mode) {
                // Pitch mode reference overlay is sibling pitches on THIS image,
                // not sibling routes — refresh it here instead of the route
                // reference-fetch below, which pitch mode doesn't use.
                if (imageId && this.selected_pitch_id) {
                    this.fetchPitchRelatedJsons(imageId, this.selected_pitch_id);
                } else {
                    this.pitch_related_jsons = [];
                    this.pitch_related_jsons_meta = [];
                }
                return;
            }
            if (imageId && !this.drawingsByRoute[this.selectedRouteId]) {
                this.fetchRelatedJsons(imageId, this.selectedRouteId);
            }
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

        async selectRoute(routeId) {
            if (this.selectedRouteId === routeId) return;
            // Picking a different route abandons whatever's CURRENTLY shown
            // (this route/pitch's own drawing, or the shared extra layer) —
            // confirm-save first if it has unsaved edits, same protection the
            // extra-drawing toggle button itself gets. See
            // canvasExtraDrawingMixin.js's confirmSaveIfDirty() for the full
            // rationale (bug fixed September 2026).
            if (!(await this.confirmSaveIfDirty())) return;

            // Selecting a route always means "go back to editing this route's
            // own drawing" — exit pitch AND extra-drawing mode. Clears
            // selected_pitch_id (not just mtp_pitch_mode) so re-picking the
            // SAME pitch afterwards still fires the select's @change event;
            // the MTP dropdown keeps its value so the pitch list doesn't need
            // refetching.
            this.mtp_pitch_mode = false;
            this.extra_drawing_mode = false;
            this.selected_pitch_id = null;
            this.pitch_json = null;
            this.pitch_json_meta = null;
            this.selectedRouteId = routeId;
        },

        // The sector-image radio picker used to be a plain v-model — clicking
        // a radio updates the underlying data SYNCHRONOUSLY, before any
        // custom logic gets a chance to intervene with a confirm(). Converted
        // to :checked/@change so an unsaved dirty drawing can be
        // confirm-saved (or the pick can be REJECTED, leaving the radio's
        // checked state exactly as it visually was since selectedImageId
        // never actually changes) before switching backgrounds — see
        // canvasExtraDrawingMixin.js's confirmSaveIfDirty().
        async selectImage(imageId) {
            if (this.selectedImageId === imageId) return;
            if (!(await this.confirmSaveIfDirty())) return;
            this.selectedImageId = imageId;
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
                    this._mainDrawingDirty = false;
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

        // canvasExtraDrawingMixin's HOST CONTRACT hook — see its own header
        // comment for the full contract. loadExtraDrawing/toggleExtraDrawingMode/
        // saveExtraDrawing/deleteExtraDrawing themselves come entirely from
        // the mixin now.
        _extraDrawingResourceId() {
            return this.selectedImageId;
        },
        // Click-to-select, styled and behaving exactly like selectRoute() above —
        // clicking the already-active MTP again deselects it (collapses the
        // pitch list), same click-to-toggle affordance as the pitch row below.
        async selectMtp(mtpId) {
            // See selectRoute()'s comment / canvasExtraDrawingMixin.js's
            // confirmSaveIfDirty() — picking a different MTP abandons the
            // currently selected pitch's drawing (or the shared extra layer)
            // just as much as picking a different route does.
            if (!(await this.confirmSaveIfDirty())) return;

            this.selected_mtp_id = (this.selected_mtp_id === mtpId) ? null : mtpId;

            this.extra_drawing_mode      = false;
            this.selected_pitch_id       = null;
            this.mtp_pitchs              = [];
            this.pitch_json               = null;
            this.pitch_json_meta          = null;
            this.pitch_related_jsons      = [];
            this.pitch_related_jsons_meta = [];
            this.mtp_pitch_mode           = false;
            this._mainDrawingDirty        = false;
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
            // See selectRoute()'s comment / canvasExtraDrawingMixin.js's
            // confirmSaveIfDirty() — picking a different pitch (or deselecting
            // the current one) abandons whatever's CURRENTLY shown just as
            // much as picking a different route does.
            if (!(await this.confirmSaveIfDirty())) return;

            this.selected_pitch_id = (this.selected_pitch_id === pitchId) ? null : pitchId;

            this.pitch_json               = null;
            this.pitch_json_meta          = null;
            this.pitch_related_jsons      = [];
            this.pitch_related_jsons_meta = [];
            this._mainDrawingDirty        = false;

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
                this._mainDrawingDirty = false;
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
            this.markDrawingDirty();
            // extra_drawing_mode checked FIRST — see activeJsonProp's comment
            // above for why. Getting this backwards routed live edits made
            // while "editing the extra drawing" into pitch_json instead,
            // silently corrupting the pitch's own saved drawing with extra-
            // layer strokes the next time it got saved.
            if (this.extra_drawing_mode) {
                this.extra_drawing_json = data;
            } else if (this.mtp_pitch_mode) {
                this.pitch_json = data;
            } else {
                this.canvasData = data;
            }
        },
        // canvasExtraDrawingMixin's HOST CONTRACT hook for the confirm-before-
        // switch flow — saveChanges() already guards `if (extra_drawing_mode)
        // return saveExtraDrawing()` (and dispatches to savePitchDrawing() in
        // pitch mode), so calling it here (only while extra_drawing_mode is
        // still false) safely runs whichever main-drawing save path — route
        // or pitch — is currently active.
        _saveMainDrawing() {
            return this.saveChanges();
        },

        // canvasExtraDrawingMixin's HOST CONTRACT hook — without this, the
        // mixin leaves extra_drawing_meta permanently null (missing on this
        // host until now), so relatedJsonsMeta's `metas.unshift(this.
        // extra_drawing_meta)` pushed a null entry that renderCompositeAt
        // FullResolution's `if (!meta || !meta.json) return;` guard silently
        // dropped — the extra-info layer was correctly SAVED but never
        // appeared in the baked composite whenever a route/pitch was saved.
        // Reported September 2026 as "route save's composite is missing the
        // extra drawing text." Mirrors sectorLocaleImageEditorComponent.vue/
        // spotRockImageEditorComponent.vue's identical helper.
        _layoutMeta(layout) {
            if (!layout) return null;
            return {
                canvas_width: layout.canvas_width, canvas_height: layout.canvas_height,
                bg_left: layout.bg_left, bg_top: layout.bg_top,
                bg_width: layout.bg_width, bg_height: layout.bg_height,
            };
        },

        // Single source of truth for "which file is the clean background for
        // this image right now" — has_original=true means the origin_img/
        // backup exists and must be used (the main file may already be a
        // baked composite); shared by lockedImageUrl's freeze point and
        // every save method's own bgPath.
        _resolveImageUrl(image) {
            if (!image) return null;
            return image.has_original
                ? '/public/images/sector_img/origin_img/' + image.image
                : '/public/images/sector_img/' + image.image;
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

        // Returns `true`/`false` on the plain-route path (dispatch branches
        // just forward whatever savePitchDrawing()/saveExtraDrawing() return)
        // — see canvasExtraDrawingMixin.js's `_saveMainDrawing()` contract for
        // why a falsy result here matters: it stops the confirm-before-switch
        // flow from proceeding to switch modes after a save that didn't
        // actually happen.
        async saveChanges() {
            if (this.extra_drawing_mode) { return this.saveExtraDrawing(); }
            if (this.mtp_pitch_mode) { return this.savePitchDrawing(); }

            if (!this.selectedRouteId) { alert(this.$t('admin.routes_sectors.select_route_first_alert')); return false; }
            if (!this.selectedImageId) { alert(this.$t('admin.routes_sectors.select_background_image_first')); return false; }
            if (!this.$refs.editorComponent) { alert(this.$t('admin.routes_sectors.editor_not_ready')); return false; }

            this.saving = true;
            this.saveStatus = null;

            try {
                const canvasContainer = this.$refs.editorComponent.$refs.canvasContainer;

                let json = this.canvasData;
                if (canvasContainer && typeof canvasContainer.getCleanJson === 'function') {
                    const cleanJson = canvasContainer.getCleanJson();
                    if (cleanJson) { json = cleanJson; this.canvasData = json; }
                }
                if (!json) { alert(this.$t('admin.routes_sectors.draw_something_first')); this.saving = false; return false; }

                const selectedImage = this.selectedImage;
                const bgPath = this._resolveImageUrl(selectedImage);

                // Paper.js view size at save time — the canvas is sized responsively to the
                // browser container width, not to the photo's pixel dimensions, so any other
                // renderer (e.g. the public guidebook page) needs this to rescale correctly.
                // Read it from Paper.js's own state (not the DOM element's clientWidth/Height)
                // so it stays correct even if the canvas happens to be hidden/unlaid-out at
                // save time (e.g. a tab switch) — clientWidth would silently read 0 then.
                const scope = canvasContainer.getCanvasScope();
                const canvasWidth  = scope && scope.view ? Math.round(scope.view.viewSize.width)  : null;
                const canvasHeight = scope && scope.view ? Math.round(scope.view.viewSize.height) : null;
                const bgBounds = this.bgBoundsPayload(canvasContainer);

                const ownMeta = {
                    json,
                    canvas_width: canvasWidth, canvas_height: canvasHeight,
                    bg_left: bgBounds.bg_left, bg_top: bgBounds.bg_top,
                    bg_width: bgBounds.bg_width, bg_height: bgBounds.bg_height,
                };
                // Capture identity BEFORE the await — renderCompositeAtFullResolution
                // takes real wall-clock time, during which the admin can switch to a
                // different route/image; reading this.selectedRouteId/selectedImageId
                // at the post() call site afterward would then risk saving under the
                // wrong route or misattributing drawingsByRoute — same race class
                // already fixed on the sector-local-image/spot-rock/pitch editors.
                const savingRouteId = this.selectedRouteId;
                const savingImageId = this.selectedImageId;
                const siblingMetas = await this._fetchAllSiblingMetas(savingImageId, { excludeRouteId: savingRouteId });
                const editedImageData = await this.renderCompositeAtFullResolution(bgPath, ownMeta, siblingMetas);

                const response = await axios.post('/set_route/save_route_drawing', {
                    route_id: savingRouteId,
                    sector_image_id: savingImageId,
                    json,
                    edited_image: editedImageData,
                    canvas_width: canvasWidth,
                    canvas_height: canvasHeight,
                    ...bgBounds,
                });

                if (response.data.success) {
                    if (this.selectedRouteId === savingRouteId) {
                        this.saveStatus = 'ok';
                        this._mainDrawingDirty = false;
                        setTimeout(() => { this.saveStatus = null; }, 3000);
                    }
                    this.drawingsByRoute = { ...this.drawingsByRoute, [savingRouteId]: savingImageId };
                    if (selectedImage) selectedImage.has_original = true;
                    this.$bus.$emit('route-drawing-updated', { sector_image_id: savingImageId });
                    return true;
                } else {
                    this.saveStatus = 'error';
                    return false;
                }
            } catch (e) {
                console.error(e);
                this.saveStatus = 'error';
                return false;
            } finally {
                this.saving = false;
            }
        },

        // canvasExtraDrawingMixin's HOST CONTRACT hooks for save/delete — see
        // its own header comment for the full contract. saveExtraDrawing/
        // deleteExtraDrawing themselves come entirely from the mixin now;
        // this just supplies the composite-image build (same approach as
        // saveChanges above, targeting SectorImageExtraDrawing instead of
        // ClimbingRoutesJson) and the route-drawing-updated bus event a
        // sibling component listens for to refresh.
        // Fetches the COMPLETE, current set of routes + pitches saved on this
        // sector image straight from the server — deliberately NOT
        // this.relatedJsonsMeta, which only reflects whichever route/pitch
        // happens to still be loaded in this component's in-memory state
        // from earlier navigation this session. Reopening the editor and
        // going straight into extra-drawing mode without first re-selecting
        // a route left canvasData/otherRoutesJsonMeta incomplete, so that
        // route's own line silently dropped out of the extra drawing's
        // baked composite even though its own database row was untouched.
        // Reported September 2026 as "extra drawing save deletes the route
        // I was just editing" (both directions of this one bug — see the
        // _layoutMeta fix above for the other half).
        // excludeRouteId/excludePitchId omit whichever item is CURRENTLY being
        // saved — its own fresh (possibly still-unsaved) content is passed
        // separately as ownMeta by the caller, so including it here too would
        // draw a stale server-side copy underneath the live one.
        //
        // Deliberately fetches the extra drawing here too (not read from
        // this.extra_drawing_json/meta) — loadExtraDrawing() runs
        // asynchronously every time selectedImageId changes, and saving a
        // route/pitch quickly after selecting it (the natural, common case)
        // could easily beat that fetch to completion, leaving those still
        // null at save time and silently excluding the extra layer even
        // with the _layoutMeta fix above in place. Reported September 2026
        // as "extra drawing save deletes the route I was just editing —
        // happens for every route and MTP editor."
        async _fetchAllSiblingMetas(sectorImageId, { excludeRouteId, excludePitchId, excludeExtra } = {}) {
            if (!sectorImageId) return [];
            const [routesRes, pitchesRes, extraRes] = await Promise.all([
                axios.get('/get_route/get_related_routes_jsons', { params: { sector_image_id: sectorImageId } }).catch(() => ({ data: [] })),
                axios.get('/set_mtp/set_mtp_pitch/get_pitch_jsons_for_sector_image', { params: { sector_image_id: sectorImageId } }).catch(() => ({ data: [] })),
                axios.get('/set_sector_image_extra_drawing/get_for_editor/' + sectorImageId).catch(() => ({ data: {} })),
            ]);
            const metas = [];
            (routesRes.data || []).forEach(r => {
                if (excludeRouteId && r.route_id === excludeRouteId) return;
                metas.push({
                    json: r.json, canvas_width: r.canvas_width, canvas_height: r.canvas_height,
                    bg_left: r.bg_left, bg_top: r.bg_top, bg_width: r.bg_width, bg_height: r.bg_height,
                });
            });
            (pitchesRes.data || []).forEach(p => {
                if (excludePitchId && p.mtp_pitch_id === excludePitchId) return;
                metas.push({
                    json: p.json, canvas_width: p.canvas_width, canvas_height: p.canvas_height,
                    bg_left: p.bg_left, bg_top: p.bg_top, bg_width: p.bg_width, bg_height: p.bg_height,
                });
            });
            const extra = extraRes.data && extraRes.data.extra_drawing;
            if (!excludeExtra && extra && extra.json) metas.push({
                json: extra.json, canvas_width: extra.canvas_width, canvas_height: extra.canvas_height,
                bg_left: extra.bg_left, bg_top: extra.bg_top, bg_width: extra.bg_width, bg_height: extra.bg_height,
            });
            return metas;
        },

        async _buildExtraDrawingComposite(json) {
            const canvasContainer = this.$refs.editorComponent?.$refs.canvasContainer;
            const selectedImage = this.selectedImage;
            const bgPath = this._resolveImageUrl(selectedImage);

            let canvasWidth = null, canvasHeight = null;
            if (canvasContainer) {
                const scope = canvasContainer.getCanvasScope();
                canvasWidth  = scope && scope.view ? Math.round(scope.view.viewSize.width)  : null;
                canvasHeight = scope && scope.view ? Math.round(scope.view.viewSize.height) : null;
            }
            const bgBoundsPayload = this.bgBoundsPayload(canvasContainer);
            const ownMeta = {
                json,
                canvas_width: canvasWidth, canvas_height: canvasHeight,
                bg_left: bgBoundsPayload.bg_left, bg_top: bgBoundsPayload.bg_top,
                bg_width: bgBoundsPayload.bg_width, bg_height: bgBoundsPayload.bg_height,
            };
            const siblingMetas = await this._fetchAllSiblingMetas(this.selectedImageId, { excludeExtra: true });
            const editedImageData = await this.renderCompositeAtFullResolution(bgPath, ownMeta, siblingMetas);

            this._extraDrawingSelectedImage = selectedImage; // read back in _onExtraDrawingSaved
            return { editedImageData, canvasWidth, canvasHeight, bgBoundsPayload };
        },

        _onExtraDrawingSaved(responseData) {
            if (responseData.success && this._extraDrawingSelectedImage) this._extraDrawingSelectedImage.has_original = true;
            this.$bus.$emit('route-drawing-updated', { sector_image_id: this.selectedImageId });
        },

        _onExtraDrawingDeleted() {
            this.$bus.$emit('route-drawing-updated', { sector_image_id: this.selectedImageId });
        },

        // Pitch-mode counterpart of saveChanges/deleteDrawing above — same
        // composite-image approach, but targets MtpPitchJson (keyed by
        // mtp_pitch_id via the SAME endpoint CanvasPitchEditorComponent.vue
        // uses) instead of ClimbingRoutesJson (keyed by route_id).
        async savePitchDrawing() {
            if (!this.selected_pitch_id) { alert(this.$t('admin.routes_sectors.select_pitch_first_alert')); return false; }
            if (!this.selectedImageId) { alert(this.$t('admin.routes_sectors.select_background_image_first')); return false; }
            if (!this.$refs.editorComponent) { alert(this.$t('admin.routes_sectors.editor_not_ready')); return false; }

            this.saving = true;
            this.saveStatus = null;

            try {
                const canvasContainer = this.$refs.editorComponent.$refs.canvasContainer;

                let json = this.pitch_json;
                if (canvasContainer && typeof canvasContainer.getCleanJson === 'function') {
                    const cleanJson = canvasContainer.getCleanJson();
                    if (cleanJson) { json = cleanJson; this.pitch_json = json; }
                }
                if (!json) { alert(this.$t('admin.routes_sectors.draw_something_first')); this.saving = false; return false; }

                const selectedImage = this.selectedImage;
                const bgPath = this._resolveImageUrl(selectedImage);

                const scope = canvasContainer.getCanvasScope();
                const canvasWidth  = scope && scope.view ? Math.round(scope.view.viewSize.width)  : null;
                const canvasHeight = scope && scope.view ? Math.round(scope.view.viewSize.height) : null;
                const bgBounds = this.bgBoundsPayload(canvasContainer);

                const ownMeta = {
                    json,
                    canvas_width: canvasWidth, canvas_height: canvasHeight,
                    bg_left: bgBounds.bg_left, bg_top: bgBounds.bg_top,
                    bg_width: bgBounds.bg_width, bg_height: bgBounds.bg_height,
                };
                const savingPitchId = this.selected_pitch_id;
                const savingImageId = this.selectedImageId;
                const siblingMetas = await this._fetchAllSiblingMetas(savingImageId, { excludePitchId: savingPitchId });
                const editedImageData = await this.renderCompositeAtFullResolution(bgPath, ownMeta, siblingMetas);

                const response = await axios.post('/set_mtp/set_mtp_pitch/save_pitch_drawing', {
                    pitch_id: savingPitchId,
                    sector_image_id: savingImageId,
                    json,
                    edited_image: editedImageData,
                    canvas_width: canvasWidth,
                    canvas_height: canvasHeight,
                    ...bgBounds,
                });

                if (response.data.success) {
                    if (this.selected_pitch_id === savingPitchId) {
                        this.saveStatus = 'ok';
                        this._mainDrawingDirty = false;
                        setTimeout(() => { this.saveStatus = null; }, 3000);
                    }
                    if (selectedImage) selectedImage.has_original = true;
                    this.$bus.$emit('route-drawing-updated', { sector_image_id: savingImageId });
                    return true;
                } else {
                    this.saveStatus = 'error';
                    return false;
                }
            } catch (e) {
                console.error(e);
                this.saveStatus = 'error';
                return false;
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

        async deleteDrawing() {
            if (this.extra_drawing_mode) { return this.deleteExtraDrawing(); }
            if (this.mtp_pitch_mode) { return this.deletePitchDrawing(); }
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

        // Bakes the saved composite image by drawing the background photo at its own
        // full native resolution, then drawing this route's own strokes plus every
        // sibling route's strokes directly from their Paper.js JSON via the shared
        // paperJsonRenderer — the SAME algorithm the public guidebook viewer uses to
        // redraw a route from its JSON. This replaced an earlier approach that
        // captured a raster from the live (small) editing canvas via
        // canvas.toDataURL() and stretched it up to the photo's resolution, which
        // blurred every edge (bitmap upscale of an already-antialiased capture,
        // re-encoded as JPEG) and could shift colors slightly. Drawing straight from
        // the vector JSON at the target resolution is crisp by construction and
        // guarantees the baked composite always matches the live viewer exactly —
        // each path's own authored stroke/fill color is preserved (pass strokeStyle/
        // dotFillStyle/textFillStyle as null) rather than flattened to one color.
        renderCompositeAtFullResolution(bgPath, ownMeta, relatedMetas) {
            return new Promise((resolve) => {
                if (!bgPath) { resolve(null); return; }
                // This composite JPEG is a cosmetic best-effort add-on — the
                // REAL data being saved is the Paper.js json, already
                // captured before this ever runs. But the save flow `await`s
                // this whole promise BEFORE posting that json, so if it
                // never settles, the actual save never even reaches the
                // server. `bg.onload`'s body runs as a raw DOM event
                // callback, not inside an async function — any exception
                // escaping it does NOT reject this Promise, it just
                // vanishes, leaving `resolve` never called and the entire
                // save hung forever with no error shown anywhere (fixed
                // September 2026, confirmed CRITICAL via production data on
                // the sector-local-image/spot-rock siblings: saved rows had
                // completed their initial insert but had NEVER once
                // successfully recorded a later update). `finish()`
                // guarantees exactly one resolve no matter which path is
                // taken, and the timeout guarantees one fires even if the
                // image itself never loads or errors.
                let settled = false;
                const finish = (value) => { if (settled) return; settled = true; clearTimeout(timeoutId); resolve(value); };
                const timeoutId = setTimeout(() => finish(null), 15000);
                const bg = new Image();
                bg.onload = () => {
                    try {
                        const w = bg.naturalWidth, h = bg.naturalHeight;
                        const canvas = document.createElement('canvas');
                        canvas.width = w; canvas.height = h;
                        const ctx = canvas.getContext('2d');
                        ctx.drawImage(bg, 0, 0, w, h);

                        // Sibling routes render first so this route's own strokes stay on
                        // top, matching the live editing view (related layers sit below
                        // 'main' — see _repositionRelatedLayersBelow in CanvasHandlers.vue).
                        (relatedMetas || []).forEach(meta => {
                            if (!meta || !meta.json) return;
                            try { drawItemScaled(ctx, meta, w, h, null, null, null, 1, 1); } catch (_) {}
                        });
                        if (ownMeta && ownMeta.json) {
                            try { drawItemScaled(ctx, ownMeta, w, h, null, null, null, 1, 1); } catch (_) {}
                        }

                        // Bakes the ONE combined legend (every symbol type present
                        // across this route/pitch + every sibling sharing this
                        // sector image) into the saved composite — see
                        // legendRenderer.js's drawCombinedLegend for why this
                        // is safe (never reads any item's own baked-in isLegend
                        // group) and necessary (previously no legend was ever
                        // saved into the actual image file at all).
                        try {
                            // Siblings BEFORE own on purpose (bug fixed September
                            // 2026, reported as "legend position isn't synced
                            // between pitches/routes" — see canvasOverlaysMixin
                            // .js's computeEditorLegend for the same fix and its
                            // full rationale): every save bakes into this SAME
                            // shared photo file regardless of which sibling
                            // triggered it, so "own first" meant the baked
                            // position could shift depending on whichever item
                            // was saved LAST.
                            const allJsons = [...(relatedMetas || []).map(m => m && m.json), ownMeta && ownMeta.json];
                            const refWidth = (ownMeta && (ownMeta.bg_width || ownMeta.canvas_width)) || w;
                            drawCombinedLegend(ctx, w, h, allJsons, refWidth, {
                                drawItem,
                                translate: (key) => this.$t('admin.articles.canvas_editor.' + key),
                            });
                        } catch (e) { console.error('drawCombinedLegend failed:', e); }

                        finish(canvasToJpegSized(canvas, COMPOSITE_JPEG_MIN_BYTES, COMPOSITE_JPEG_MAX_BYTES));
                    } catch (e) {
                        console.error('renderCompositeAtFullResolution failed:', e);
                        finish(null);
                    }
                };
                bg.onerror = () => finish(null);
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
